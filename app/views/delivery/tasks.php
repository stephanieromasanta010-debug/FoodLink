<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$this->call->view('layouts/header'); 
$this->call->view('layouts/sidebar', ['role' => $role, 'user_name' => $user_name, 'active_page' => $active_page]); 
?>

<h1>Assigned Deliveries</h1>
<p class="page-sub">All deliveries assigned to you.</p>

<?php if(isset($_SESSION['success'])): ?>
    <div style="background: #d1fae5; color: #059669; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #34d399;">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<div class="table-section">
    <h3>Today's Tasks</h3>
    <table>
        <thead><tr><th>Order ID</th><th>Drop-off Location</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
            <?php if(empty($orders)): ?>
                <tr><td colspan="4" style="text-align: center; color: #94a3b8; padding: 20px;">Wala pang assigned tasks.</td></tr>
            <?php else: ?>
                <?php foreach($orders as $order): ?>
                    <tr>
                        <td style="font-weight: 600;">#<?= $order['id'] ?></td>
                        <td><?= htmlspecialchars($order['delivery_address']) ?></td>
                        <td>
                            <?php 
                            $status = $order['status'];
                            $badge_class = 'pending';
                            if($status == 'ready') $badge_class = 'ready';
                            if($status == 'delivering') $badge_class = 'delivering';
                            if($status == 'delivered') $badge_class = 'delivered';
                            ?>
                            <span class="badge <?= $badge_class ?>"><?= ucfirst($status) ?></span>
                        </td>
                        <td>
                            <?php if($status == 'ready'): ?>
                                <a href="/delivery/update_status?id=<?= $order['id'] ?>&status=delivering" class="btn-primary" style="padding: 5px 15px; text-decoration:none; font-size:13px;">Pick Up</a>
                            <?php elseif($status == 'delivering'): ?>
                                <a href="/delivery/update_status?id=<?= $order['id'] ?>&status=delivered" class="btn-primary" style="padding: 5px 15px; text-decoration:none; font-size:13px; background:#10b981;">Mark Delivered</a>
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