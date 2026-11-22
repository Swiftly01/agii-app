@extends('layout.layout')
@section('title', '- Messages')
@section('content')
    <div class="container py-4" style="max-width: 800px;">
        <h4 class="fw-bold mb-3">Messages</h4>

        <div class="list-group shadow-sm">
            @forelse ($threads as $thread)
                @php($subject = $thread->subject)
                @php($other = $thread->counterpartFor(auth()->user()))
                <a href="{{ route('listing-chat.show', $thread) }}" class="list-group-item list-group-item-action d-flex gap-3 align-items-center">
                    <img src="{{ $subject?->chatThumbnail() ?? asset(\App\Models\Product::VIDEO_PLACEHOLDER) }}" alt="" class="rounded" style="width: 56px; height: 56px; object-fit: cover;">
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="d-flex justify-content-between">
                            <strong class="text-truncate">{{ $other?->first_name ?? 'Unknown' }}</strong>
                            <small class="text-muted">{{ $thread->lastMessage?->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="small text-muted text-truncate">{{ $subject?->chatTitle() ?? 'Listing no longer available' }}</div>
                        <div class="small text-truncate">{{ $thread->lastMessage?->body }}</div>
                    </div>
                    @if ($thread->unread_count)
                        <span class="badge rounded-pill bg-danger">{{ $thread->unread_count }}</span>
                    @endif
                </a>
            @empty
                <div class="list-group-item text-center text-muted py-5">No conversations yet.</div>
            @endforelse
        </div>

        <div class="mt-3">{{ $threads->links() }}</div>
    </div>
@endsection
