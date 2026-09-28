<?php

namespace App\Enums;

/**
 * Mirrors the `users.vendor_status` column. Kept as a lightweight backed
 * enum (not a DB enum change) so every place that checks vendor approval
 * state reads `VendorStatus::Approved` instead of the magic string
 * 'approved' — one place to look when the state machine needs to grow
 * (e.g. adding a 'suspended' status later).
 */
enum VendorStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending Review',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending => 'bg-warning text-dark',
            self::Approved => 'bg-success',
            self::Rejected => 'bg-danger',
        };
    }
}
