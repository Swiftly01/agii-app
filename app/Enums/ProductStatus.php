<?php

namespace App\Enums;

/**
 * Mirrors the `products.status` column (active|inactive|pending). Centralised
 * here so controllers/services stop scattering the raw strings around.
 *
 * There is no separate "rejected" DB value — a rejected product is Inactive
 * with `rejection_reason` filled in. Use Product::isRejected() to tell a
 * plain deactivation apart from an admin rejection.
 */
enum ProductStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending Review',
            self::Active => 'Live',
            self::Inactive => 'Inactive',
        };
    }
}
