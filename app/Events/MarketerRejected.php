<?php

namespace App\Events;

use App\Models\Marketer;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MarketerRejected
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Marketer $marketer,
        public readonly string $reason,
        public readonly ?User $rejectedBy = null,
    ) {}
}
