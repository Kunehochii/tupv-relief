<?php

namespace App\Http\Controllers\Ngo;

use App\Http\Controllers\Controller;
use App\Models\Drive;
use App\Models\Pledge;
use App\Models\PledgeItem;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PledgeController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function index(): View
    {
        $user = Auth::user();

        $pledges = $user->pledges()
            ->with(['drive', 'pledgeItems'])
            ->latest()
            ->paginate(15);

        $impact = [
            'families_helped' => $user->pledges()->sum('families_helped'),
            'items_distributed' => $user->pledges()
                ->join('pledge_items', 'pledges.id', '=', 'pledge_items.pledge_id')
                ->sum('pledge_items.quantity_distributed'),
            'relief_packages' => $user->pledges()->sum('relief_packages'),
        ];

        return view('ngo.pledges.index', compact('pledges', 'impact'));
    }

    public function create(Request $request): View
    {
        // NGOs can see exact quantities
        $drives = Drive::active()->with('driveItems')->get();
        $selectedDrive = $request->get('drive_id')
            ? Drive::with('driveItems')->find($request->get('drive_id'))
            : null;

        return view('ngo.pledges.create', compact('drives', 'selectedDrive'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'drive_id' => ['required', 'exists:drives,id'],
            'pledge_type' => ['required', 'in:in-kind'],
            'items' => ['nullable', 'array'],
            'items.*.drive_item_id' => ['required_with:items.*.quantity', 'exists:drive_items,id'],
            'items.*.quantity' => ['nullable', 'integer', 'min:1'],
            'details' => ['nullable', 'string', 'max:1000'],
            'contact_number' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        // Ensure at least one item has a valid quantity
        $hasValidItems = false;
        if (!empty($validated['items'])) {
            foreach ($validated['items'] as $item) {
                if (isset($item['quantity']) && $item['quantity'] > 0) {
                    $hasValidItems = true;
                    break;
                }
            }
        }

        if (!$hasValidItems) {
            return back()
                ->withInput()
                ->withErrors(['items' => 'Please enter a quantity for at least one item.']);
        }

        $drive = Drive::findOrFail($validated['drive_id']);

        if (!$drive->isActive()) {
            return back()->with('error', 'This drive is no longer accepting pledges.');
        }

        $pledge = DB::transaction(function () use ($validated, $drive) {
            $pledge = Pledge::create([
                'user_id' => Auth::id(),
                'drive_id' => $validated['drive_id'],
                'pledge_type' => 'in-kind',
                'details' => $validated['details'] ?? null,
                'contact_number' => $validated['contact_number'],
                'notes' => $validated['notes'] ?? null,
                'status' => Pledge::STATUS_PENDING,
            ]);

            // Create pledge items
            if (!empty($validated['items'])) {
                foreach ($validated['items'] as $item) {
                    if (isset($item['quantity']) && $item['quantity'] > 0) {
                        $driveItem = $drive->driveItems()->find($item['drive_item_id']);

                        if ($driveItem) {
                            PledgeItem::create([
                                'pledge_id' => $pledge->id,
                                'drive_item_id' => $driveItem->id,
                                'item_name' => $driveItem->item_name,
                                'quantity' => $item['quantity'],
                                'unit' => $driveItem->unit,
                            ]);
                        }
                    }
                }
            }

            return $pledge;
        });

        $this->notificationService->sendPledgeAcknowledged($pledge);

        // Notify donors who have pledged to this drive that an NGO is also supporting it
        $this->notificationService->sendNgoPledgeAddedToDonors($pledge);

        return redirect()->route('ngo.pledges.show', $pledge)
            ->with('success', 'Pledge submitted successfully! Reference: ' . $pledge->reference_number);
    }

    public function show(Pledge $pledge): View
    {
        $this->authorize('view', $pledge);

        $pledge->load(['drive', 'verifier', 'pledgeItems']);

        return view('ngo.pledges.show', compact('pledge'));
    }
}
