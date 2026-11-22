@extends('layout.layout')
@section('title', '- Chat')
@section('content')
    <div class="container py-4" style="max-width: 800px;">
        <a href="{{ route('listing-chat.inbox') }}" class="small text-decoration-none"><i class="bi bi-arrow-left"></i> All messages</a>

        @php($subject = $conversation->subject)

        {{-- Listing the conversation is about --}}
        <div class="card shadow-sm my-3">
            <div class="card-body d-flex gap-3 align-items-center">
                <img src="{{ $subject?->chatThumbnail() ?? asset(\App\Models\Product::VIDEO_PLACEHOLDER) }}" alt="" class="rounded" style="width: 64px; height: 64px; object-fit: cover;">
                <div class="flex-grow-1">
                    <div class="small text-muted">Chat with {{ $counterpart?->first_name ?? 'Unknown' }}</div>
                    @if ($subject)
                        <a href="{{ $subject->chatUrl() }}" class="fw-bold text-decoration-none">{{ $subject->chatTitle() }}</a>
                    @else
                        <span class="fw-bold">Listing no longer available</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div id="messages" class="card-body" style="height: 55vh; overflow-y: auto;">
                @forelse ($messages as $message)
                    @include('chat.listing.partials.message', ['message' => $message])
                @empty
                    <p id="empty-hint" class="text-center text-muted my-4">Say hello to start the conversation.</p>
                @endforelse
            </div>

            <div class="card-footer bg-white">
                @if ($messages->isEmpty())
                    {{-- Quick openers for a brand new thread --}}
                    <div id="openers" class="d-flex gap-2 flex-wrap mb-2">
                        @foreach (['Is this still available?', 'What is your best price?', 'Where can we meet?'] as $opener)
                            <button type="button" class="btn btn-sm btn-outline-secondary opener">{{ $opener }}</button>
                        @endforeach
                    </div>
                @endif

                <form id="chat-form" method="POST" action="{{ route('listing-chat.send', $conversation) }}" class="d-flex gap-2">
                    @csrf
                    <textarea name="body" rows="1" maxlength="1000" class="form-control" placeholder="Type a message" required></textarea>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i></button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const box = document.getElementById('messages');
            const form = document.getElementById('chat-form');
            const input = form.querySelector('textarea');
            const pollUrl = @json(route('listing-chat.poll', $conversation));
            let lastId = {{ $messages->last()?->id ?? 0 }};

            // Appends rendered bubbles and keeps the view pinned to the newest one
            const append = ({ html, last_id }) => {
                if (!html) return;
                document.getElementById('empty-hint')?.remove();
                document.getElementById('openers')?.remove();
                box.insertAdjacentHTML('beforeend', html);
                lastId = last_id ?? lastId;
                box.scrollTop = box.scrollHeight;
            };

            box.scrollTop = box.scrollHeight;

            // Fills the input from a quick opener
            document.querySelectorAll('.opener').forEach(btn => btn.addEventListener('click', () => {
                input.value = btn.textContent;
                input.focus();
            }));

            // Sends without a page reload
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const body = input.value.trim();
                if (!body) return;

                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': form.querySelector('[name=_token]').value },
                    body: JSON.stringify({ body }),
                });

                if (response.ok) {
                    input.value = '';
                    append(await response.json());
                }
            });

            // Checks for new messages every 5 seconds while the tab is visible
            setInterval(async () => {
                if (document.hidden) return;
                const response = await fetch(`${pollUrl}?after=${lastId}`, { headers: { 'Accept': 'application/json' } });
                if (response.ok) append(await response.json());
            }, 5000);
        })();
    </script>
@endpush
