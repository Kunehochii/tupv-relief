<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drive;
use App\Models\LinkClick;
use App\Models\Pledge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        // Build query based on filters
        $pledgeQuery = Pledge::query();
        
        if ($request->filled('date_from')) {
            $pledgeQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $pledgeQuery->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('drive_id')) {
            $pledgeQuery->where('drive_id', $request->drive_id);
        }

        // Get all drives for filter dropdown
        $drives = Drive::orderBy('name')->get();

        // Calculate stats based on actual schema
        $pledges = $pledgeQuery->get();
        $stats = [
            'total_pledges' => $pledges->count(),
            'total_inkind' => $pledges->sum('quantity'),
            'active_donors' => User::whereHas('pledges', function ($query) {
                $query->where('created_at', '>=', now()->subMonth());
            })->count(),
            'families_helped' => $pledges->sum('families_helped'),
        ];

        // Drive performance stats
        $driveStats = Drive::withCount('pledges')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        // Top donors with total pledges and quantities
        $topDonors = User::whereIn('role', [User::ROLE_DONOR, User::ROLE_NGO])
            ->withCount('pledges')
            ->with('pledges')
            ->get()
            ->map(function ($user) {
                $user->total_quantity = $user->pledges->sum('quantity');
                return $user;
            })
            ->sortByDesc('pledges_count')
            ->take(10);

        // NGO stats with link clicks
        $ngoStats = User::where('role', User::ROLE_NGO)
            ->withCount(['pledges', 'linkClicks'])
            ->with('pledges')
            ->get()
            ->map(function ($ngo) {
                $ngo->total_quantity = $ngo->pledges->sum('quantity');
                return $ngo;
            });

        return view('admin.reports.index', compact(
            'drives',
            'stats',
            'driveStats',
            'topDonors',
            'ngoStats'
        ));
    }

    public function donationSummary(Request $request): View
    {
        $startDate = $request->get('start_date', now()->subMonth());
        $endDate = $request->get('end_date', now());

        $pledges = Pledge::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'drive'])
            ->get();

        $summary = [
            'total_pledges' => $pledges->count(),
            'verified_pledges' => $pledges->where('status', Pledge::STATUS_VERIFIED)->count(),
            'distributed_pledges' => $pledges->where('status', Pledge::STATUS_DISTRIBUTED)->count(),
            'expired_pledges' => $pledges->where('status', Pledge::STATUS_EXPIRED)->count(),
            'total_families_helped' => $pledges->sum('families_helped'),
            'total_relief_packages' => $pledges->sum('relief_packages'),
        ];

        return view('admin.reports.donation-summary', compact('pledges', 'summary', 'startDate', 'endDate'));
    }

    public function drivePerformance(): View
    {
        $drives = Drive::withCount(['pledges', 'pledges as verified_pledges_count' => function ($query) {
            $query->where('status', Pledge::STATUS_VERIFIED);
        }])
        ->get();

        return view('admin.reports.drive-performance', compact('drives'));
    }

    public function donorStatistics(): View
    {
        $topDonors = User::whereIn('role', [User::ROLE_DONOR, User::ROLE_NGO])
            ->withCount('pledges')
            ->orderByDesc('pledges_count')
            ->take(20)
            ->get();

        $stats = [
            'total_donors' => User::where('role', User::ROLE_DONOR)->count(),
            'total_ngos' => User::where('role', User::ROLE_NGO)->count(),
            'active_donors' => User::whereHas('pledges', function ($query) {
                $query->where('created_at', '>=', now()->subMonth());
            })->count(),
        ];

        return view('admin.reports.donor-statistics', compact('topDonors', 'stats'));
    }

    public function export(Request $request): StreamedResponse
    {
        $type = $request->get('type', 'pledges');
        $filename = "relief_{$type}_" . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($type) {
            $handle = fopen('php://output', 'w');

            switch ($type) {
                case 'pledges':
                    $this->exportPledges($handle);
                    break;
                case 'donors':
                    $this->exportDonors($handle);
                    break;
                case 'drives':
                    $this->exportDrives($handle);
                    break;
                case 'ngos':
                    $this->exportNgos($handle);
                    break;
                case 'impact':
                    $this->exportImpact($handle);
                    break;
                default:
                    $this->exportPledges($handle);
            }

            fclose($handle);
        }, 200, $headers);
    }

    private function exportPledges($handle): void
    {
        fputcsv($handle, [
            'Reference Number',
            'Donor Name',
            'Donor Type',
            'Drive',
            'Items',
            'Quantity',
            'Details',
            'Status',
            'Families Helped',
            'Relief Packages',
            'Items Distributed',
            'Created At',
            'Verified At',
            'Distributed At'
        ]);

        Pledge::with(['user', 'drive'])->chunk(100, function ($pledges) use ($handle) {
            foreach ($pledges as $pledge) {
                fputcsv($handle, [
                    $pledge->reference_number,
                    $pledge->user->name ?? 'N/A',
                    $pledge->user->role ?? 'N/A',
                    $pledge->drive->name ?? 'N/A',
                    is_array($pledge->items) ? implode(', ', $pledge->items) : $pledge->items,
                    $pledge->quantity,
                    $pledge->details,
                    $pledge->status,
                    $pledge->families_helped,
                    $pledge->relief_packages,
                    $pledge->items_distributed,
                    $pledge->created_at?->format('Y-m-d H:i:s'),
                    $pledge->verified_at?->format('Y-m-d H:i:s'),
                    $pledge->distributed_at?->format('Y-m-d H:i:s'),
                ]);
            }
        });
    }

    private function exportDonors($handle): void
    {
        fputcsv($handle, [
            'Name',
            'Email',
            'Role',
            'Organization',
            'Total Pledges',
            'Total Quantity',
            'Registered At'
        ]);

        User::whereIn('role', [User::ROLE_DONOR, User::ROLE_NGO])
            ->withCount('pledges')
            ->with('pledges')
            ->chunk(100, function ($users) use ($handle) {
                foreach ($users as $user) {
                    fputcsv($handle, [
                        $user->name,
                        $user->email,
                        $user->role,
                        $user->organization_name,
                        $user->pledges_count,
                        $user->pledges->sum('quantity'),
                        $user->created_at?->format('Y-m-d H:i:s'),
                    ]);
                }
            });
    }

    private function exportDrives($handle): void
    {
        fputcsv($handle, [
            'Drive Name',
            'Description',
            'Target Type',
            'Target Amount',
            'Collected Amount',
            'Progress %',
            'Status',
            'Total Pledges',
            'Start Date',
            'End Date',
            'Address'
        ]);

        Drive::withCount('pledges')->chunk(100, function ($drives) use ($handle) {
            foreach ($drives as $drive) {
                fputcsv($handle, [
                    $drive->name,
                    $drive->description,
                    $drive->target_type,
                    $drive->target_amount,
                    $drive->collected_amount,
                    $drive->progress_percentage,
                    $drive->status,
                    $drive->pledges_count,
                    $drive->start_date?->format('Y-m-d'),
                    $drive->end_date?->format('Y-m-d'),
                    $drive->address,
                ]);
            }
        });
    }

    private function exportNgos($handle): void
    {
        fputcsv($handle, [
            'Organization Name',
            'Contact Name',
            'Email',
            'Verification Status',
            'Total Pledges',
            'Total Quantity',
            'Link Clicks',
            'Registered At',
            'Verified At'
        ]);

        User::where('role', User::ROLE_NGO)
            ->withCount(['pledges', 'linkClicks'])
            ->with('pledges')
            ->chunk(100, function ($ngos) use ($handle) {
                foreach ($ngos as $ngo) {
                    fputcsv($handle, [
                        $ngo->organization_name,
                        $ngo->name,
                        $ngo->email,
                        $ngo->verification_status,
                        $ngo->pledges_count,
                        $ngo->pledges->sum('quantity'),
                        $ngo->link_clicks_count,
                        $ngo->created_at?->format('Y-m-d H:i:s'),
                        $ngo->verified_at?->format('Y-m-d H:i:s'),
                    ]);
                }
            });
    }

    private function exportImpact($handle): void
    {
        fputcsv($handle, [
            'Drive Name',
            'Total Pledges',
            'Verified Pledges',
            'Distributed Pledges',
            'Families Helped',
            'Relief Packages',
            'Items Distributed',
            'Total Quantity'
        ]);

        Drive::with(['pledges' => function ($query) {
            $query->whereIn('status', [Pledge::STATUS_VERIFIED, Pledge::STATUS_DISTRIBUTED]);
        }])->chunk(100, function ($drives) use ($handle) {
            foreach ($drives as $drive) {
                fputcsv($handle, [
                    $drive->name,
                    $drive->pledges->count(),
                    $drive->pledges->where('status', Pledge::STATUS_VERIFIED)->count(),
                    $drive->pledges->where('status', Pledge::STATUS_DISTRIBUTED)->count(),
                    $drive->pledges->sum('families_helped'),
                    $drive->pledges->sum('relief_packages'),
                    $drive->pledges->sum('items_distributed'),
                    $drive->pledges->sum('quantity'),
                ]);
            }
        });
    }
}
