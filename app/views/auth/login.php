<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$this->call->view('layouts/header'); 
?>

<div class="auth-screen">
    <div class="auth-card">
        
        <!-- Back to Home Button -->
        <a href="/" style="display: inline-block; margin-bottom: 20px; color: #64748b; text-decoration: none; font-size: 14px; font-weight: 500;">
            &larr; Back to Home
        </a>

        <h1>FoodLink</h1>
        <p class="sub">Local Food Ordering & Delivery Management System</p>

        <!-- Success Message (Native Session) -->
        <?php if(isset($_SESSION['success'])): ?>
            <div style="background: #d1fae5; color: #059669; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: center; border: 1px solid #34d399;">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Error Message (Native Session) -->
        <?php if(isset($_SESSION['error'])): ?>
            <div style="background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: center; border: 1px solid #f87171;">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- LOGIN FORM -->
        <form action="/process_login" method="POST">
            
            <!-- Role Picker -->
            <div class="role-picker">
                <label class="role-btn" style="display:block;">
                    <input type="radio" name="role" value="customer" checked style="display:none;">
                    <div class="role-content" style="padding: 10px;">
                        <div class="role-icon">👤</div>
                        <div class="role-name">Customer</div>
                    </div>
                </label>
                <label class="role-btn" style="display:block;">
                    <input type="radio" name="role" value="vendor" style="display:none;">
                    <div class="role-content" style="padding: 10px;">
                        <div class="role-icon">🍳</div>
                        <div class="role-name">Vendor</div>
                    </div>
                </label>
                <label class="role-btn" style="display:block;">
                    <input type="radio" name="role" value="delivery" style="display:none;">
                    <div class="role-content" style="padding: 10px;">
                        <div class="role-icon">🛵</div>
                        <div class="role-name">Delivery</div>
                    </div>
                </label>
            </div>

            <!-- Email Input -->
            <div class="form-group">
                <label>Email</label>
                <div class="input-wrapper">
                    <span class="icon">👤</span>
                    <input type="email" name="email" placeholder="Enter email" required>
                </div>
            </div>

            <!-- Password Input -->
            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <span class="icon">🔒</span>
                    <input type="password" name="password" placeholder="Enter password" required>
                </div>
            </div>

            <!-- Login Button -->
            <button type="submit" class="btn-primary">Login</button>
        </form>

                <!-- Registration Links -->
        <div style="text-align: center; margin-top: 24px; font-size: 14px; color: #64748b; display: flex; flex-direction: column; gap: 10px;">
            <div>
                <span style="display: block; margin-bottom: 5px;">Don't have an account?</span>
                <a href="/register" style="color:#ea580c; font-weight: 600; text-decoration: none; margin: 0 5px;">Register as Customer</a>
            </div>
            <div>
                <a href="/register_vendor" style="color:#ea580c; font-weight: 600; text-decoration: none; margin: 0 5px;">Become a Vendor</a>
                <span style="color: #cbd5e1;">|</span>
                <a href="/register_delivery" style="color:#ea580c; font-weight: 600; text-decoration: none; margin: 0 5px;">Delivery Rider</a>
            </div>
        </div>
        
    </div>
</div>

<?php $this->call->view('layouts/footer'); ?>