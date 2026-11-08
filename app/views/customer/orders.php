<?php $this->call->view('layouts/header'); ?>
<?php $this->call->view('layouts/sidebar', ['role' => $role, 'user_name' => $user_name, 'active_page' => $active_page]); ?>

<h1>My Orders</h1>
<p class="page-sub">Track your current and past orders.</p>

<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!-- Success/Error messages -->
<?php if(isset($_SESSION['success'])): ?>
    <div style="background: #d1fae5; color: #059669; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<div class="table-section">
    <h3>Order History</h3>
    <table>
        <thead><tr><th>Order ID</th><th>Total</th><th>Address</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
            <?php if(empty($orders)): ?>
                <tr><td colspan="5" style="text-align: center; color:#94a3b8; padding: 20px;">Wala ka pang orders.</td></tr>
            <?php else: ?>
                <?php foreach($orders as $o): ?>
                    <tr>
                        <td>#<?= $o['id'] ?></td>
                        <td>₱<?= number_format($o['total_amount'], 2) ?></td>
                        <td><?= htmlspecialchars($o['delivery_address']) ?></td>
                        <td>
                            <?php 
                            $status = $o['status'];
                            $badge_class = 'pending';
                            if($status == 'preparing') $badge_class = 'preparing';
                            if($status == 'ready') $badge_class = 'ready';
                            if($status == 'delivering') $badge_class = 'delivering';
                            if($status == 'delivered') $badge_class = 'delivered';
                            ?>
                            <span class="badge <?= $badge_class ?>"><?= ucfirst($status) ?></span>
                        </td>
                        <td>
                            <?php 
                                $date = new DateTime($o['created_at'], new DateTimeZone('UTC'));
                                $date->setTimezone(new DateTimeZone('Asia/Manila'));
                                echo $date->format('M d, Y h:i A'); 
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php $this->call->view('layouts/footer'); ?>