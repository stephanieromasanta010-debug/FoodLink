<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$this->call->view('layouts/header'); 
$this->call->view('layouts/sidebar', ['role' => $role, 'user_name' => $user_name, 'active_page' => $active_page]); 
?>

<h1>Menu Management</h1>
<p class="page-sub">Manage your food products and prices.</p>

<?php if(isset($_SESSION['success'])): ?>
    <div style="background: #d1fae5; color: #059669; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #34d399;">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
    <div style="background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f87171;">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="table-section">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h3 style="margin: 0;">Your Menu</h3>
        <a href="/vendor/menu/create" class="btn-primary" style="padding: 8px 16px; width: auto; text-decoration: none; font-size: 14px;">+ Add New Item</a>
    </div>
    
    <table>
        <thead><tr><th>Item Name</th><th>Description</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php if(empty($products)): ?>
                <tr><td colspan="5" style="text-align: center; color: #94a3b8; padding: 20px;">No products found. Click "+ Add New Item" to get started.</td></tr>
            <?php else: ?>
                <?php foreach($products as $product): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['description']) ?></td>
                        <td>₱<?= number_format($product['price'], 2) ?></td>
                        <td>
                            <?php if($product['is_available']): ?>
                                <span class="badge delivered">Available</span>
                            <?php else: ?>
                                <span class="badge pending">Out of Stock</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/vendor/menu/edit?id=<?= $product['id'] ?>" style="color:#2563eb; font-weight:600; text-decoration:none; margin-right:10px;">Edit</a>
                            <a href="/vendor/menu/delete?id=<?= $product['id'] ?>" style="color:#ef4444; font-weight:600; text-decoration:none;" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php $this->call->view('layouts/footer'); ?>