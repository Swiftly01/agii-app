<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Models\Conversation;
use App\Models\Product;
use App\Services\Chat\ConversationMessenger;
use App\Services\Chat\ListingConversationService;
use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ListingChatController extends Controller
{
    public function __construct(
        private readonly ListingConversationService $conversations,
        private readonly ConversationMessenger $messenger,
    ) {}

    // Every listing thread the user is part of, newest activity first
    public function inbox(Request $request): View
    {
        $user = $request->user();

        $threads = $user->conversations()
            ->listing()
            ->has('messages')
            ->with(['subject', 'participants', 'lastMessage'])
            ->withUnreadFor($user)
            ->latest('conversations.updated_at')
            ->paginate(20);

        return view('chat.listing.inbox', compact('threads'));
    }

    // Buyer taps "Chat with seller" on a listing
    public function start(Request $request, Product $product): RedirectResponse
    {
        try {
            $conversation = $this->conversations->startFor($request->user(), $product);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('listing-chat.show', $conversation);
    }

    public function show(Request $request, Conversation $conversation): View
    {
        Gate::authorize('view', $conversation);

        $conversation->load(['subject', 'participants']);
        $this->messenger->markRead($conversation, $request->user());

        return view('chat.listing.show', [
            'conversation' => $conversation,
            'counterpart' => $conversation->counterpartFor($request->user()),
            'messages' => $this->messenger->since($conversation),
        ]);
    }

    public function send(SendMessageRequest $request, Conversation $conversation): JsonResponse|RedirectResponse
    {
        Gate::authorize('send', $conversation);

        $message = $this->messenger->send($conversation, $request->user(), $request->validated('body'));

        return $request->expectsJson()
            ? $this->payload(collect([$message]))
            : redirect()->route('listing-chat.show', $conversation);
    }

    // Polled by the thread page for messages it has not rendered yet
    public function poll(Request $request, Conversation $conversation): JsonResponse
    {
        Gate::authorize('view', $conversation);

        $messages = $this->messenger->since($conversation, (int) $request->query('after', 0));
        $this->messenger->markRead($conversation, $request->user());

        return $this->payload($messages);
    }

    // Server-rendered bubbles keep one template for first load, send and poll
    private function payload(Collection $messages): JsonResponse
    {
        return response()->json([
            'html' => $messages->map(fn ($message) => view('chat.listing.partials.message', compact('message'))->render())->implode(''),
            'last_id' => $messages->last()?->id,
        ]);
    }
}
