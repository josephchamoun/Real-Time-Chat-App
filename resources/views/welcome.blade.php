<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ChatConnect - Stay Connected</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        /* Base styles */
        *, ::after, ::before {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: #333;
        }
        
        /* Header styles */
        .header {
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #3b82f6;
            display: flex;
            align-items: center;
        }
        
        .logo svg {
            margin-right: 0.5rem;
        }
        
        .nav-links {
            display: flex;
            gap: 1rem;
        }
        
        .btn {
            padding: 0.5rem 1.25rem;
            border-radius: 0.375rem;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
        }
        
        .btn-outline {
            border: 2px solid #3b82f6;
            color: #3b82f6;
            background: transparent;
        }
        
        .btn-outline:hover {
            background-color: rgba(59, 130, 246, 0.1);
        }
        
        .btn-primary {
            background-color: #3b82f6;
            color: white;
            border: 2px solid #3b82f6;
        }
        
        .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        
        /* Main content */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            text-align: center;
        }
        
        .hero {
            max-width: 800px;
            margin-bottom: 3rem;
        }
        
        .hero h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .hero p {
            font-size: 1.125rem;
            color: #4b5563;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            width: 100%;
            max-width: 1200px;
            margin-bottom: 3rem;
        }
        
        .feature-card {
            background-color: white;
            border-radius: 0.75rem;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            background-color: rgba(59, 130, 246, 0.1);
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        
        .feature-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: #1f2937;
        }
        
        .feature-card p {
            color: #6b7280;
            line-height: 1.6;
        }
        
        .cta {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        /* Footer */
        .footer {
            background-color: #1f2937;
            color: #e5e7eb;
            padding: 2rem;
            text-align: center;
        }
        
        .footer p {
            font-size: 0.875rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }
            
            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            ChatConnect
        </div>
        <div class="nav-links">
            <a href="/login" class="btn btn-outline">Log in</a>
            <a href="/register" class="btn btn-primary">Register</a>
        </div>
    </header>
    
    <main class="main">
        <section class="hero">
            <h1>Connect with friends & family instantly</h1>
            <p>Experience seamless communication with ChatConnect. Send messages, share photos, and stay connected with the people who matter most to you, all in one secure platform.</p>
            <div class="cta">
                <a href="/register" class="btn btn-primary">Get Started</a>
                <a href="/login" class="btn btn-outline">Sign In</a>
            </div>
        </section>
        
        <section class="features">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                    </svg>
                </div>
                <h3>Real-time Messaging</h3>
                <p>Send and receive messages instantly with our lightning-fast, reliable messaging system.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <h3>End-to-End Encryption</h3>
                <p>Your privacy matters. All messages are securely encrypted to keep your conversations private.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                    </svg>
                </div>
                <h3>Media Sharing</h3>
                <p>Share photos, videos, and documents easily with friends and family in any conversation.</p>
            </div>
        </section>
    </main>
    
    <footer class="footer">
        <p>© 2025 ChatConnect. All rights reserved.</p>
    </footer>
</body>
</html>