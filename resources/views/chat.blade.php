<!DOCTYPE html>
<html lang="en">
<head>
<script src="/js/socket.io.js"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App</title>
    <style>
        :root {
            --primary-color: #4F46E5;
            --primary-hover: #4338CA;
            --bg-color: #F9FAFB;
            --text-color: #1F2937;
            --border-color: #E5E7EB;
            --sender-bubble: #DCFCE7; /* Light green */
            --sender-text: #065F46; /* Dark green */
            --receiver-bubble: #F3F4F6; /* Light gray/white */
            --receiver-text: #1F2937;
            --time-text: #9CA3AF;
            --sender-name-color: #4338CA;
            --receiver-name-color: #1F2937;
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
            margin-bottom: 1rem;
            padding: 0 1rem;
        }

        .message-wrapper.sender {
            align-items: flex-end;
        }

        .message-wrapper.receiver {
            align-items: flex-start;
        }

        .message-sender-name {
            font-size: 0.8rem;
            font-weight: bold;
            margin-bottom: 0.2rem;
        }

        .message-sender-name.sender {
            color: var(--sender-name-color);
            text-align: right;
        }

        .message-sender-name.receiver {
            color: var(--receiver-name-color);
            text-align: left;
        }

        .message {
            max-width: 70%;
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            font-size: 0.95rem;
            word-break: break-word;
            position: relative;
        }

        .message.sender {
            background-color: var(--sender-bubble);
            color: var(--sender-text);
            border-bottom-right-radius: 0;
        }

        .message.receiver {
            background-color: var(--receiver-bubble);
            color: var(--receiver-text);
            border-bottom-left-radius: 0;
        }

        .message-time {
            font-size: 0.75rem;
            color: var(--time-text);
            margin-top: 0.25rem;
            text-align: right;
        }

        .sender .message-time {
            margin-right: 0;
        }

        .receiver .message-time {
            margin-left: 0;
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
            align-items: center;
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

        /* Emoji Picker Styles */
        .emoji-container {
            position: relative;
            margin-right: 0.5rem;
        }

        .emoji-toggle {
            background: none;
            border: none;
            color: #6B7280;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }

        .emoji-toggle:hover {
            background-color: #F3F4F6;
            color: var(--primary-color);
        }

        .emoji-picker {
            position: absolute;
            bottom: 50px;
            left: 0;
            width: 320px;
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 0.75rem;
            z-index: 100;
            display: none;
            flex-direction: column;
        }

        .emoji-categories {
            display: flex;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.5rem;
            margin-bottom: 0.5rem;
            overflow-x: auto;
        }

        .emoji-category {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            font-size: 1.25rem;
            border-radius: 0.25rem;
            margin-right: 0.25rem;
        }

        .emoji-category.active {
            background-color: #F3F4F6;
        }

        .emoji-search {
            margin-bottom: 0.75rem;
        }

        .emoji-search input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }

        .emoji-container-scroll {
            max-height: 200px;
            overflow-y: auto;
        }

        .emoji-list {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 0.5rem;
        }

        .emoji-item {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 0.25rem;
            transition: background-color 0.2s;
        }

        .emoji-item:hover {
            background-color: #F3F4F6;
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
            
            .emoji-picker {
                width: 280px;
                left: -50px;
            }
            
            .emoji-list {
                grid-template-columns: repeat(6, 1fr);
            }
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
                <div class="emoji-container">
                    <button type="button" id="emoji-toggle" class="emoji-toggle" title="Add emoji">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                            <line x1="9" y1="9" x2="9.01" y2="9"></line>
                            <line x1="15" y1="9" x2="15.01" y2="9"></line>
                        </svg>
                    </button>
                    <div id="emoji-picker" class="emoji-picker">
                        <div class="emoji-categories">
                            <button data-category="smileys" class="emoji-category active">😊</button>
                            <button data-category="people" class="emoji-category">👋</button>
                            <button data-category="animals" class="emoji-category">🐶</button>
                            <button data-category="food" class="emoji-category">🍕</button>
                            <button data-category="activities" class="emoji-category">⚽</button>
                            <button data-category="travel" class="emoji-category">🚗</button>
                            <button data-category="objects" class="emoji-category">💡</button>
                            <button data-category="symbols" class="emoji-category">❤️</button>
                        </div>
                        <div class="emoji-search">
                            <input type="text" id="emoji-search" placeholder="Search emojis...">
                        </div>
                        <div class="emoji-container-scroll">
                            <div id="emoji-list" class="emoji-list"></div>
                        </div>
                    </div>
                </div>
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

    

    <script>
        const baseUrl = "{{ config('ngrok.url') }}";
        // Get the user ID from the URL
        const pathParts = window.location.pathname.split('/');
        const otherUserId = parseInt(pathParts[pathParts.length - 1]);

        // Configuration
        const authId = localStorage.getItem('user_id'); // The authenticated user ID - ideally from your auth system
        let conversationId = null; // Will be determined after fetching or creating conversation
        let otherUserName = 'User'; // Store the name of the other user

        // Connect to Socket.IO server
        //
        //
        //
        //
        //
        const socket = io("https://d9bc-94-72-152-229.ngrok-free.app", {
            transports: ['websocket'],
            auth: {
                token: localStorage.getItem('token') || ''
            }
        });

        // DOM elements
        const chatMessages = document.getElementById('chat-messages');
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
        const refreshBtn = document.getElementById('refresh-btn');
        const userAvatar = document.getElementById('user-avatar');
        const userStatusText = document.getElementById('user-status-text');
        const typingIndicator = document.getElementById('typing-indicator');
        const alertsContainer = document.getElementById('alerts-container');
        const receiverNameElement = document.getElementById('receiver-name');

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

                const response = await fetch(`${baseUrl}/api/conversations`, {
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
        function appendMessage(content, isSender, timestamp = new Date().toISOString(), senderName = '') {
            // Format the timestamp
            const messageDate = new Date(timestamp);
            const formattedTime = messageDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            // Create message wrapper
            const messageWrapper = document.createElement('div');
            messageWrapper.classList.add('message-wrapper', isSender ? 'sender' : 'receiver');

            // Create sender name element
            const senderNameDiv = document.createElement('div');
            senderNameDiv.classList.add('message-sender-name', isSender ? 'sender' : 'receiver');
            senderNameDiv.textContent = senderName;

            // Create message bubble
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', isSender ? 'sender' : 'receiver');
            messageDiv.textContent = content;

            // Create timestamp element
            const timeDiv = document.createElement('div');
            timeDiv.classList.add('message-time');
            timeDiv.textContent = formattedTime;

            // Append elements
            messageWrapper.appendChild(senderNameDiv);
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
                const response = await fetch(`${baseUrl}/api/users/${otherUserId}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch user details');
                }

                const user = await response.json();
                otherUserName = user.name;
                receiverNameElement.textContent = otherUserName;

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
                receiverNameElement.textContent = 'User';
            }
        }

        // Fetch message history
        async function fetchMessages() {
            if (!conversationId) return;

            try {
                chatMessages.innerHTML = '<div class="loading-indicator">Loading messages...</div>';

                const token = localStorage.getItem('token') || '';
                const response = await fetch(`${baseUrl}/api/messages/${conversationId}`, {
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
                    const senderName = isSender ? 'You' : otherUserName;
                    appendMessage(message.message, isSender, message.created_at, senderName);
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

                const response = await fetch(`${baseUrl}/api/send-message`, {
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
            const senderName = isSender ? 'You' : otherUserName;

            // Display the message with appropriate styling
            appendMessage(data.message, isSender, data.created_at, senderName);

            // Hide typing indicator
            typingIndicator.style.display = 'none';
        });

        // Socket connection error handling
        socket.on('connect_error', (error) => {
            console.error('Socket connection error:', error);
            showAlert('Chat connection failed. Please refresh the page.', 'error');
        });

        // Emoji Picker Implementation
        // Emoji data organized by categories
        const emojiData = {
            smileys: [
                { emoji: "😀", description: "Grinning Face" },
                { emoji: "😃", description: "Grinning Face with Big Eyes" },
                { emoji: "😄", description: "Grinning Face with Smiling Eyes" },
                { emoji: "😁", description: "Beaming Face with Smiling Eyes" },
                { emoji: "😆", description: "Grinning Squinting Face" },
                { emoji: "😅", description: "Grinning Face with Sweat" },
                { emoji: "🤣", description: "Rolling on the Floor Laughing" },
                { emoji: "😂", description: "Face with Tears of Joy" },
                { emoji: "🙂", description: "Slightly Smiling Face" },
                { emoji: "🙃", description: "Upside-Down Face" },
                { emoji: "😉", description: "Winking Face" },
                { emoji: "😊", description: "Smiling Face with Smiling Eyes" },
                { emoji: "😇", description: "Smiling Face with Halo" },
                { emoji: "😍", description: "Smiling Face with Heart-Eyes" },
                { emoji: "🥰", description: "Smiling Face with Hearts" },
                { emoji: "😘", description: "Face Blowing a Kiss" },
                { emoji: "😗", description: "Kissing Face" },
                { emoji: "☺️", description: "Smiling Face" },
                { emoji: "😚", description: "Kissing Face with Closed Eyes" },
                { emoji: "😙", description: "Kissing Face with Smiling Eyes" }
            ],
            people: [
                { emoji: "👋", description: "Waving Hand" },
                { emoji: "🤚", description: "Raised Back of Hand" },
                { emoji: "✋", description: "Raised Hand" },
                { emoji: "👌", description: "OK Hand" },
                { emoji: "👍", description: "Thumbs Up" },
                { emoji: "👎", description: "Thumbs Down" },
                { emoji: "👏", description: "Clapping Hands" },
                { emoji: "🙌", description: "Raising Hands" },
                { emoji: "🤝", description: "Handshake" },
                { emoji: "🤲", description: "Palms Up Together" },
                { emoji: "🤞", description: "Crossed Fingers" },
                { emoji: "✌️", description: "Victory Hand" },
                { emoji: "🤟", description: "Love-You Gesture" },
                { emoji: "👨", description: "Man" },
                { emoji: "👩", description: "Woman" },
                { emoji: "👦", description: "Boy" },
                { emoji: "👧", description: "Girl" }
            ],
            animals: [
                { emoji: "🐶", description: "Dog Face" },
                { emoji: "🐱", description: "Cat Face" },
                { emoji: "🐭", description: "Mouse Face" },
                { emoji: "🐹", description: "Hamster Face" },
                { emoji: "🐰", description: "Rabbit Face" },
                { emoji: "🦊", description: "Fox Face" },
                { emoji: "🐻", description: "Bear Face" },
                { emoji: "🐼", description: "Panda Face" },
                { emoji: "🐨", description: "Koala Face" },
                { emoji: "🐯", description: "Tiger Face" },
                { emoji: "🦁", description: "Lion Face" },
                { emoji: "🐮", description: "Cow Face" }
            ],
            food: [
                { emoji: "🍏", description: "Green Apple" },
                { emoji: "🍎", description: "Red Apple" },
                { emoji: "🍐", description: "Pear" },
                { emoji: "🍊", description: "Tangerine" },
                { emoji: "🍋", description: "Lemon" },
                { emoji: "🍌", description: "Banana" },
                { emoji: "🍉", description: "Watermelon" },
                { emoji: "🍇", description: "Grapes" },
                { emoji: "🍓", description: "Strawberry" },
                { emoji: "🍕", description: "Pizza" },
                { emoji: "🍔", description: "Hamburger" },
                { emoji: "🍟", description: "French Fries" },
                { emoji: "🍖", description: "Meat on Bone" }
            ],
            activities: [
                { emoji: "⚽", description: "Soccer Ball" },
                { emoji: "🏀", description: "Basketball" },
                { emoji: "🏈", description: "American Football" },
                { emoji: "⚾", description: "Baseball" },
                { emoji: "🎾", description: "Tennis" },
                { emoji: "🏐", description: "Volleyball" },
                { emoji: "🎱", description: "Pool 8 Ball" },
                { emoji: "🏓", description: "Ping Pong" }
            ],
            travel: [
                { emoji: "🚗", description: "Car" },
                { emoji: "🚕", description: "Taxi" },
                { emoji: "🚙", description: "Sport Utility Vehicle" },
                { emoji: "🚌", description: "Bus" },
                { emoji: "🚎", description: "Trolleybus" },
                { emoji: "🏎️", description: "Racing Car" },
                { emoji: "🚓", description: "Police Car" },
                { emoji: "🚑", description: "Ambulance" },
                { emoji: "🚒", description: "Fire Engine" },
                { emoji: "✈️", description: "Airplane" },
                { emoji: "🚀", description: "Rocket" }
            ],
            objects: [
                { emoji: "⌚", description: "Watch" },
                { emoji: "📱", description: "Mobile Phone" },
                { emoji: "💻", description: "Laptop" },
                { emoji: "🖥️", description: "Desktop Computer" },
                { emoji: "🖨️", description: "Printer" },
                { emoji: "💡", description: "Light Bulb" },
                { emoji: "💰", description: "Money Bag" },
                { emoji: "💎", description: "Gem Stone" },
                { emoji: "🔑", description: "Key" },
                { emoji: "🔒", description: "Locked" }
            ],
            symbols: [
                { emoji: "❤️", description: "Red Heart" },
                { emoji: "🧡", description: "Orange Heart" },
                { emoji: "💛", description: "Yellow Heart" },
                { emoji: "💚", description: "Green Heart" },
                { emoji: "💙", description: "Blue Heart" },
                { emoji: "💜", description: "Purple Heart" },
                { emoji: "🖤", description: "Black Heart" },
                { emoji: "💕", description: "Two Hearts" },
                { emoji: "💯", description: "Hundred Points" },
                { emoji: "✅", description: "Check Mark Button" },
                { emoji: "❌", description: "Cross Mark" }
            ]
        };

        // DOM Elements for emoji picker
        const emojiToggle = document.getElementById('emoji-toggle');
        const emojiPicker = document.getElementById('emoji-picker');
        const emojiList = document.getElementById('emoji-list');
        const emojiSearch = document.getElementById('emoji-search');
        const categoryButtons = document.querySelectorAll('.emoji-category');

        // Current active category
        let activeCategory = 'smileys';

        // Initialize emoji picker
        function initEmojiPicker() {
            // Show/hide emoji picker on toggle button click
            emojiToggle.addEventListener('click', toggleEmojiPicker);

            // Close emoji picker when clicking outside
            document.addEventListener('click', (e) => {
                if (!emojiPicker.contains(e.target) && e.target !== emojiToggle) {
                    emojiPicker.style.display = 'none';
                }
            });

            // Initialize with first category
            renderEmojiCategory(activeCategory);

            // Set up category switching
            categoryButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const category = button.getAttribute('data-category');
                    activeCategory = category;
                    
                    // Update active category styling
                    categoryButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    
                    // Render emojis for the selected category
                    renderEmojiCategory(category);
                });
            });

            // Set up emoji search
            emojiSearch.addEventListener('input', searchEmojis);
        }

        // Toggle emoji picker visibility
        function toggleEmojiPicker() {
            const isVisible = emojiPicker.style.display === 'flex';
            emojiPicker.style.display = isVisible ? 'none' : 'flex';
        }

        // Render emojis for a specific category
        function renderEmojiCategory(category) {
            emojiList.innerHTML = '';
            
            emojiData[category].forEach(item => {
                const emojiItem = document.createElement('div');
                emojiItem.classList.add('emoji-item');
                emojiItem.setAttribute('title', item.description);
                emojiItem.textContent = item.emoji;
                
                // Add click event to insert emoji into message input
                emojiItem.addEventListener('click', () => {
                    insertEmoji(item.emoji);
                });
                
                emojiList.appendChild(emojiItem);
            });
        }

        // Search emojis across all categories
        function searchEmojis() {
            const searchTerm = emojiSearch.value.toLowerCase();
            
            if (!searchTerm) {
                renderEmojiCategory(activeCategory);
                return;
            }
            
            emojiList.innerHTML = '';
            
            // Search through all emoji categories
            Object.values(emojiData).flat().forEach(item => {
                if (item.description.toLowerCase().includes(searchTerm)) {
                    const emojiItem = document.createElement('div');
                    emojiItem.classList.add('emoji-item');
                    emojiItem.setAttribute('title', item.description);
                    emojiItem.textContent = item.emoji;
                    
                    emojiItem.addEventListener('click', () => {
                        insertEmoji(item.emoji);
                    });
                    
                    emojiList.appendChild(emojiItem);
                }
            });
        }

        // Insert emoji at cursor position in message input
        function insertEmoji(emoji) {
            const cursorPos = messageInput.selectionStart;
            const text = messageInput.value;
            const textBefore = text.substring(0, cursorPos);
            const textAfter = text.substring(cursorPos);
            
            // Update input value with emoji inserted at cursor position
            messageInput.value = textBefore + emoji + textAfter;
            
            // Set cursor position after the inserted emoji
            messageInput.selectionStart = cursorPos + emoji.length;
            messageInput.selectionEnd = cursorPos + emoji.length;
            
            // Focus on the input
            messageInput.focus();
            
            // Hide the emoji picker after selection
            emojiPicker.style.display = 'none';
        }

        // Initialize emoji picker when DOM is loaded
        initEmojiPicker();
    </script>
</body>
</html>