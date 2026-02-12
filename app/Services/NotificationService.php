<?php

namespace App\Services;

use App\Mail\NotificationMail;
use App\Mail\PledgeSubmittedMail;
use App\Models\Notification;
use App\Models\Pledge;
use App\Models\PledgeItem;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Create an in-app notification and optionally send email
     */
    protected function createNotification(
        User $user,
        string $type,
        string $title,
        string $message,
        array $data = [],
        bool $sendEmail = true,
        ?string $link = null
    ): Notification {
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);

        if ($sendEmail) {
            $this->sendEmail($user, $title, $message, $type, $link);
            $notification->update(['emailed_at' => now()]);
        }

        return $notification;
    }

    /**
     * Send email notification via SendGrid
     */
    protected function sendEmail(User $user, string $subject, string $message, string $type, ?string $link = null): void
    {
        try {
            Mail::to($user->email)->send(new NotificationMail($subject, $message, $type, $user, $link));
        } catch (\Exception $e) {
            Log::error('Failed to send notification email: ' . $e->getMessage());
        }
    }

    /**
     * Send a dedicated pledge email (with full pledge details)
     */
    protected function sendPledgeEmail(Pledge $pledge): void
    {
        try {
            Mail::to($pledge->user->email)->send(new PledgeSubmittedMail($pledge));
        } catch (\Exception $e) {
            Log::error('Failed to send pledge submitted email: ' . $e->getMessage());
        }
    }

    public function sendPledgeAcknowledged(Pledge $pledge): void
    {
        $notification = $this->createNotification(
            $pledge->user,
            Notification::TYPE_PLEDGE_ACKNOWLEDGED,
            'Pledge Received',
            "Your pledge (Ref: {$pledge->reference_number}) for {$pledge->drive->name} has been received and is pending verification.",
            ['pledge_id' => $pledge->id, 'reference_number' => $pledge->reference_number],
            false // Email is sent separately with detailed template
        );

        // Send dedicated pledge details email
        $this->sendPledgeEmail($pledge);
        $notification->update(['emailed_at' => now()]);
    }

    public function sendPledgeVerified(Pledge $pledge): void
    {
        $pledge->load(['drive', 'pledgeItems']);

        $itemsList = $pledge->pledgeItems->map(function ($item) {
            return "• {$item->item_name}: {$item->quantity} {$item->unit}";
        })->implode("\n");

        $message = "Your pledge (Ref: {$pledge->reference_number}) for \"{$pledge->drive->name}\" has been verified. Thank you for your contribution!\n\nItems verified:\n{$itemsList}";

        if ($pledge->drive->address) {
            $message .= "\n\nDrop-off location: {$pledge->drive->address}";
        }

        $this->createNotification(
            $pledge->user,
            Notification::TYPE_PLEDGE_VERIFIED,
            'Pledge Verified',
            $message,
            ['pledge_id' => $pledge->id, 'reference_number' => $pledge->reference_number],
            true // Send email
        );
    }

    public function sendPledgeExpiryWarning(Pledge $pledge): void
    {
        $this->createNotification(
            $pledge->user,
            Notification::TYPE_PLEDGE_EXPIRY_WARNING,
            'Pledge Expiring Soon',
            "Your pledge (Ref: {$pledge->reference_number}) will expire in 6 hours if not verified. Please ensure delivery.",
            ['pledge_id' => $pledge->id, 'reference_number' => $pledge->reference_number]
        );
    }

    public function sendPledgeExpired(Pledge $pledge): void
    {
        $this->createNotification(
            $pledge->user,
            Notification::TYPE_PLEDGE_EXPIRED,
            'Pledge Expired',
            "Your pledge (Ref: {$pledge->reference_number}) has expired due to non-verification within 24 hours.",
            ['pledge_id' => $pledge->id, 'reference_number' => $pledge->reference_number]
        );
    }

    public function sendPledgeRejected(Pledge $pledge, string $reason): void
    {
        $pledge->load('drive');

        $message = "Your pledge (Ref: {$pledge->reference_number}) for \"{$pledge->drive->name}\" has been rejected.\n\nReason: {$reason}\n\nIf you believe this was in error, please contact DSWD support or submit a new pledge.";

        $this->createNotification(
            $pledge->user,
            Notification::TYPE_PLEDGE_EXPIRED,
            'Pledge Rejected',
            $message,
            ['pledge_id' => $pledge->id, 'reference_number' => $pledge->reference_number, 'rejection_reason' => $reason],
            true // Send email
        );
    }

    public function sendDonationDistributed(Pledge $pledge): void
    {
        $pledge->load(['drive', 'pledgeItems']);

        $message = "Great news! Your donation (Ref: {$pledge->reference_number}) for \"{$pledge->drive->name}\" has been fully distributed to beneficiaries.";

        if ($pledge->total_families_helped > 0) {
            $message .= " Your contribution helped {$pledge->total_families_helped} " .
                ($pledge->total_families_helped === 1 ? 'family' : 'families') . "!";
        }

        $message .= " Thank you for making a difference!";

        $this->createNotification(
            $pledge->user,
            Notification::TYPE_DONATION_DISTRIBUTED,
            'Donation Distributed',
            $message,
            ['pledge_id' => $pledge->id, 'reference_number' => $pledge->reference_number],
            true // Send email for distribution
        );
    }

    /**
     * Notify user about individual item distribution (SYSTEM ONLY, NO EMAIL)
     * Used for per-item distribution tracking
     */
    public function notifyItemDistributed(User $user, PledgeItem $item): void
    {
        $message = "Your donation of {$item->quantity_distributed} {$item->unit} of {$item->item_name} " .
            "has been distributed";

        if ($item->families_helped > 0) {
            $message .= ", helping {$item->families_helped} " .
                ($item->families_helped === 1 ? 'family' : 'families');
        }

        $message .= ". Thank you!";

        $this->createNotification(
            $user,
            Notification::TYPE_ITEM_DISTRIBUTED,
            'Item Distributed',
            $message,
            [
                'pledge_item_id' => $item->id,
                'item_name' => $item->item_name,
                'quantity_distributed' => $item->quantity_distributed,
                'families_helped' => $item->families_helped,
            ],
            false // NO email for cost reduction
        );
    }

    public function sendImpactFeedback(Pledge $pledge): void
    {
        $this->createNotification(
            $pledge->user,
            Notification::TYPE_IMPACT_FEEDBACK,
            'Impact Update',
            "We've added impact details to your pledge (Ref: {$pledge->reference_number}). See how your donation helped!",
            ['pledge_id' => $pledge->id, 'reference_number' => $pledge->reference_number]
        );
    }

    public function sendNewDriveAvailable(\App\Models\Drive $drive): void
    {
        $users = User::whereIn('role', [User::ROLE_DONOR, User::ROLE_NGO])
            ->where('verification_status', User::STATUS_VERIFIED)
            ->get();

        foreach ($users as $user) {
            $this->createNotification(
                $user,
                Notification::TYPE_NEW_DRIVE,
                'New Drive Available',
                "A new donation drive '{$drive->name}' is now accepting pledges. Help make a difference!",
                ['drive_id' => $drive->id],
                false // Don't email for new drives
            );
        }
    }

    public function sendNgoVerified(User $user): void
    {
        $this->createNotification(
            $user,
            Notification::TYPE_NGO_VERIFIED,
            'Account Verified',
            "Congratulations! Your NGO account has been verified. You now have full access to all features.",
            []
        );
    }

    public function sendNgoRejected(User $user): void
    {
        $reason = $user->rejection_reason ?? 'No reason provided';

        $this->createNotification(
            $user,
            Notification::TYPE_NGO_REJECTED,
            'Verification Rejected',
            "Your NGO verification was rejected. Reason: {$reason}. You may update your details and resubmit for verification, or contact support for assistance.",
            ['rejection_reason' => $reason],
            true // Send email for rejection
        );
    }

    /**
     * Notify donors when an NGO pledges items to a drive they're supporting
     */
    public function sendNgoPledgeAddedToDonors(Pledge $ngoPledge): void
    {
        $drive = $ngoPledge->drive;
        $ngo = $ngoPledge->user;

        // Get unique donors who have pledged to this drive
        $donorPledges = Pledge::where('drive_id', $drive->id)
            ->whereHas('user', fn($q) => $q->where('role', User::ROLE_DONOR))
            ->with('user')
            ->get()
            ->unique('user_id');

        foreach ($donorPledges as $donorPledge) {
            $this->createNotification(
                $donorPledge->user,
                Notification::TYPE_NGO_PLEDGE_ADDED,
                'NGO Support Added to Drive',
                "{$ngo->organization_name} has pledged items to \"{$drive->name}\" - a drive you're supporting!",
                [
                    'drive_id' => $drive->id,
                    'ngo_id' => $ngo->id,
                    'ngo_name' => $ngo->organization_name,
                    'pledge_id' => $donorPledge->id,
                ],
                false // Don't send email for cost reduction
            );
        }
    }
}
