<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodLink - Local Food Ordering & Delivery</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        body { background: #f8fafc; color: #1e293b; }
        
        /* Navbar */
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 20px 50px; background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 100; }
        .navbar .logo { font-size: 24px; font-weight: 800; color: #0f172a; }
        .navbar .logo span { color: #ea580c; }
        .navbar .nav-links { display: flex; gap: 20px; align-items: center; }
        .navbar .nav-links a { text-decoration: none; color: #475569; font-weight: 500; font-size: 15px; transition: color 0.2s; }
        .navbar .nav-links a:hover { color: #ea580c; }
        .navbar .btn-login { background: #ea580c; color: #fff !important; padding: 10px 24px; border-radius: 8px; font-weight: 600; transition: background 0.2s; }
        .navbar .btn-login:hover { background: #c2410c; }

        /* Hero Section */
        .hero { display: flex; align-items: center; justify-content: space-between; padding: 80px 50px; background: linear-gradient(135deg, #fff7ed 0%, #f8fafc 100%); flex-wrap: wrap; gap: 40px; }
        .hero-text { flex: 1; min-width: 300px; }
        .hero-text h1 { font-size: 48px; font-weight: 800; line-height: 1.2; color: #0f172a; margin-bottom: 20px; }
        .hero-text h1 span { color: #ea580c; }
        .hero-text p { font-size: 18px; color: #64748b; margin-bottom: 30px; line-height: 1.6; }
        .hero-text .btn-cta { display: inline-block; background: #ea580c; color: #fff; padding: 16px 36px; border-radius: 12px; text-decoration: none; font-size: 18px; font-weight: 600; transition: background 0.2s, transform 0.1s; }
        .hero-text .btn-cta:hover { background: #c2410c; transform: translateY(-2px); }
        .hero-image { flex: 1; min-width: 300px; display: flex; justify-content: center; }
        .hero-image img { max-width: 100%; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }

        /* Features Section */
        .features { padding: 80px 50px; text-align: center; background: #fff; }
        .features h2 { font-size: 36px; font-weight: 700; color: #0f172a; margin-bottom: 10px; }
        .features p.sub { font-size: 16px; color: #64748b; margin-bottom: 50px; }
        .feature-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; max-width: 1200px; margin: 0 auto; }
        .feature-card { padding: 40px 30px; border-radius: 16px; background: #f8fafc; border: 1px solid #e2e8f0; transition: transform 0.2s, box-shadow 0.2s; }
        .feature-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .feature-card .icon { font-size: 40px; margin-bottom: 20px; }
        .feature-card h3 { font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 10px; }
        .feature-card p { font-size: 15px; color: #64748b; line-height: 1.6; }

        /* Roles Section */
        .roles { padding: 80px 50px; background: #f8fafc; text-align: center; }
        .roles h2 { font-size: 36px; font-weight: 700; color: #0f172a; margin-bottom: 50px; }
        .role-grid { display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; max-width: 1000px; margin: 0 auto; }
        .role-box { background: #fff; padding: 40px; border-radius: 16px; width: 250px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 5px solid #ea580c; }
        .role-box .role-icon { font-size: 50px; margin-bottom: 20px; }
        .role-box h3 { font-size: 22px; color: #0f172a; margin-bottom: 10px; }
        .role-box p { font-size: 14px; color: #64748b; }

        /* Footer */
        .footer { padding: 30px; text-align: center; background: #1e293b; color: #94a3b8; font-size: 14px; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">Food<span>Link</span></div>
        <div class="nav-links">
            <a href="#features">Features</a>
            <a href="#roles">Roles</a>
            <a href="/register">Sign Up (Customer)</a>
            <a href="/register_vendor" style="color: #ea580c; font-weight: 600;">Become a Vendor</a>
            <a href="/register_delivery" style="color: #ea580c; font-weight: 600;">Become a Rider</a>
            <a href="/login" class="btn-login">Login</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-text">
            <h1>Support Local. <br><span>Order Food</span> Easily.</h1>
            <p>FoodLink connects you with the best local food businesses in your community. Fast, convenient, and secure ordering for customers, vendors, and delivery riders.</p>
            <a href="/login" class="btn-cta">Get Started Now</a>
        </div>
        <div class="hero-image">
            <!-- Placeholder for a Food Delivery Image -->
            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=800&auto=format&fit=crop" alt="Food Delivery">
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <h2>Why Choose FoodLink?</h2>
        <p class="sub">Everything you need to manage local food ordering in one platform.</p>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="icon">🍔</div>
                <h3>Easy Ordering</h3>
                <p>Browse menus from multiple local vendors and place your order with just a few clicks.</p>
            </div>
            <div class="feature-card">
                <div class="icon">🚀</div>
                <h3>Fast Delivery</h3>
                <p>Our dedicated delivery riders ensure your food arrives hot and fresh at your doorstep.</p>
            </div>
            <div class="feature-card">
                <div class="icon">🔒</div>
                <h3>Secure Payments</h3>
                <p>Integrated payment systems and secure authentication keep your transactions safe.</p>
            </div>
        </div>
    </section>

    <!-- Roles Section -->
    <section class="roles" id="roles">
        <h2>Built for Everyone</h2>
        <div class="role-grid">
            <div class="role-box">
                <div class="role-icon">👤</div>
                <h3>Customers</h3>
                <p>Discover new favorites, order easily, and track your food in real-time.</p>
            </div>
            <div class="role-box">
                <div class="role-icon">🍳</div>
                <h3>Vendors</h3>
                <p>Manage your menu, receive orders, and grow your local food business.</p>
            </div>
            <div class="role-box">
                <div class="role-icon">🛵</div>
                <h3>Riders</h3>
                <p>Get assigned deliveries, navigate easily, and earn on your own schedule.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <div class="footer">
        &copy; 2026 FoodLink System. All rights reserved. | Systems Integration and Architecture 1 | Web Systems and Technologies 2 | Event Driven Programming Project
    </div>

</body>
</html>