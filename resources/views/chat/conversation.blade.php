@extends('layout.marketer')

@section('content')
<div class="row chat-container">
    <!-- Left sidebar -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('chat.index') }}" class="btn btn-sm btn-secondary">Back</a>
            </div>
            <div class="card-body conversations-list">
                @foreach(Auth::user()->conversations as $conv)
                    <a href="{{ route('chat.conversation', $conv->id) }}" 
                       class="d-block p-3 border-bottom text-decoration-none text-dark 
                              {{ $conv->id == $conversation->id ? 'bg-light' : '' }}">
                        <strong>
                            @if($conv->type == 'group')
                                {{ $conv->name }}
                                <small class="text-muted">(Group)</small>
                            @else
                                {{ $conv->otherParticipant->first_name ?? 'Unknown' }}
                            @endif
                        </strong>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Chat area -->
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header">
                <h5>
                    @if($conversation->type == 'group')
                        {{ $conversation->name }}
                        <small class="text-muted">(Group Chat)</small>
                    @else
                        {{ $conversation->otherParticipant->first_name ?? 'Unknown' }}
                    @endif
                </h5>
            </div>
            
            <!-- Messages -->
            <div class="card-body messages-container">
                @foreach($conversation->messages as $message)
                    <div class="message {{ $message->user_id == Auth::id() ? 'own-message' : 'other-message' }}">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $message->user->first_name }}</strong>
                            @if($message->user_id == Auth::id())
                                <form action="{{ route('chat.message.delete', $message->id) }}" method="POST" 
                                      onsubmit="return confirm('Delete this message?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">×</button>
                                </form>
                            @endif
                        </div>
                        <p>{{ $message->body }}</p>
                        <small class="text-muted">
                            {{ $message->created_at->format('M d, h:i A') }}
                        </small>
                    </div>
                @endforeach
            </div>
            
            <!-- Message input -->
            <div class="card-footer">
                <form action="{{ route('chat.message.send', $conversation->id) }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="message" class="form-control" 
                               placeholder="Type your message..." required>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Scroll to bottom on load
        scrollToBottom();
        
        // Start polling for new messages
        @if($conversation->messages->isNotEmpty())
            let lastMessageId = {{ $conversation->messages->last()->id }};
            pollNewMessages({{ $conversation->id }}, lastMessageId);
        @endif
    });
</script>
@endpush
@endsection