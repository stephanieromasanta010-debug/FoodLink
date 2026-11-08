<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$this->call->view('layouts/header'); 
?>

<div class="auth-screen">
    <div class="auth-card">
        
        <a href="/" style="display: inline-block; margin-bottom: 20px; color: #64748b; text-decoration: none; font-size: 14px; font-weight: 500;">
            &larr; Back to Home
        </a>

        <h1>Become a Rider</h1>
        <p class="sub">Join our delivery team and start earning.</p>

        <?php if(isset($_SESSION['error'])): ?>
            <div style="background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: center; border: 1px solid #f87171;">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="/process_delivery_register" method="POST">
            
            <div class="form-group">
                <label>Full Name</label>
                <div class="input-wrapper">
                    <span class="icon">👤</span>
                    <input type="text" name="name" placeholder="Enter full name" required>
                </div>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <div class="input-wrapper">
                    <span class="icon">✉</span>
                    <input type="email" name="email" placeholder="Enter email" required>
                </div>
            </div>

            <div class="form-group">
                <label>Vehicle Type</label>
                <div class="input-wrapper">
                    <span class="icon">🛵</span>
                    <input type="text" name="vehicle_type" placeholder="e.g. Motorcycle, Car" required>
                </div>
            </div>

            <div class="form-group">
                <label>Plate Number</label>
                <div class="input-wrapper">
                    <span class="icon">🔢</span>
                    <input type="text" name="plate_number" placeholder="e.g. ABC-123" required>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <span class="icon">🔒</span>
                    <input type="password" name="password" placeholder="Create password" required>
                </div>
            </div>

            <button type="submit" class="btn-primary">Register as Rider</button>
        </form>

        <div style="text-align: center; margin-top: 24px; font-size: 14px; color: #64748b;">
            Already registered? <a href="/login" style="color:#ea580c; font-weight: 600; text-decoration: none;">Login here</a>
        </div>
    </div>
</div>

<?php $this->call->view('layouts/footer'); ?>