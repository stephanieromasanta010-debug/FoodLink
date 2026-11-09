<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$this->call->view('layouts/header'); 
$this->call->view('layouts/sidebar', ['role' => $role, 'user_name' => $user_name, 'active_page' => $active_page]); 
?>

<h1>Vendor Dashboard</h1>
<p class="page-sub"><?= htmlspecialchars($user_name) ?> · Business Overview</p>

<div class="stats-grid">
    <div class="stat-card">
        <div class="label">Pending Orders</div>
        <div class="value"><?= $pending_orders ?></div>
        <div class="sub">Awaiting confirmation</div>
    </div>
    <div class="stat-card amber">
        <div class="label">Preparing</div>
        <div class="value"><?= $preparing_orders ?></div>
        <div class="sub">In the kitchen</div>
    </div>
    <div class="stat-card green">
        <div class="label">Total Sales</div>
        <div class="value">₱<?= number_format($total_sales, 2) ?></div>
        <div class="sub">From delivered orders</div>
    </div>
    <div class="stat-card purple">
        <div class="label">Menu Items</div>
        <div class="value"><?= $total_products ?></div>
        <div class="sub">Active products</div>
    </div>
</div>

<div class="table-section">
    <h3>Recent Orders</h3>
    <table>
        <thead><tr><th>Order ID</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
            <?php if(empty($recent_orders)): ?>
                <tr><td colspan="4" style="text-align: center; color: #94a3b8; padding: 20px;">Wala pang orders.</td></tr>
            <?php else: ?>
                <?php foreach($recent_orders as $order): ?>
                    <tr>
                        <td style="font-weight: 600;">#<?= $order['id'] ?></td>
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
                            <?php 
                                $date = new DateTime($order['created_at'], new DateTimeZone('UTC'));
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