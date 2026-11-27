<?php

namespace App\Enums;

// Mirrors the hero_spotlight_payments.status column
enum SpotlightPaymentStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Failed = 'failed';
}
