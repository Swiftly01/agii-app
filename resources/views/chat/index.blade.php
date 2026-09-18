@extends('layout.marketer')

@section('title', 'Chat - Agii Marketer')
@section('page-title', 'Chat')

@section('content')
<style>
    .chat-container {
        height: calc(100vh - 200px);
        background: #f8f9fa;
    }
    
    .conversations-list {
        max-height: calc(100vh - 300px);
        overflow-y: auto;
    }
    
    .messages-container {
        height: calc(100vh - 300px);
        overflow-y: auto;
        background: #f8f9fa;
        padding: 20px;
        scroll-behavior: smooth;
    }
    
    .chat-sidebar {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        height: 100%;
    }
    
    .chat-main {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .conversation-item {
        padding: 12px 15px;
        border-radius: 8px;
        margin: 5px 10px;
        transition: all 0.3s ease;
        cursor: pointer;
        border-left: 3px solid transparent;
        display: flex;
        align-items: center;
    }
    
    .conversation-item:hover {
        background: #f1f5f9;
    }
    
    .conversation-item.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-left: 3px solid #4f46e5;
    }
    
    .conversation-item.active .conversation-time,
    .conversation-item.active .conversation-last-message,
    .conversation-item.active .conversation-name {
        color: rgba(255,255,255,0.9);
    }
    
    .message {
        padding: 12px 16px;
        border-radius: 15px;
        margin-bottom: 15px;
        max-width: 70%;
        position: relative;
        animation: fadeIn 0.3s ease;
        word-wrap: break-word;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .own-message {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        margin-left: auto;
        border-bottom-right-radius: 5px;
    }
    
    .other-message {
        background: white;
        color: #333;
        margin-right: auto;
        border-bottom-left-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .message-sender {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 5px;
        color: #4f46e5;
    }
    
    .other-message .message-sender {
        color: #4f46e5;
    }
    
    .own-message .message-sender {
        color: rgba(255,255,255,0.9);
    }
    
    .message-time {
        font-size: 11px;
        opacity: 0.7;
        text-align: right;
        margin-top: 5px;
    }
    
    .chat-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 10px 10px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .chat-input-container {
        border-top: 1px solid #e9ecef;
        padding: 20px;
        background: white;
        border-radius: 0 0 10px 10px;
    }
    
    .chat-tabs {
        border-bottom: 1px solid #e9ecef;
        padding: 0 20px;
        display: flex;
    }
    
    .chat-tab {
        padding: 12px 25px;
        border: none;
        background: none;
        font-weight: 500;
        color: #6c757d;
        position: relative;
        transition: all 0.3s ease;
        flex: 1;
        text-align: center;
    }
    
    .chat-tab.active {
        color: #667eea;
    }
    
    .chat-tab.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 3px 3px 0 0;
    }
    
    .chat-tab:hover {
        color: #667eea;
    }
    
    .conversation-badge {
        background: #667eea;
        color: white;
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 10px;
        margin-left: 5px;
    }
    
    .conversation-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 18px;
        margin-right: 12px;
        flex-shrink: 0;
        position: relative;
    }
    
    .group-avatar {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .online-dot {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 10px;
        height: 10px;
        background: #10b981;
        border: 2px solid white;
        border-radius: 50%;
    }
    
    .conversation-info {
        flex: 1;
        min-width: 0;
    }
    
    .conversation-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: flex;
        align-items: center;
    }
    
    .conversation-last-message {
        font-size: 13px;
        color: #6c757d;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .conversation-time {
        font-size: 12px;
        color: #adb5bd;
    }
    
    .no-conversations {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }
    
    .no-conversations i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }
    
    .chat-welcome {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        text-align: center;
        padding: 40px;
    }
    
    .chat-welcome-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin: 0 auto 20px;
    }
    
    .unread-badge {
        background: #ef4444;
        color: white;
        font-size: 11px;
        min-width: 18px;
        height: 18px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 6px;
        margin-left: auto;
    }
    
    .refresh-button {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .refresh-button:hover {
        background: rgba(255,255,255,0.3);
    }
    
    .refresh-button.spinning {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .typing-indicator {
        display: inline-flex;
        align-items: center;
        color: #667eea;
        font-style: italic;
    }
    
    .typing-dots {
        display: inline-flex;
        margin-left: 5px;
    }
    
    .typing-dots span {
        width: 6px;
        height: 6px;
        background: #667eea;
        border-radius: 50%;
        margin: 0 2px;
        animation: typing 1.4s infinite ease-in-out;
    }
    
    .typing-dots span:nth-child(1) { animation-delay: -0.32s; }
    .typing-dots span:nth-child(2) { animation-delay: -0.16s; }
    
    @keyframes typing {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }
    
    .message-actions {
        position: absolute;
        top: 5px;
        right: 10px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .message:hover .message-actions {
        opacity: 1;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }
    
    .empty-state-icon {
        font-size: 60px;
        color: #dee2e6;
        margin-bottom: 20px;
    }
</style>

<div class="row chat-container g-3">
    <!-- Left sidebar - Conversations -->
    <div class="col-md-4">
        <div class="chat-sidebar h-100 d-flex flex-column">
            <!-- Header -->
            <div class="p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Messages</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newGroupModal">
                        <i class="fas fa-plus me-1"></i> New Group
                    </button>
                </div>
            </div>
            
            <!-- Tabs -->
            <div class="chat-tabs d-flex">
                <button class="chat-tab active" data-tab="all" onclick="filterConversations('all')">
                    <i class="fas fa-inbox me-1"></i> All
                </button>
                <button class="chat-tab" data-tab="private" onclick="filterConversations('private')">
                    <i class="fas fa-user me-1"></i> Private
                </button>
                <button class="chat-tab" data-tab="group" onclick="filterConversations('group')">
                    <i class="fas fa-users me-1"></i> Group
                </button>
            </div>
            
            <!-- Conversations List -->
            <div class="conversations-list flex-grow-1" id="conversationsList">
                @if($conversations->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h5>No conversations yet</h5>
                        <p class="text-muted">Start a new conversation by selecting a user</p>
                    </div>
                @else
                    @foreach($conversations as $conversation)
                        <div class="conversation-item {{ $conversation->id == ($currentConversation->id ?? 0) ? 'active' : '' }}" 
                             onclick="loadConversation({{ $conversation->id }})">
                            <div class="conversation-avatar {{ $conversation->type == 'group' ? 'group-avatar' : '' }}">
                                @if($conversation->type == 'group')
                                    <i class="fas fa-users"></i>
                                @else
                                    {{ substr(($conversation->otherParticipant->first_name ?? 'U'), 0, 1) }}
                                @endif
                            </div>
                            <div class="conversation-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="conversation-name">
                                        @if($conversation->type == 'group')
                                            {{ $conversation->first_name }}
                                            <span class="conversation-badge ms-2">Group</span>
                                        @else
                                            {{ $conversation->otherParticipant->first_name ?? 'Unknown' }}
                                        @endif
                                    </div>
                                    <div class="conversation-time">
                                   
                                        {{ optional($conversation->messages->first())->created_at?->diffForHumans() ?? '' }}

                                    </div>
                                </div>
                                <div class="conversation-last-message">
                                    @if($conversation->messages->isNotEmpty())
                                        {{ Str::limit($conversation->messages->first()->body, 30) }}
                                    @else
                                        No messages yet
                                    @endif
                                </div>
                            </div>
                            @if($conversation->unread_count > 0)
                                <span class="unread-badge">{{ $conversation->unread_count }}</span>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
            
            <!-- User list for new conversation -->
            <div class="border-top p-3">
                <h6 class="mb-3">Start New Chat</h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($users as $user)
                        <button class="btn btn-outline-primary btn-sm" 
                                onclick="startPrivateChat({{ $user->id }})">
                            <i class="fas fa-user me-1"></i>
                            {{ $user->first_name }}
                            <small class="text-muted ms-1">({{ $user->user_type }})</small>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right side - Chat area -->
    <div class="col-md-8">
        <div class="chat-main h-100 d-flex flex-column" id="chatMain">
            @if(isset($currentConversation))
                <!-- Chat Header -->
                <div class="chat-header">
                    <div>
                        <h6 class="mb-0">
                            @if($currentConversation->type == 'group')
                                <i class="fas fa-users me-2"></i>
                                {{ $currentConversation->first_name }}
                            @else
                                <i class="fas fa-user me-2"></i>
                                {{ $currentConversation->otherParticipant->first_name ?? 'Unknown' }}
                            @endif
                        </h6>
                        <small class="opacity-75">
                            @if($currentConversation->type == 'group')
                                {{ $currentConversation->participants->count() }} members
                            @else
                                {{ $currentConversation->otherParticipant->user_type ?? '' }}
                            @endif
                        </small>
                    </div>
                    <button class="refresh-button" onclick="refreshMessages()" title="Refresh messages">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                
                <!-- Messages Container -->
                <div class="messages-container flex-grow-1" id="messagesContainer">
                    @foreach($currentConversation->messages as $message)
                        <div class="message {{ $message->user_id == Auth::id() ? 'own-message' : 'other-message' }}" 
                             id="message-{{ $message->id }}">
                            <div class="message-sender">
                                {{ $message->user->first_name }}
                                @if($currentConversation->type == 'group' && $message->user_id != Auth::id())
                                    <small class="opacity-75">({{ $message->user->user_type }})</small>
                                @endif
                            </div>
                            <div class="message-body">{{ $message->body }}</div>
                            <div class="message-time">
                                {{ $message->created_at->format('h:i A') }}
                                @if($message->user_id == Auth::id())
                                    <span class="ms-2">
                                        @if($message->created_at->format('Y-m-d') == now()->format('Y-m-d'))
                                            Today
                                        @else
                                            {{ $message->created_at->format('M d') }}
                                        @endif
                                    </span>
                                @endif
                            </div>
                            @if($message->user_id == Auth::id())
                                <div class="message-actions">
                                    <button class="btn btn-sm btn-danger btn-icon" 
                                            onclick="deleteMessage({{ $message->id }})"
                                            title="Delete message">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                
                <!-- Message Input -->
                <div class="chat-input-container">
                    <form id="messageForm" onsubmit="sendMessage(event)">
                        @csrf
                        <input type="hidden" name="conversation_id" value="{{ $currentConversation->id }}">
                        <div class="input-group">
                            <input type="text" 
                                   name="message" 
                                   class="form-control" 
                                   placeholder="Type your message..."
                                   id="messageInput"
                                   required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Send
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <!-- Welcome Screen -->
                <div class="chat-welcome">
                    <div>
                        <div class="chat-welcome-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h4 class="mb-3">Welcome to Chat</h4>
                        <p class="text-muted mb-4">Select a conversation from the sidebar or start a new one</p>
                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-outline-primary" onclick="showAllUsers()">
                                <i class="fas fa-user-plus me-2"></i> New Private Chat
                            </button>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newGroupModal">
                                <i class="fas fa-users me-2"></i> Create Group
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- New Group Modal -->
<div class="modal fade" id="newGroupModal">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('chat.group.create') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label">Group Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter group name" required>
                    </div>
                    <div>
                        <label class="form-label">Select Members</label>
                        <div class="row">
                            @foreach($users as $user)
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="members[]" value="{{ $user->id }}" id="user{{ $user->id }}">
                                        <label class="form-check-label d-flex align-items-center" for="user{{ $user->id }}">
                                            <div class="conversation-avatar me-3" style="width: 35px; height: 35px;">
                                                {{ substr($user->first_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div>{{ $user->first_name }}</div>
                                                <small class="text-muted">{{ $user->user_type }}</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create Group</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentConversationId = {{ $currentConversation->id ?? 0 }};
    let lastMessageId = 0;
    let pollingInterval;
    let isPolling = false;

    // Initialize
    $(document).ready(function() {
        if (currentConversationId > 0) {
            // Get last message ID for polling
            const messages = $('#messagesContainer .message');
            if (messages.length > 0) {
                lastMessageId = parseInt(messages.last().attr('id').replace('message-', ''));
            }
            
            // Start polling
            startPolling();
            
            // Scroll to bottom
            scrollToBottom();
        }
    });

    // Filter conversations by type
    function filterConversations(type) {
        $('.chat-tab').removeClass('active');
        $(`[data-tab="${type}"]`).addClass('active');
        
        if (type === 'all') {
            $('.conversation-item').show();
        } else {
            $('.conversation-item').each(function() {
                const isGroup = $(this).find('.conversation-badge').length > 0;
                if ((type === 'group' && isGroup) || (type === 'private' && !isGroup)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    }

    // Load conversation
    function loadConversation(conversationId) {
        // Show loading
        $('#chatMain').html(`
            <div class="chat-welcome">
                <div class="text-center">
                    <div class="spinner-border text-primary mb-3"></div>
                    <p>Loading conversation...</p>
                </div>
            </div>
        `);
        
        // Stop previous polling
        stopPolling();
        
        // Load conversation
        window.location.href = `/chat/${conversationId}`;
    }

    // Start private chat
    function startPrivateChat(userId) {
        window.location.href = `/chat/start/${userId}`;
    }

    // Send message via AJAX
    function sendMessage(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const messageInput = form.querySelector('input[name="message"]');
        
        // Disable input and show loading
        messageInput.disabled = true;
        form.querySelector('button[type="submit"]').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        
        fetch('/chat/' + currentConversationId + '/message', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add message to UI
                addMessageToUI(data.message);
                
                // Clear input
                messageInput.value = '';
                
                // Update last message in sidebar
                updateLastMessageInSidebar(data.message.body);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to send message. Please try again.');
        })
        .finally(() => {
            // Re-enable input
            messageInput.disabled = false;
            form.querySelector('button[type="submit"]').innerHTML = '<i class="fas fa-paper-plane"></i> Send';
        });
    }

    // Poll for new messages
    function startPolling() {
        if (currentConversationId === 0 || isPolling) return;
        
        isPolling = true;
        pollingInterval = setInterval(fetchNewMessages, 3000); // Poll every 3 seconds
    }

    function stopPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            isPolling = false;
        }
    }

    // Fetch new messages
    function fetchNewMessages() {
        fetch(`/chat/${currentConversationId}/new-messages?last_message_id=${lastMessageId}`)
            .then(response => response.json())
            .then(messages => {
                if (messages.length > 0) {
                    messages.forEach(message => {
                        addMessageToUI(message);
                        lastMessageId = Math.max(lastMessageId, message.id);
                    });
                }
            })
            .catch(error => console.error('Error fetching messages:', error));
    }

    // Refresh messages manually
    function refreshMessages() {
        const refreshBtn = $('.refresh-button');
        refreshBtn.addClass('spinning');
        
        fetchNewMessages();
        
        setTimeout(() => {
            refreshBtn.removeClass('spinning');
        }, 1000);
    }

    // Add message to UI
    function addMessageToUI(message) {
        const isOwnMessage = message.user_id == {{ Auth::id() }};
        const messageTime = new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        const messageHtml = `
            <div class="message ${isOwnMessage ? 'own-message' : 'other-message'}" id="message-${message.id}">
                <div class="message-sender">
                    ${message.user.name}
                    ${message.conversation.type === 'group' && !isOwnMessage ? 
                        `<small class="opacity-75">(${message.user.user_type})</small>` : ''}
                </div>
                <div class="message-body">${message.body}</div>
                <div class="message-time">
                    ${messageTime}
                    ${isOwnMessage ? `
                        <span class="ms-2">
                            ${new Date(message.created_at).toDateString() === new Date().toDateString() ? 
                                'Today' : new Date(message.created_at).toLocaleDateString('en-US', {month: 'short', day: 'numeric'})}
                        </span>
                    ` : ''}
                </div>
                ${isOwnMessage ? `
                    <div class="message-actions">
                        <button class="btn btn-sm btn-danger btn-icon" 
                                onclick="deleteMessage(${message.id})"
                                title="Delete message">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                ` : ''}
            </div>
        `;
        
        $('#messagesContainer').append(messageHtml);
        scrollToBottom();
    }

    // Delete message
    function deleteMessage(messageId) {
        if (!confirm('Are you sure you want to delete this message?')) return;
        
        fetch(`/chat/message/${messageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $(`#message-${messageId}`).fadeOut(300, function() {
                    $(this).remove();
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to delete message.');
        });
    }

    // Update last message in sidebar
    function updateLastMessageInSidebar(messageBody) {
        const conversationItem = $(`.conversation-item.active`);
        conversationItem.find('.conversation-last-message').text(messageBody);
        conversationItem.find('.conversation-time').text('Just now');
        
        // Move to top of list
        const list = $('#conversationsList');
        conversationItem.prependTo(list);
    }

    // Scroll to bottom
    function scrollToBottom() {
        const container = document.getElementById('messagesContainer');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }

    // Show all users modal
    function showAllUsers() {
        // You can implement a modal to show all users for new chat
        alert('Select a user from the sidebar to start a chat');
    }
</script>
@endpush