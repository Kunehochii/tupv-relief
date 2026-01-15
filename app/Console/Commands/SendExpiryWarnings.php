<?php

namespace App\Console\Commands;

use App\Models\Pledge;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendExpiryWarnings extends Command
{
    protected $signature = 'pledges:warn-expiry';
    protected $description = 'Send 18-hour warning for unverified pledges';

    public function handle(NotificationService $notificationService): int
    {
        // Find pledges that are 18 hours old but not yet 24 hours
        $pledges = Pledge::where('status', Pledge::STATUS_PENDING)
            ->where('created_at', '<=', now()->subHours(18))
            ->where('created_at', '>', now()->subHours(24))
            ->whereDoesntHave('notifications', function ($query) {
                $query->where('type', 'pledge_expiry_warning');
            })
            ->get();

        foreach ($pledges as $pledge) {
            $notificationService->sendPledgeExpiryWarning($pledge);
            $this->info("Sent expiry warning for pledge: {$pledge->reference_number}");
        }

        $this->info("Sent {$pledges->count()} expiry warnings.");

        return Command::SUCCESS;
    }
}
