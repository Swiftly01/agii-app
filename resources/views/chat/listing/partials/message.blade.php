@php($mine = $message->user_id === auth()->id())

<div class="d-flex mb-2 {{ $mine ? 'justify-content-end' : 'justify-content-start' }}" data-message-id="{{ $message->id }}">
    <div class="px-3 py-2 rounded-3 {{ $mine ? 'bg-primary text-white' : 'bg-light' }}" style="max-width: 75%;">
        <div style="white-space: pre-wrap; word-break: break-word;">{{ $message->body }}</div>
        <div class="small mt-1 {{ $mine ? 'text-white-50' : 'text-muted' }}">{{ $message->created_at->format('M d, h:i A') }}</div>
    </div>
</div>
