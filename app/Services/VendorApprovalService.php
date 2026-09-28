<?php

namespace App\Services;

use App\Enums\VendorStatus;
use App\Events\VendorApproved;
use App\Events\VendorRejected;
use App\Models\User;
use Illuminate\Validation\ValidationException;

/**
 * Single place that owns the vendor-approval state machine. Controllers
 * (admin UI today, an API or CLI tomorrow) should go through this rather
 * than writing to `vendor_status` directly, so the rules below can't be
 * bypassed or duplicated.
 */
class VendorApprovalService
{
    public function approve(User $vendor, User $admin): User
    {
        $this->assertIsVendor($vendor);

        $vendor->forceFill([
            'vendor_status' => VendorStatus::Approved->value,
            'vendor_approved_at' => now(),
            'vendor_approved_by' => $admin->id,
            'vendor_rejection_reason' => null,
        ])->save();

        VendorApproved::dispatch($vendor->fresh(), $admin);

        return $vendor->fresh();
    }

    public function reject(User $vendor, User $admin, string $reason): User
    {
        $this->assertIsVendor($vendor);

        $vendor->forceFill([
            'vendor_status' => VendorStatus::Rejected->value,
            'vendor_approved_at' => null,
            'vendor_approved_by' => $admin->id,
            'vendor_rejection_reason' => $reason,
        ])->save();

        VendorRejected::dispatch($vendor->fresh(), $reason, $admin);

        return $vendor->fresh();
    }

    /**
     * Send a vendor back to pending — e.g. after they update their
     * business details following a rejection, so admin reviews the
     * changes rather than the vendor staying rejected forever.
     */
    public function resetToPending(User $vendor): User
    {
        $this->assertIsVendor($vendor);

        $vendor->forceFill([
            'vendor_status' => VendorStatus::Pending->value,
            'vendor_approved_at' => null,
            'vendor_approved_by' => null,
            'vendor_rejection_reason' => null,
        ])->save();

        return $vendor->fresh();
    }

    protected function assertIsVendor(User $vendor): void
    {
        if ($vendor->user_type !== 'vendor') {
            throw ValidationException::withMessages([
                'user_type' => 'Only vendor accounts can be approved or rejected.',
            ]);
        }
    }
}
