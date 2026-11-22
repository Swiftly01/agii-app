{{-- Header shortcut to the user's listing conversations --}}
<a href="{{ route('listing-chat.inbox') }}" class="bg-light p-2 mx-1 position-relative" title="Messages" aria-label="Messages">
    <i class="bi bi-chat-dots fs-5"></i>
    @if ($unread)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ $unread > 99 ? '99+' : $unread }}
        </span>
    @endif
</a>