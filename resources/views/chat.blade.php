<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat | Chat App</title>
    <style>
        :root {
            --primary-color: #4F46E5;
            --primary-hover: #4338CA;
            --bg-color: #F9FAFB;
            --text-color: #1F2937;
            --border-color: #E5E7EB;
            --sender-bubble: #EEF2FF;
            --sender-text: #4338CA;
            --receiver-bubble: #F3F4F6;
            --receiver-text: #1F2937;
            --time-text: #9CA3AF;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .chat-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            max-width: 1000px;
            margin: 0 auto;
            width: 100%;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        .chat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            background-color: white;
            border-bottom: 1px solid var(--border-color);
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .back-button {
            margin-right: 1rem;
            color: var(--text-color);
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-color);
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
            margin-right: 1rem;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .user-status {
            font-size: 0.85rem;
            color: #6B7280;
            display: flex;
            align-items: center;
        }
        
        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 0.5rem;
            background-color: #10B981;
        }
        
        .actions {
            display: flex;
            gap: 1rem;
        }
        
        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #6B7280;
            font-size: 1.25rem;
            transition: color 0.2s ease;
        }
        
        .action-btn:hover {
            color: var(--primary-color);
        }
        
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            background-color: #F9FAFB;
        }
        
        .message-wrapper {
            display: flex;
            flex-direction: column;
            max-width: 80%;
        }
        
        .message-wrapper.sender {
            align-self: flex-end;
        }
        
        .message-wrapper.receiver {
            align-self: flex-start;
        }
        
        .message {
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            font-size: 0.95rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            word-break: break-word;
            position: relative;
        }
        
        .message.sender {
            background-color: var(--sender-bubble);
            color: var(--sender-text);
            border-bottom-right-radius: 0.25rem;
        }
        
        .message.receiver {
            background-color: var(--receiver-bubble);
            color: var(--receiver-text);
            border-bottom-left-radius: 0.25rem;
        }
        
        .message-time {
            font-size: 0.75rem;
            color: var(--time-text);
            margin-top: 0.25rem;
            align-self: flex-end;
        }
        
        .sender .message-time {
            margin-right: 0.5rem;
        }
        
        .receiver .message-time {
            margin-left: 0.5rem;
        }
        
        .date-divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: #6B7280;
            font-size: 0.85rem;
        }
        
        .date-divider::before, 
        .date-divider::after {
            content: "";
            flex: 1;
            border-top: 1px solid var(--border-color);
        }
        
        .date-divider::before {
            margin-right: 0.75rem;
        }
        
        .date-divider::after {
            margin-left: 0.75rem;
        }
        
        .chat-input-container {
            padding: 1.25rem;
            background-color: white;
            border-top: 1px solid var(--border-color);
        }
        
        .chat-input-form {
            display: flex;
            gap: 0.75rem;
        }
        
        .chat-input {
            flex: 1;
            border: 1px solid var(--border-color);
            border-radius: 9999px;
            padding: 0.75rem 1.25rem;
            font-size: 0.95rem;
            transition: border-color 0.2s ease;
        }
        
        .chat-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
        }
        
        .send-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 9999px;
            width: 45px;
            height: 45px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        
        .send-btn:hover {
            background-color: var(--primary-hover);
        }
        
        .send-icon {
            width: 20px;
            height: 20px;
        }
        
        .typing-indicator {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            color: #6B7280;
            font-style: italic;
            display: none;
        }
        
        .loading-indicator {
            text-align: center;
            padding: 2rem;
            color: #6B7280;
            font-size: 0.95rem;
        }
        
        /* Responsive adjustments */
        @media (max-width: 640px) {
            .chat-header {
                padding: 0.75rem 1rem;
            }
            
            .chat-messages {
                padding: 1rem;
            }
            
            .message-wrapper {
                max-width: 90%;
            }
            
            .chat-input-container {
                padding: 0.75rem;
            }
        }
        
        /* Alert messages */
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
            text-align: center;
            animation: fadeOut 5s forwards;
        }
        
        .alert-error {
            background-color: #FEE2E2;
            color: #EF4444;
            border: 1px solid #FCA5A5;
        }
        
        @keyframes fadeOut {
            0% { opacity: 1; }
            70% { opacity: 1; }
            100% { opacity: 0; }
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <div class="user-info">
                <a href="/mainpage" class="back-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                        <path d="M15 18l-6-6 6-6"></path>
                    </svg>
                </a>
                <div id="user-avatar" class="user-avatar">U</div>
                <div>
                    <div id="receiver-name" class="user-name">Loading...</div>
                    <div class="user-status">
                        <span class="status-indicator"></span>
                        <span id="user-status-text">Online</span>
                    </div>
                </div>
            </div>
            <div class="actions">
                <button class="action-btn" title="Refresh messages" id="refresh-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div id="alerts-container"></div>
        
        <div id="chat-messages" class="chat-messages">
            <div class="loading-indicator">Loading messages...</div>
        </div>
        
        <div id="typing-indicator" class="typing-indicator">User is typing...</div>
        
        <div class="chat-input-container">
            <form id="message-form" class="chat-input-form">
                <input type="text" id="message-input" class="chat-input" placeholder="Type your message..." autocomplete="off">
                <button type="submit" class="send-btn" title="Send message">
                    <svg xmlns="http://www.w3.org/2000/svg" class="send-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <script src="http://localhost:3000/socket.io/socket.io.js"></script>
    <script>
        // Get the user ID from the URL
        const pathParts = window.location.pathname.split('/');
        const otherUserId = parseInt(pathParts[pathParts.length - 1]);
        
        // Configuration
        const authId = localStorage.getItem('user_id') || 1; // The authenticated user ID - ideally from your auth system
        let conversationId = null; // Will be determined after fetching or creating conversation
        
        // Connect to Socket.IO server
        const socket = io("http://localhost:3000");
        
        // DOM elements
        const chatMessages = document.getElementById('chat-messages');
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
        const refreshBtn = document.getElementById('refresh-btn');
        const userAvatar = document.getElementById('user-avatar');
        const userStatusText = document.getElementById('user-status-text');
        const typingIndicator = document.getElementById('typing-indicator');
        const alertsContainer = document.getElementById('alerts-container');
        
        // Initialize the chat
        document.addEventListener('DOMContentLoaded', () => {
            initializeChat();
            
            // Set up event listeners
            refreshBtn.addEventListener('click', fetchMessages);
            
            // Setup typing indicator events
            let typingTimeout;
            messageInput.addEventListener('input', () => {
                if (conversationId) {
                    clearTimeout(typingTimeout);
                    socket.emit('typing', { conversationId, userId: authId });
                    
                    typingTimeout = setTimeout(() => {
                        socket.emit('stopTyping', { conversationId, userId: authId });
                    }, 1000);
                }
            });
        });
        
        async function initializeChat() {
            try {
                // First, fetch or create the conversation
                await getOrCreateConversation();
                
                // Now join the conversation room in Socket.IO if we have a conversation ID
                if (conversationId) {
                    socket.emit('joinConversation', conversationId);
                    console.log('Joined conversation:', conversationId);
                }
                
                // Fetch receiver name and message history
                await fetchReceiverName();
                await fetchMessages();
            } catch (error) {
                console.error('Error initializing chat:', error);
                showAlert('Failed to initialize chat. Please refresh the page.', 'error');
            }
        }
        
        // Get existing conversation or create new one
        async function getOrCreateConversation() {
            try {
                const token = localStorage.getItem('token') || '';
                
                const response = await fetch('http://127.0.0.1:8000/api/conversations', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        user_id: otherUserId
                    })
                });
                
                if (!response.ok) {
                    throw new Error('Failed to get/create conversation');
                }
                
                const data = await response.json();
                conversationId = data.conversation.id;
                
                // Set up socket connection events
                socket.on('connect', () => {
                    console.log('Connected to Socket.IO server');
                    socket.emit('joinConversation', conversationId);
                });
                
                // Set up typing indicator events
                socket.on('userTyping', (data) => {
                    if (data.userId !== authId) {
                        typingIndicator.style.display = 'block';
                    }
                });
                
                socket.on('userStoppedTyping', (data) => {
                    if (data.userId !== authId) {
                        typingIndicator.style.display = 'none';
                    }
                });
                
                return conversationId;
            } catch (error) {
                console.error('Error creating conversation:', error);
                showAlert('Failed to start conversation. Please try again.', 'error');
                return null;
            }
        }
        
        // Create and append a message to the chat
        function appendMessage(content, isSender, timestamp = new Date().toISOString()) {
            // Format the timestamp
            const messageDate = new Date(timestamp);
            const formattedTime = messageDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            // Create message wrapper
            const messageWrapper = document.createElement('div');
            messageWrapper.classList.add('message-wrapper', isSender ? 'sender' : 'receiver');
            
            // Create message bubble
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', isSender ? 'sender' : 'receiver');
            messageDiv.textContent = content;
            
            // Create timestamp element
            const timeDiv = document.createElement('div');
            timeDiv.classList.add('message-time');
            timeDiv.textContent = formattedTime;
            
            // Append elements
            messageWrapper.appendChild(messageDiv);
            messageWrapper.appendChild(timeDiv);
            
            // Add to chat
            chatMessages.appendChild(messageWrapper);
            
            // Scroll to the bottom
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        // Group messages by date for displaying date separators
        function getMessageDateString(timestamp) {
            const messageDate = new Date(timestamp);
            const today = new Date();
            const yesterday = new Date();
            yesterday.setDate(yesterday.getDate() - 1);
            
            if (messageDate.toDateString() === today.toDateString()) {
                return 'Today';
            } else if (messageDate.toDateString() === yesterday.toDateString()) {
                return 'Yesterday';
            } else {
                return messageDate.toLocaleDateString(undefined, { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
            }
        }
        
        // Add a date separator
        function appendDateSeparator(dateString) {
            const dateDivider = document.createElement('div');
            dateDivider.classList.add('date-divider');
            dateDivider.textContent = dateString;
            chatMessages.appendChild(dateDivider);
        }
        
        // Fetch the receiver's name and set avatar
        async function fetchReceiverName() {
            try {
                const token = localStorage.getItem('token') || '';
                const response = await fetch(`http://127.0.0.1:8000/api/users/${otherUserId}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to fetch user details');
                }
                
                const user = await response.json();
                document.getElementById('receiver-name').textContent = user.name;
                
                // Set avatar with user's initials
                const initials = user.name.split(' ')
                    .map(part => part.charAt(0))
                    .join('')
                    .toUpperCase()
                    .substring(0, 2);
                    
                userAvatar.textContent = initials;
                
                // Random online/offline status for demo purposes
                const isOnline = Math.random() > 0.3;
                userStatusText.textContent = isOnline ? 'Online' : 'Offline';
                
                if (!isOnline) {
                    document.querySelector('.status-indicator').style.backgroundColor = '#9CA3AF';
                }
                
                // Set document title
                document.title = `Chat with ${user.name} | Chat App`;
            } catch (error) {
                console.error('Error fetching receiver name:', error);
                document.getElementById('receiver-name').textContent = 'User';
            }
        }
        
        // Fetch message history
        async function fetchMessages() {
            if (!conversationId) return;
            
            try {
                chatMessages.innerHTML = '<div class="loading-indicator">Loading messages...</div>';
                
                const token = localStorage.getItem('token') || '';
                const response = await fetch(`http://127.0.0.1:8000/api/messages/${conversationId}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to fetch messages');
                }
                
                const data = await response.json();
                
                // Clear loading indicator
                chatMessages.innerHTML = '';
                
                if (data.messages.length === 0) {
                    const emptyStateDiv = document.createElement('div');
                    emptyStateDiv.textContent = 'No messages yet. Start the conversation!';
                    emptyStateDiv.style.textAlign = 'center';
                    emptyStateDiv.style.padding = '2rem';
                    emptyStateDiv.style.color = '#6B7280';
                    chatMessages.appendChild(emptyStateDiv);
                    return;
                }
                
                // Group messages by date
                let currentDate = '';
                
                // Display fetched messages
                data.messages.forEach(message => {
                    const messageDate = getMessageDateString(message.created_at);
                    
                    // Add date separator if date changes
                    if (messageDate !== currentDate) {
                        appendDateSeparator(messageDate);
                        currentDate = messageDate;
                    }
                    
                    const isSender = parseInt(message.user_id) === parseInt(authId);
                    appendMessage(message.message, isSender, message.created_at);
                });
                
                // Scroll to bottom after loading messages
                chatMessages.scrollTop = chatMessages.scrollHeight;
            } catch (error) {
                console.error('Error fetching messages:', error);
                chatMessages.innerHTML = '';
                showAlert('Failed to load message history.', 'error');
            }
        }
        
        // Send a new message
        async function sendMessage(content) {
            if (!conversationId || !content.trim()) return;
            
            try {
                const token = localStorage.getItem('token') || '';
                
                const response = await fetch('http://127.0.0.1:8000/api/send-message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        conversation_id: conversationId,
                        message: content
                    })
                });
                
                if (!response.ok) {
                    throw new Error('Failed to send message');
                }
                
                // Message will appear through socket event if successful
                messageInput.value = '';
            } catch (error) {
                console.error('Error sending message:', error);
                showAlert('Failed to send message. Please try again.', 'error');
            }
        }
        
        // Display alert messages
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.classList.add('alert', `alert-${type}`);
            alertDiv.textContent = message;
            
            alertsContainer.appendChild(alertDiv);
            
            // Remove after animation completes
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
        
        // Event listeners
        messageForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const content = messageInput.value.trim();
            if (content) {
                sendMessage(content);
            }
        });
        
        // Socket event listeners for real-time messages
        socket.on('newMessage', data => {
            console.log('Received message:', data);
            
            // Only process messages for our conversation
            if (parseInt(data.conversation_id) !== parseInt(conversationId)) {
                return;
            }
            
            // Get current date for comparison
            const lastDateDivider = chatMessages.querySelector('.date-divider:last-of-type');
            const currentDateString = getMessageDateString(data.created_at || new Date());
            
            // Add a new date divider if needed
            if (!lastDateDivider || lastDateDivider.textContent !== currentDateString) {
                appendDateSeparator(currentDateString);
            }
            
            // Determine if it's a message from the current user
            const isSender = parseInt(data.user_id) === parseInt(authId);
            
            // Display the message with appropriate styling
            appendMessage(data.message, isSender, data.created_at);
        });
        
        // Socket connection error handling
        socket.on('connect_error', (error) => {
            console.error('Socket connection error:', error);
            showAlert('Chat connection failed. Please refresh the page.', 'error');
        });
    </script>
</body>
</html>