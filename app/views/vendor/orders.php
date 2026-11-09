<?php $this->call->view('layouts/header'); ?>
<?php $this->call->view('layouts/sidebar', ['role' => $role, 'user_name' => $user_name, 'active_page' => $active_page]); ?>

<h1>Incoming Orders</h1>
<p class="page-sub">Manage customer orders.</p>

<!-- Success/Error Messages (Native Session) -->
<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!-- Success/Error messages -->
<?php if(isset($_SESSION['success'])): ?>
    <div style="background: #d1fae5; color: #059669; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
    <div style="background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f87171;">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="table-section">
    <h3>Orders Today</h3>
    <table>
        <thead><tr><th>Order ID</th><th>Customer</th><th>Total</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
            <?php if(empty($orders)): ?>
                <tr><td colspan="5" style="text-align: center; color: #94a3b8; padding: 20px;">Wala pang orders ngayon.</td></tr>
            <?php else: ?>
                <?php foreach($orders as $order): ?>
                    <tr>
                        <td style="font-weight: 600;">#<?= $order['id'] ?></td>
                        <td><?= htmlspecialchars($order['customer_id']) ?></td>
                        <td>₱<?= number_format($order['total_amount'], 2) ?></td>
                        <td>
                            <?php 
                            $status = $order['status'];
                            $badge_class = 'pending';
                            if($status == 'preparing') $badge_class = 'preparing';
                            if($status == 'ready') $badge_class = 'ready';
                            if($status == 'delivered') $badge_class = 'delivered';
                            ?>
                            <span class="badge <?= $badge_class ?>"><?= ucfirst($status) ?></span>
                        </td>
                        <td>
                            <?php if($status == 'pending'): ?>
                                <a href="/vendor/orders/update_status?id=<?= $order['id'] ?>&status=preparing" class="btn-primary" style="padding: 5px 15px; text-decoration:none; font-size:13px;">Accept & Prepare</a>
                            <?php elseif($status == 'preparing'): ?>
                                <a href="/vendor/orders/update_status?id=<?= $order['id'] ?>&status=ready" class="btn-primary" style="padding: 5px 15px; text-decoration:none; font-size:13px; background:#10b981;">Mark Ready</a>
                            <?php elseif($status == 'ready'): ?>
                                <span style="color:#64748b; font-size:13px;">Waiting for Rider</span>
                            <?php else: ?>
                                <span style="color:#94a3b8; font-size:13px;">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php $this->call->view('layouts/footer'); ?>