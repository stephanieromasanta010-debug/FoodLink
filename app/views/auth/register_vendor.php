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

        <h1>Partner with Us</h1>
        <p class="sub">Register your food business on FoodLink.</p>

        <!-- Error Message (Native Session) -->
        <?php if(isset($_SESSION['error'])): ?>
            <div style="background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: center; border: 1px solid #f87171;">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="/process_vendor_register" method="POST">
            
            <div class="form-group">
                <label>Business Name</label>
                <div class="input-wrapper">
                    <span class="icon">🏪</span>
                    <input type="text" name="business_name" placeholder="e.g. Lola Nena's Carinderia" required>
                </div>
            </div>

            <!-- BAGONG FIELD: STORE ADDRESS -->
            <div class="form-group">
                <label>Store Address</label>
                <div class="input-wrapper">
                    <span class="icon">📍</span>
                    <input type="text" name="store_address" placeholder="e.g. 123 Rizal Ave, Quezon City" required>
                </div>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <div class="input-wrapper">
                    <span class="icon">✉</span>
                    <input type="email" name="email" placeholder="Enter business email" required>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <span class="icon">🔒</span>
                    <input type="password" name="password" placeholder="Create password" required>
                </div>
            </div>

            <button type="submit" class="btn-primary">Register as Vendor</button>
        </form>

        <div style="text-align: center; margin-top: 24px; font-size: 14px; color: #64748b;">
            Already a partner? <a href="/login" style="color:#ea580c; font-weight: 600; text-decoration: none;">Login here</a>
        </div>
    </div>
</div>

<?php $this->call->view('layouts/footer'); ?>