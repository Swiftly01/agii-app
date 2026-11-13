<?php

namespace App\Services\Marketer;

use App\Enums\MarketerStatus;
use App\Events\MarketerApproved;
use App\Events\MarketerRejected;
use App\Models\Marketer;
use App\Models\User;

// Single owner of the marketer approval state machine, controllers must go through it instead of writing `status` directly
class MarketerApprovalService
{
    public function approve(Marketer $marketer, User $admin): Marketer
    {
        // Approving twice would email the marketer twice
        if ($marketer->isApproved()) {
            return $marketer;
        }

        $marketer->update([
            'status' => MarketerStatus::Approved,
            'is_active' => true,
            'approved_at' => now(),
            'approved_by' => $admin->id,
            'rejection_reason' => null,
        ]);

        MarketerApproved::dispatch($marketer->fresh('user'), $admin);

        return $marketer->fresh();
    }

    public function reject(Marketer $marketer, User $admin, string $reason): Marketer
    {
        $marketer->update([
            'status' => MarketerStatus::Rejected,
            'is_active' => false,
            'approved_at' => null,
            'approved_by' => $admin->id,
            'rejection_reason' => $reason,
        ]);

        MarketerRejected::dispatch($marketer->fresh('user'), $reason, $admin);

        return $marketer->fresh();
    }
}
