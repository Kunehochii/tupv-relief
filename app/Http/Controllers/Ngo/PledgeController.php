<?php

namespace App\Http\Controllers\Ngo;

use App\Http\Controllers\Controller;
use App\Models\Drive;
use App\Models\Pledge;
use App\Models\PledgeItem;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
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
        /** @var User $user */
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

    public function create(Request $request): View|RedirectResponse
    {
        $driveId = $request->get('drive') ?? $request->get('drive_id');

        if (!$driveId) {
            return redirect()->route('ngo.dashboard')
                ->with('warning', 'Please select a drive from the list below to make a pledge.');
        }

        $selectedDrive = Drive::with('driveItems')->find($driveId);

        if (!$selectedDrive || !$selectedDrive->isActive()) {
            return redirect()->route('ngo.dashboard')
                ->with('warning', 'The selected drive is no longer available. Please choose another drive.');
        }

        // NGOs can see exact quantities
        $drives = Drive::active()->with('driveItems')->get();
        $this->attachAvailablePledgeQuantities($drives);
        $this->attachAvailablePledgeQuantities(collect([$selectedDrive]));

        return view('ngo.pledges.create', compact('drives', 'selectedDrive'));
    }

    private function attachAvailablePledgeQuantities(Collection $drives): void
    {
        $driveItemIds = $drives
            ->flatMap(fn($drive) => $drive->driveItems->pluck('id'))
            ->unique()
            ->values();

        if ($driveItemIds->isEmpty()) {
            return;
        }

        $pendingByDriveItem = PledgeItem::query()
            ->selectRaw('drive_item_id, COALESCE(SUM(quantity), 0) as pending_quantity')
            ->whereIn('drive_item_id', $driveItemIds)
            ->whereHas('pledge', fn($query) => $query->where('status', Pledge::STATUS_PENDING))
            ->groupBy('drive_item_id')
            ->pluck('pending_quantity', 'drive_item_id');

        $drives->each(function ($drive) use ($pendingByDriveItem) {
            $drive->driveItems->each(function ($item) use ($pendingByDriveItem) {
                $pendingQuantity = (float) ($pendingByDriveItem[$item->id] ?? 0);
                $alreadyReserved = (float) $item->quantity_pledged + $pendingQuantity;
                $availableForPledge = max(0, (float) $item->quantity_needed - $alreadyReserved);

                $item->setAttribute('pending_quantity', $pendingQuantity);
                $item->setAttribute('available_for_pledge', $availableForPledge);
            });
        });
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
            'contact_number' => ['nullable', 'string', 'max:20'],
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

        $requestedItems = collect($validated['items'] ?? [])
            ->filter(fn($item) => isset($item['quantity']) && (int) $item['quantity'] > 0)
            ->values();

        $pledge = DB::transaction(function () use ($validated, $requestedItems) {
            $drive = Drive::query()
                ->with('driveItems')
                ->lockForUpdate()
                ->findOrFail($validated['drive_id']);

            if (!$drive->isActive()) {
                throw ValidationException::withMessages([
                    'drive_id' => 'This drive is no longer accepting pledges.',
                ]);
            }

            $requestedDriveItemIds = $requestedItems
                ->pluck('drive_item_id')
                ->unique()
                ->values();

            $driveItems = $drive->driveItems()
                ->whereIn('id', $requestedDriveItemIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $pendingByDriveItem = PledgeItem::query()
                ->selectRaw('drive_item_id, COALESCE(SUM(quantity), 0) as pending_quantity')
                ->whereIn('drive_item_id', $requestedDriveItemIds)
                ->whereHas('pledge', fn($query) => $query->where('status', Pledge::STATUS_PENDING))
                ->groupBy('drive_item_id')
                ->pluck('pending_quantity', 'drive_item_id');

            $itemErrors = [];

            foreach ($requestedItems as $item) {
                $driveItem = $driveItems->get($item['drive_item_id']);
                $requestedQuantity = (float) $item['quantity'];

                if (!$driveItem) {
                    $itemErrors[] = 'One or more selected items do not belong to this drive.';
                    continue;
                }

                $alreadyReserved = (float) $driveItem->quantity_pledged + (float) ($pendingByDriveItem[$driveItem->id] ?? 0);
                $remainingCapacity = max(0, (float) $driveItem->quantity_needed - $alreadyReserved);

                if ($requestedQuantity > $remainingCapacity) {
                    $itemErrors[] = sprintf(
                        '%s: only %s %s remaining for new pledges.',
                        $driveItem->item_name,
                        rtrim(rtrim(number_format($remainingCapacity, 2, '.', ''), '0'), '.'),
                        $driveItem->unit
                    );
                }
            }

            if (!empty($itemErrors)) {
                throw ValidationException::withMessages([
                    'items' => array_values(array_unique($itemErrors)),
                ]);
            }

            $pledge = Pledge::create([
                'user_id' => Auth::id(),
                'drive_id' => $validated['drive_id'],
                'pledge_type' => 'in-kind',
                'details' => $validated['details'] ?? null,
                'contact_number' => $validated['contact_number'] ?? 'N/A',
                'notes' => $validated['notes'] ?? null,
                'status' => Pledge::STATUS_PENDING,
            ]);

            // Create pledge items
            if ($requestedItems->isNotEmpty()) {
                foreach ($requestedItems as $item) {
                    $driveItem = $driveItems->get($item['drive_item_id']);

                    PledgeItem::create([
                        'pledge_id' => $pledge->id,
                        'drive_item_id' => $driveItem->id,
                        'item_name' => $driveItem->item_name,
                        'quantity' => $item['quantity'],
                        'unit' => $driveItem->unit,
                    ]);
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
