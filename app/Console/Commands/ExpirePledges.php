<?php

namespace App\Console\Commands;

use App\Models\Pledge;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class ExpirePledges extends Command
{
    protected $signature = 'pledges:expire';
    protected $description = 'Expire unverified pledges after 24 hours';

    public function handle(NotificationService $notificationService): int
    {
        $expiredPledges = Pledge::where('status', Pledge::STATUS_PENDING)
            ->where('created_at', '<=', now()->subHours(24))
            ->get();

        foreach ($expiredPledges as $pledge) {
            $pledge->update([
                'status' => Pledge::STATUS_EXPIRED,
                'expired_at' => now(),
            ]);

            $notificationService->sendPledgeExpired($pledge);

            $this->info("Expired pledge: {$pledge->reference_number}");
        }

        $this->info("Expired {$expiredPledges->count()} pledges.");

        return Command::SUCCESS;
    }
}
