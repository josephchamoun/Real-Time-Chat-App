<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realtime Chat - Users</title>
    <style>
        :root {
            --primary-color: #4F46E5;
            --primary-hover: #4338CA;
            --bg-color: #F9FAFB;
            --text-color: #1F2937;
            --border-color: #E5E7EB;
            --error-color: #EF4444;
            --success-color: #10B981;
            --online-color: #10B981;
            --offline-color: #9CA3AF;
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
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .header h1 {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-color);
        }
        
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        
        .btn:hover {
            background-color: var(--primary-hover);
        }
        
        .btn-outline {
            background-color: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }
        
        .btn-outline:hover {
            background-color: rgba(79, 70, 229, 0.1);
        }
        
        .users-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .user-card {
            background-color: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }
        
        .user-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: var(--primary-color);
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 1rem;
            color: white;
            font-weight: bold;
            font-size: 1.25rem;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 1rem;
        }
        
        .user-status {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            color: #6B7280;
        }
        
        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 0.5rem;
        }
        
        .online .status-indicator {
            background-color: var(--online-color);
        }
        
        .offline .status-indicator {
            background-color: var(--offline-color);
        }
        
        .chat-action {
            margin-top: 1rem;
        }
        
        .chat-btn {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease;
            text-decoration: none;
            width: 100%;
            justify-content: center;
        }
        
        .chat-btn:hover {
            background-color: var(--primary-hover);
        }
        
        .loader {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }
        
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(79, 70, 229, 0.1);
            border-left-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6B7280;
        }
        
        .empty-state p {
            margin-bottom: 1.5rem;
        }
        
        .error-container {
            background-color: #FEE2E2;
            color: var(--error-color);
            border: 1px solid #FCA5A5;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: none;
        }
        
        .logout-section {
            margin-top: 3rem;
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Chat Community</h1>
            <div>
                <button id="refreshBtn" class="btn btn-outline">Refresh</button>
                <button id="logoutBtn" class="btn">Logout</button>
            </div>
        </div>
        
        <div id="error-container" class="error-container"></div>
        
        <div id="loader" class="loader">
            <div class="loading-spinner"></div>
        </div>
        
        <div id="users-container" class="users-grid"></div>
        
        <div id="empty-state" class="empty-state" style="display: none;">
            <p>No users are available to chat with at the moment.</p>
            <button id="refreshEmptyBtn" class="btn">Refresh Users</button>
        </div>
    </div>

    <script>
        const baseUrl = "{{ config('ngrok.url') }}";
        // Check authentication on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkAuth();
        });
        
        // Function to check authentication
        function checkAuth() {
            const token = localStorage.getItem('token');
            
            if (!token) {
                // Redirect to login page if no token found
                window.location.href = '/login';
            } else {
                // Fetch users if authenticated
                fetchUsers();
            }
        }
       
        
        // Event listeners for buttons
        document.getElementById('refreshBtn').addEventListener('click', fetchUsers);
        document.getElementById('logoutBtn').addEventListener('click', logout);
        
        // Logout function
        function logout() {
            localStorage.removeItem('token');
            window.location.href = '/login';
        }
        
        // Function to fetch users
        async function fetchUsers() {
            const loader = document.getElementById('loader');
            const errorContainer = document.getElementById('error-container');
            const usersContainer = document.getElementById('users-container');
            const emptyState = document.getElementById('empty-state');
            
            // Show loader and hide error/empty state
            loader.style.display = 'flex';
            errorContainer.style.display = 'none';
            usersContainer.style.display = 'none';
            emptyState.style.display = 'none';
            
            try {
                // Make API request
                const response = await fetch(`${baseUrl}/api/users`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token') || ''}`
                    }
                });
                
                // Hide loader
                loader.style.display = 'none';
                
                if (!response.ok) {
                    // Handle unauthorized response
                    if (response.status === 401) {
                        localStorage.removeItem('token');
                        window.location.href = '/login';
                        return;
                    }
                    
                    throw new Error(`Failed to fetch users: ${response.status}`);
                }
                
                const data = await response.json();
                
                // Clear previous content
                usersContainer.innerHTML = '';
                
                if (Array.isArray(data.users) && data.users.length > 0) {
                    // Filter out current user if needed (assuming we know current user ID)
                
                    const otherUsers = data.users.filter(user => user.id !== localStorage.getItem('user_id'));
                    
                    if (otherUsers.length === 0) {
                        // Show empty state if no other users
                        emptyState.style.display = 'block';
                        return;
                    }
                    
                    // Show users container
                    usersContainer.style.display = 'grid';
                    const authId = localStorage.getItem('user_id');
                    // Generate user cards
                    otherUsers.forEach(user => {

                        if(user.id == authId) return; // Skip the logged-in user
                        // Determine online status (random for demo)
                        const isOnline = Math.random() > 0.5;
                        const statusClass = isOnline ? 'online' : 'offline';
                        const statusText = isOnline ? 'Online' : 'Offline';
                        
                        // Generate initials for avatar
                        const initials = user.name.split(' ')
                            .map(part => part.charAt(0))
                            .join('')
                            .toUpperCase()
                            .substring(0, 2);
                        
                        // Create user card
                        const userCard = document.createElement('div');
                        userCard.className = 'user-card';
                        userCard.innerHTML = `
                            <div class="user-info">
                                <div class="user-avatar">${initials}</div>
                                <div>
                                    <div class="user-name">${user.name}</div>
                                    <div class="user-status ${statusClass}">
                                        <span class="status-indicator"></span>
                                        ${statusText}
                                    </div>
                                </div>
                            </div>
                            <div class="chat-action">
                                <a href="/chat/${user.id}" class="chat-btn">Start Chatting</a>
                            </div>
                        `;
                        
                        usersContainer.appendChild(userCard);
                    });
                } else {
                    // Show empty state if no users
                    emptyState.style.display = 'block';
                }
            } catch (error) {
                console.error('Error fetching users:', error);
                
                // Display error message
                errorContainer.textContent = 'Failed to load users. Please try again.';
                errorContainer.style.display = 'block';
            }
        }
        
        // Add event listener for empty state refresh button
        document.getElementById('refreshEmptyBtn').addEventListener('click', fetchUsers);
    </script>
</body>
</html>