<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | Chat App</title>
  <style>
    :root {
      --primary-color: #4F46E5;
      --primary-hover: #4338CA;
      --bg-color: #F9FAFB;
      --text-color: #1F2937;
      --border-color: #E5E7EB;
      --error-color: #EF4444;
      --success-color: #10B981;
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
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    
    .container {
      width: 100%;
      max-width: 420px;
      padding: 2rem;
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .header {
      text-align: center;
      margin-bottom: 2rem;
    }
    
    .header h1 {
      font-size: 1.875rem;
      font-weight: 700;
      color: var(--text-color);
      margin-bottom: 0.5rem;
    }
    
    .header p {
      color: #6B7280;
    }
    
    .form-group {
      margin-bottom: 1.5rem;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: #374151;
    }
    
    .form-control {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid var(--border-color);
      border-radius: 0.375rem;
      font-size: 1rem;
      transition: border-color 0.2s ease;
    }
    
    .form-control:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    
    .btn {
      display: block;
      width: 100%;
      padding: 0.75rem 1rem;
      background-color: var(--primary-color);
      color: white;
      border: none;
      border-radius: 0.375rem;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }
    
    .btn:hover {
      background-color: var(--primary-hover);
    }
    
    .text-center {
      text-align: center;
    }
    
    .mt-4 {
      margin-top: 1rem;
    }
    
    .text-sm {
      font-size: 0.875rem;
    }
    
    .alert {
      padding: 0.75rem 1rem;
      border-radius: 0.375rem;
      margin-bottom: 1rem;
      display: none;
    }
    
    .alert-error {
      background-color: #FEE2E2;
      color: var(--error-color);
      border: 1px solid #FCA5A5;
    }
    
    .alert-success {
      background-color: #D1FAE5;
      color: var(--success-color);
      border: 1px solid #A7F3D0;
    }
    
    a {
      color: var(--primary-color);
      text-decoration: none;
    }
    
    a:hover {
      text-decoration: underline;
    }

    .password-requirements {
      font-size: 0.75rem;
      color: #6B7280;
      margin-top: 0.5rem;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Create Account</h1>
      <p>Join our chat community today</p>
    </div>
    
    <div id="alert" class="alert"></div>
    
    <form id="registerForm">
      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name" required>
      </div>
      
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
      </div>
      
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Create a password" required>
        <p class="password-requirements">Password must be at least 8 characters long</p>
      </div>
      
      <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm your password" required>
      </div>
      
      <button type="submit" class="btn">Create Account</button>
    </form>
    
    <p class="text-center mt-4 text-sm">
      Already have an account? <a href="login">Sign in</a>
    </p>
  </div>

  <script>
    document.getElementById('registerForm').addEventListener('submit', async function (e) {
      e.preventDefault();
      
      const alertBox = document.getElementById('alert');
      alertBox.style.display = 'none';
      alertBox.classList.remove('alert-error', 'alert-success');
      
      const name = document.getElementById('name').value;
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      const password_confirmation = document.getElementById('password_confirmation').value;
      
      // Basic validation
      if (password !== password_confirmation) {
        alertBox.textContent = "Passwords do not match.";
        alertBox.classList.add('alert-error');
        alertBox.style.display = 'block';
        return;
      }
      
      if (password.length < 8) {
        alertBox.textContent = "Password must be at least 8 characters long.";
        alertBox.classList.add('alert-error');
        alertBox.style.display = 'block';
        return;
      }
      
      try {
        const response = await fetch('http://localhost:8000/api/register', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({ name, email, password, password_confirmation })
        });

        const result = await response.json();
        
        if (response.ok) {
          // Show success message
          alertBox.textContent = "Registration successful! Redirecting to login...";
          alertBox.classList.add('alert-success');
          alertBox.style.display = 'block';
          
          // Store token if provided
          if (result.token) {
            localStorage.setItem("token", result.token);
            
            // Redirect to main page after a brief delay
            setTimeout(() => {
              window.location.href = '/mainpage';
            }, 1500);
          } else {
            // If no token, redirect to login
            setTimeout(() => {
              window.location.href = '/login';
            }, 1500);
          }
        } else {
          // Handle validation errors
          if (result.errors) {
            const errorMessages = Object.values(result.errors).flat();
            alertBox.textContent = errorMessages.join(' ');
          } else {
            alertBox.textContent = result.message || "Registration failed. Please try again.";
          }
          alertBox.classList.add('alert-error');
          alertBox.style.display = 'block';
        }
      } catch (error) {
        alertBox.textContent = "An error occurred. Please try again later.";
        alertBox.classList.add('alert-error');
        alertBox.style.display = 'block';
        console.error("Registration error:", error);
      }
    });
  </script>
</body>
</html>