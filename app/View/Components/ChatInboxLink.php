<?php

namespace App\View\Components;

use App\Services\Chat\ConversationMessenger;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ChatInboxLink extends Component
{
    public int $unread = 0;

    public function __construct(ConversationMessenger $messenger)
    {
        $this->unread = auth()->check() ? $messenger->unreadCountFor(auth()->user()) : 0;
    }

    public function render(): View
    {
        return view('components.chat-inbox-link');
    }
}