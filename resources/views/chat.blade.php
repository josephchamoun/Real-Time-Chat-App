<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <style>
        #chat-box {
            height: 300px;
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        .message {
            margin: 5px 0;
            padding: 8px 12px;
            border-radius: 10px;
            max-width: 70%;
            word-wrap: break-word;
        }
        .message.sender {
            background-color: #e1f5fe;
            margin-left: auto;
        }
        .message.receiver {
            background-color: #f5f5f5;
            margin-right: auto;
        }
        #message-form {
            display: flex;
            gap: 10px;
        }
        #message-input {
            flex-grow: 1;
            padding: 8px;
        }
    </style>
</head>
<body>
    <h2>Chat with <span id="receiver-name">...</span></h2>
    <div id="chat-box"></div>
    <form id="message-form">
        <input type="text" id="message-input" placeholder="Type a message...">
        <button type="submit" id="send-btn">Send</button>
    </form>

    <script src="http://localhost:3000/socket.io/socket.io.js"></script>
    <script>
        // Get the user ID from the URL
        // For example, if URL is /chat/5, this will extract "5"
        const pathParts = window.location.pathname.split('/');
        const otherUserId = parseInt(pathParts[pathParts.length - 1]);
        
        // Configuration
        const authId = 1; // The authenticated user ID - ideally this should come from your auth system
        let conversationId = null; // Will be determined after fetching or creating conversation
        
        // Connect to Socket.IO server
        const socket = io("http://localhost:3000");
        
        // DOM elements
        const chatBox = document.getElementById('chat-box');
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');

        // Initialize the chat after setting up conversation
        initializeChat();
        
        async function initializeChat() {
            // First, fetch or create the conversation
            await getOrCreateConversation();
            
            // Now join the conversation room in Socket.IO
            if (conversationId) {
                socket.emit('joinConversation', conversationId);
                console.log('Joined conversation:', conversationId);
            }
            
            // Fetch receiver name and message history
            await fetchReceiverName();
            await fetchMessages();
        }
        
        // Get existing conversation or create new one
        async function getOrCreateConversation() {
    try {
        const token = localStorage.getItem('token') || '';
        
        // Use the full API URL
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
        
        // Now connect socket to this conversation
        socket.on('connect', () => {
            console.log('Connected to Socket.IO server');
            socket.emit('joinConversation', conversationId);
        });
        
        return conversationId;
    } catch (error) {
        console.error('Error creating conversation:', error);
        alert('Failed to start conversation. Please try again.');
        return null;
    }
}

        // Add a message to the chat display
        function appendMessage(content, isSender) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', isSender ? 'sender' : 'receiver');
            messageDiv.textContent = content;
            chatBox.appendChild(messageDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        // Fetch the receiver's name
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
    } catch (error) {
        console.error('Error fetching receiver name:', error);
        document.getElementById('receiver-name').textContent = 'User';
    }
}

        // Fetch message history
        async function fetchMessages() {
    if (!conversationId) return;
    
    try {
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
        
        // Clear existing messages
        chatBox.innerHTML = '';
        
        // Display fetched messages
        data.messages.forEach(message => {
            const isSender = message.user_id === authId;
            appendMessage(message.message, isSender);
        });
    } catch (error) {
        console.error('Error fetching messages:', error);
        appendMessage('Failed to load message history.', false);
    }
}

        // Send a new message
        async function sendMessage(content) {
    if (!conversationId) return;
    
    try {
        const token = localStorage.getItem('token') || '';
        
        // Send to Laravel backend with full URL
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
        alert('Failed to send message. Please try again.');
    }
}

        // Event listeners
        messageForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const content = messageInput.value.trim();
            if (content) {
                appendMessage(content, true);
                sendMessage(content);
            }
        });

        // Listen for real-time messages
        socket.on('newMessage', data => {
            console.log('Received message:', data);
            
            // Only process messages for our conversation
            if (data.conversation_id !== conversationId) {
                return;
            }
            
            // Skip messages sent by the current user
            if (data.user_id === authId) {
                return;
            }
            
            // Display the message
            appendMessage(data.message, false);
        });

        // Socket connection error handling
        socket.on('connect_error', (error) => {
            console.error('Socket connection error:', error);
            alert('Chat connection failed. Please refresh the page.');
        });
    </script>
</body>
</html>