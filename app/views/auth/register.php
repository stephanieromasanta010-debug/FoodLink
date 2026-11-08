<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$this->call->view('layouts/header'); 
?>

<div class="auth-screen">
    <div class="auth-card">
        
        <a href="/" style="display: inline-block; margin-bottom: 20px; color: #64748b; text-decoration: none; font-size: 14px; font-weight: 500;">
            &larr; Back to Home
        </a>

        <h1>Create Account</h1>
        <p class="sub">Register as a Customer to start ordering.</p>

        <?php if(isset($_SESSION['error'])): ?>
            <div style="background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: center; border: 1px solid #f87171;">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="/process_register" method="POST">
            
            <div class="form-group">
                <label>Full Name</label>
                <div class="input-wrapper">
                    <span class="icon">👤</span>
                    <input type="text" name="name" placeholder="Enter full name" required>
                </div>
            </div>

            <!-- BAGONG FIELD: DELIVERY ADDRESS -->
            <div class="form-group">
                <label>Delivery Address</label>
                <div class="input-wrapper">
                    <span class="icon">📍</span>
                    <input type="text" name="address" placeholder="e.g. 24 Mabini St, QC" required>
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
                <label>Password</label>
                <div class="input-wrapper">
                    <span class="icon">🔒</span>
                    <input type="password" name="password" placeholder="Create password" required>
                </div>
            </div>

            <button type="submit" class="btn-primary">Create Account</button>
        </form>

        <div style="text-align: center; margin-top: 24px; font-size: 14px; color: #64748b;">
            Already have an account? <a href="/login" style="color:#ea580c; font-weight: 600; text-decoration: none;">Login here</a>
        </div>
    </div>
</div>

<?php $this->call->view('layouts/footer'); ?>