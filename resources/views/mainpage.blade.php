<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realtime Chat - User Selection</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h2 {
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        #users-list {
            list-style-type: none;
            padding: 0;
        }
        #users-list li {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #eee;
            transition: all 0.3s ease;
        }
        #users-list li:hover {
            background-color: #f9f9f9;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        #users-list a {
            display: block;
            color: #2c3e50;
            text-decoration: none;
            font-weight: bold;
        }
        .user-status {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .online {
            background-color: #2ecc71;
        }
        .offline {
            background-color: #95a5a6;
        }
        .loader {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
        }
        .error-message {
            color: #e74c3c;
            padding: 10px;
            border: 1px solid #e74c3c;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h2>Select a user to chat with</h2>
    <div id="loader" class="loader">Loading users...</div>
    <div id="error-container"></div>
    <ul id="users-list"></ul>

    <script>
    // Fetch all users from the API and display them in the list
    async function fetchUsers() {
        try {
            const loader = document.getElementById('loader');
            const errorContainer = document.getElementById('error-container');
            const usersList = document.getElementById('users-list');
            
            // Clear any previous errors
            errorContainer.innerHTML = '';
            
            // Make API request
            const response = await fetch('http://127.0.0.1:8000/api/users', {
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
                throw new Error(`Failed to fetch users: ${response.status}`);
            }

            const data = await response.json();
            const users = data.users;

            if (Array.isArray(users)) {
                if (users.length === 0) {
                    usersList.innerHTML = '<p>No users available to chat with.</p>';
                    return;
                }
                
                // Filter out the current user if needed
                const currentUserId = 1; // Replace with actual authentication data
                const otherUsers = users.filter(user => user.id !== currentUserId);
                
                // Display the filtered list
                otherUsers.forEach(user => {
                    const li = document.createElement('li');
                    const link = document.createElement('a');
                    
                    // Create status indicator (you can implement actual online status later)
                    const statusIndicator = document.createElement('span');
                    statusIndicator.classList.add('user-status', Math.random() > 0.5 ? 'online' : 'offline');
                    
                    link.appendChild(statusIndicator);
                    link.appendChild(document.createTextNode(user.name));
                    link.href = `/chat/${user.id}`;
                    
                    li.appendChild(link);
                    usersList.appendChild(li);
                });
            } else {
                throw new Error('Unexpected API response format');
            }
        } catch (error) {
            console.error('Error fetching users:', error);
            
            // Display error message to user
            const errorContainer = document.getElementById('error-container');
            const errorMessage = document.createElement('div');
            errorMessage.classList.add('error-message');
            errorMessage.textContent = 'Failed to load users. Please refresh the page or try again later.';
            errorContainer.appendChild(errorMessage);
            
            // Hide loader
            document.getElementById('loader').style.display = 'none';
        }
    }

    // Check if the user is logged in before fetching users
    function checkAuth() {
        const token = localStorage.getItem('token');
        
        if (!token) {
            // Redirect to login page if not logged in
            window.location.href = '/login';
        } else {
            // Fetch users if logged in
            fetchUsers();
        }
    }

    // Call the function to check auth and fetch users
    checkAuth();
    </script>
</body>
</html>