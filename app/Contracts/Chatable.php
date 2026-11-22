<?php

namespace App\Contracts;

use App\Models\User;

// Anything a buyer can open a seller conversation about (products, services, future listing types)
interface Chatable
{
    // The user who receives the buyer's messages
    public function chatSeller(): User;

    // Short title shown at the top of the thread
    public function chatTitle(): string;

    // Public page of the listing
    public function chatUrl(): string;

    // Thumbnail shown in the thread header and inbox
    public function chatThumbnail(): string;
}
