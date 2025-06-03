<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with {{ $receiver->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4cc9f0;
            --message-bg: #e9ecef;
        }
        
        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .chat-container {
            max-width: 800px;
            margin: 2rem auto;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: white;
        }
        
        .chat-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .chat-header .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--light-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .chat-header .status {
            font-size: 0.8rem;
            opacity: 0.9;
        }
        
        #chat-box {
            height: 500px;
            padding: 1.5rem;
            overflow-y: auto;
            background-color: #fafcff;
        }
        
        .message {
            max-width: 70%;
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            position: relative;
            word-wrap: break-word;
        }
        
        .sent {
            background-color: var(--primary-color);
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 0.25rem;
        }
        
        .received {
            background-color: var(--message-bg);
            margin-right: auto;
            border-bottom-left-radius: 0.25rem;
        }
        
        #typing-indicator {
            padding: 0 1.5rem;
            font-size: 0.9rem;
            color: #6c757d;
            height: 20px;
        }
        
        .message-form {
            padding: 1rem;
            background-color: white;
            border-top: 1px solid #e9ecef;
        }
        
        .message-input-group {
            border-radius: 2rem;
            overflow: hidden;
        }
        
        #message-input {
            border: none;
            background-color: #f8f9fa;
            padding: 0.75rem 1.25rem;
        }
        
        #message-input:focus {
            box-shadow: none;
            background-color: #f1f3f5;
        }
        
        .send-btn {
            border: none;
            background-color: var(--primary-color);
            padding: 0 1.5rem;
            transition: all 0.2s;
        }
        
        .send-btn:hover {
            background-color: var(--secondary-color);
        }
        
        .time-stamp {
            font-size: 0.7rem;
            opacity: 0.7;
            display: block;
            margin-top: 0.3rem;
        }
        
        /* Custom scrollbar */
        #chat-box::-webkit-scrollbar {
            width: 6px;
        }
        
        #chat-box::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        #chat-box::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }
        
        #chat-box::-webkit-scrollbar-thumb:hover {
            background: #aaa;
        }
    </style>
    @vite(['resources/js/app.js'])
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <div class="avatar">{{ substr($receiver->name, 0, 1) }}</div>
            <div>
                <h5 class="mb-0">{{ $receiver->name }}</h5>
                <div class="status">
                    <span id="user-status" class="{{ $receiver->isOnline() ? 'text-success' : 'text-light' }}">
                        {{ $receiver->isOnline() ? 'Online' : 'Offline' }}
                    </span>
                    <span id="typing-indicator-text" style="display:none"> is typing...</span>
                </div>
            </div>
        </div>
        
        <div id="chat-box">
            @foreach ($messages as $message)
                <div class="message {{ $message->sender_id == auth()->id() ? 'sent' : 'received' }}">
                    {{ $message->message }}
                    <span class="time-stamp">
                        {{ $message->created_at->format('h:i A') }}
                    </span>
                </div>
            @endforeach
        </div>
        
        <div id="typing-indicator" style="display: none;">
            <i class="fas fa-circle-notch fa-spin me-2"></i>
            <span>{{ $receiver->name }} is typing...</span>
        </div>
        
        <form id="message-form" class="message-form">
            @csrf
            <div class="input-group message-input-group">
                <input type="text" id="message-input" class="form-control" placeholder="Type a message..." autocomplete="off">
                <button type="submit" class="btn send-btn text-white">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function (){
            let receiverId = {{ $receiver->id }};
            let senderId = {{ auth()->id() }};
            let chatBox = document.getElementById('chat-box');
            let messageForm = document.getElementById('message-form');
            let messageInput = document.getElementById('message-input');
            let typingIndicator = document.getElementById('typing-indicator');
            let userStatus = document.getElementById('user-status');
            let typingIndicatorText = document.getElementById('typing-indicator-text');

            // Set user online
            fetch('/online', 
                { 
                    method: 'POST', 
                    headers: { 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    } 
                }
            );
            
            // Scroll to bottom of chat
            chatBox.scrollTop = chatBox.scrollHeight;

            // subscribe to chat channel
            window.Echo.private('chat.' + senderId)
                .listen('MessageSent', (e) => {
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'message received';
                    messageDiv.innerHTML = `
                        ${e.message.message}
                        <span class="time-stamp">
                            ${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                        </span>
                    `;
                    chatBox.appendChild(messageDiv);
                    chatBox.scrollTop = chatBox.scrollHeight;
                    
                    // Play message sound
                    let audio = new Audio('{{ asset("sounds/message.mp3") }}');
                    audio.play().catch(e => console.log("Audio play failed:", e));
                });

            // subscribe to typing channel
            window.Echo.private('typing.' + receiverId)
                .listen('UserTyping', (e) => {
                    if(e.typerId === receiverId){
                        typingIndicator.style.display = 'block';
                        setTimeout(() => typingIndicator.style.display = 'none', 3000);
                    }
                });

            // subscribe to presence channel for online status
            window.Echo.private('presence.chat')
                .listen('.presence-updated', (e) => {
                    if(e.user.id === receiverId) {
                        userStatus.textContent = e.status === 'online' ? 'Online' : 'Offline';
                        userStatus.className = e.status === 'online' ? 'text-success' : 'text-light';
                    }
                });

            messageForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const message = messageInput.value.trim();
                if (message) {
                    fetch(`/chat/${receiverId}/send`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message })
                    });
                    
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'message sent';
                    messageDiv.innerHTML = `
                        ${message}
                        <span class="time-stamp">
                            ${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                        </span>
                    `;
                    chatBox.appendChild(messageDiv);
                    chatBox.scrollTop = chatBox.scrollHeight;
                    messageInput.value = '';
                }
            });

            let typingTimeOut;
            messageInput.addEventListener('input', function () {
                clearTimeout(typingTimeOut);
                if(messageInput.value.trim()) {
                    fetch(`/chat/typing`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                }
                typingTimeOut = setTimeout(() => {typingIndicator.style.display = 'none'}, 3000);
            });

            // Set user offline on window close
            window.addEventListener('beforeunload', function () {
                fetch('/offline', { 
                    method: 'POST', 
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    keepalive: true 
                });
            });

            // Focus input field on load
            messageInput.focus();
        });
    </script>
</body>
</html>