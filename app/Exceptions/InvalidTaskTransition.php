<?php

namespace App\Exceptions;

use App\Enums\TaskStatus;
use DomainException;

class InvalidTaskTransition extends DomainException
{
    public static function between(TaskStatus $from, TaskStatus $to): self
    {
        return new self("A {$from->label()} task cannot be moved to {$to->label()}.");
    }
}
