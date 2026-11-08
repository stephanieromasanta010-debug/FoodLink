<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$this->call->view('layouts/header'); 
$this->call->view('layouts/sidebar', ['role' => $role, 'user_name' => $user_name, 'active_page' => $active_page]); 
?>

<h1>Delivery Dashboard</h1>
<p class="page-sub"><?= htmlspecialchars($user_name) ?> · Today's delivery overview</p>

<div class="stats-grid">
    <div class="stat-card">
        <div class="label">Ready for Pickup</div>
        <div class="value"><?= $ready_tasks ?></div>
        <div class="sub">Available tasks</div>
    </div>
    <div class="stat-card amber">
        <div class="label">In Transit</div>
        <div class="value"><?= $in_transit ?></div>
        <div class="sub">On the way</div>
    </div>
    <div class="stat-card green">
        <div class="label">Completed Deliveries</div>
        <div class="value"><?= $completed_today ?></div>
        <div class="sub">Successfully delivered</div>
    </div>
</div>

<div class="table-section">
    <h3>Recent Assigned Tasks</h3>
    <table>
        <thead><tr><th>Order ID</th><th>Address</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
            <?php if(empty($assigned_tasks)): ?>
                <tr><td colspan="4" style="text-align: center; color: #94a3b8; padding: 20px;">Wala pang assigned tasks.</td></tr>
            <?php else: ?>
                <?php foreach($assigned_tasks as $task): ?>
                    <tr>
                        <td style="font-weight: 600;">#<?= $task['id'] ?></td>
                        <td><?= htmlspecialchars($task['delivery_address']) ?></td>
                        <td>
                            <?php 
                            $status = $task['status'];
                            $badge_class = 'pending';
                            if($status == 'ready') $badge_class = 'ready';
                            if($status == 'delivering') $badge_class = 'delivering';
                            if($status == 'delivered') $badge_class = 'delivered';
                            ?>
                            <span class="badge <?= $badge_class ?>"><?= ucfirst($status) ?></span>
                        </td>
                        <td>
                            <?php if($status == 'ready'): ?>
                                <a href="/delivery/update_status?id=<?= $task['id'] ?>&status=delivering" class="btn-primary" style="padding: 5px 15px; text-decoration:none; font-size:13px;">Pick Up</a>
                            <?php elseif($status == 'delivering'): ?>
                                <a href="/delivery/update_status?id=<?= $task['id'] ?>&status=delivered" class="btn-primary" style="padding: 5px 15px; text-decoration:none; font-size:13px; background:#10b981;">Mark Delivered</a>
                            <?php else: ?>
                                <span style="color:#94a3b8; font-size:13px;">Completed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php $this->call->view('layouts/footer'); ?>