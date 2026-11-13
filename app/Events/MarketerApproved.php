<?php

namespace App\Events;

use App\Models\Marketer;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MarketerApproved
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Marketer $marketer,
        public readonly ?User $approvedBy = null,
    ) {}
}
