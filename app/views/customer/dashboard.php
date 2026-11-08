<?php $this->call->view('layouts/header'); ?>
<?php $this->call->view('layouts/sidebar', ['role' => $role, 'user_name' => $user_name, 'active_page' => $active_page]); ?>

<h1>Customer Dashboard</h1>
<p class="page-sub">Welcome back, <?= htmlspecialchars($user_name) ?>. Here's your food ordering overview.</p>

<div class="stats-grid">
    <div class="stat-card">
        <div class="label">Active Orders</div>
        <div class="value"><?= $active_orders ?></div>
        <div class="sub">Orders currently in progress</div>
    </div>
    <div class="stat-card green">
        <div class="label">Completed Orders</div>
        <div class="value"><?= $completed_orders ?></div>
        <div class="sub">Successfully delivered</div>
    </div>
    <div class="stat-card amber">
        <div class="label">Total Spent</div>
        <div class="value">₱<?= number_format($total_spent, 2) ?></div>
        <div class="sub">Lifetime total (Delivered orders)</div>
    </div>
    <div class="stat-card purple">
        <div class="label">Favorite Vendor</div>
        <div class="value sm">Lola Nena's</div>
        <div class="sub">(Static placeholder)</div>
    </div>
</div>

<div class="table-section">
    <h3>All Recent Orders</h3>
    <table>
        <thead><tr><th>Order ID</th><th>Vendor</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
            <?php if(empty($recent_orders)): ?>
                <tr><td colspan="5" style="text-align: center; color: #94a3b8; padding: 20px;">No recent orders. <a href="/customer/browse" style="color: #ea580c; font-weight:600;">Order now!</a></td></tr>
            <?php else: ?>
                <?php foreach($recent_orders as $o): ?>
                    <tr>
                        <td>#<?= $o['id'] ?></td>
                        <td><?= htmlspecialchars($o['vendor_name']) ?></td>
                        <td>₱<?= number_format($o['total_amount'], 2) ?></td>
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