<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\Drive;
use App\Models\Pledge;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PledgeController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function index(): View
    {
        $pledges = Auth::user()
            ->pledges()
            ->with('drive')
            ->latest()
            ->paginate(15);

        $impact = [
            'families_helped' => Auth::user()->pledges()->sum('families_helped'),
            'items_distributed' => Auth::user()->pledges()->sum('items_distributed'),
            'relief_packages' => Auth::user()->pledges()->sum('relief_packages'),
        ];

        return view('donor.pledges.index', compact('pledges', 'impact'));
    }

    public function create(Request $request): View
    {
        $drives = Drive::active()->get();
        $selectedDrive = $request->get('drive_id') ? Drive::find($request->get('drive_id')) : null;

        return view('donor.pledges.create', compact('drives', 'selectedDrive'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'drive_id' => ['required', 'exists:drives,id'],
            'items' => ['required', 'array'],
            'items.*' => ['string'],
            'quantity' => ['required', 'integer', 'min:1'],
            'details' => ['nullable', 'string', 'max:1000'],
            'contact_number' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $drive = Drive::findOrFail($validated['drive_id']);
        
        if (!$drive->isActive()) {
            return back()->with('error', 'This drive is no longer accepting pledges.');
        }

        $pledge = Pledge::create([
            'user_id' => Auth::id(),
            'drive_id' => $validated['drive_id'],
            'items' => $validated['items'],
            'quantity' => $validated['quantity'],
            'details' => $validated['details'],
            'contact_number' => $validated['contact_number'],
            'notes' => $validated['notes'],
            'status' => Pledge::STATUS_PENDING,
        ]);

        $this->notificationService->sendPledgeAcknowledged($pledge);

        return redirect()->route('donor.pledges.show', $pledge)
            ->with('success', 'Pledge submitted successfully! Reference: ' . $pledge->reference_number);
    }

    public function show(Pledge $pledge): View
    {
        $this->authorize('view', $pledge);
        
        $pledge->load(['drive', 'verifier']);
        
        return view('donor.pledges.show', compact('pledge'));
    }
}
