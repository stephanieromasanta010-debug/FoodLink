<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$this->call->view('layouts/header'); 
$this->call->view('layouts/sidebar', ['role' => $role, 'user_name' => $user_name, 'active_page' => $active_page]); 
?>

<h1>Browse Food</h1>
<p class="page-sub">Order from local food businesses near you.</p>

<?php if(isset($_SESSION['success'])): ?>
    <div style="background: #d1fae5; color: #059669; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #34d399; display: flex; justify-content: space-between; align-items: center;">
        <span><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
        <a href="/customer/checkout" style="font-weight: bold; text-decoration: none; color: #059669; background: #fff; padding: 5px 15px; border-radius: 5px;">Go to Checkout &rarr;</a>
    </div>
<?php endif; ?>

<div class="product-grid" style="display:grid; grid-template-columns:repeat(auto-fill,minmax(240px,1fr)); gap:20px;">
    <?php if(empty($products)): ?>
        <p>No food items available right now.</p>
    <?php else: ?>
        <?php foreach($products as $p): ?>
            <div class="stat-card" style="display:flex; flex-direction:column;">
                <div class="label" style="color: #ea580c; font-weight: 600; margin-bottom: 5px;">Vendor ID: <?= $p['vendor_id'] ?></div>
                <div class="value" style="font-size: 20px; margin-bottom: 5px;"><?= htmlspecialchars($p['name']) ?></div>
                <div class="sub" style="margin-bottom: 15px;"><?= htmlspecialchars($p['description']) ?></div>
                <div class="value" style="margin-top: auto; margin-bottom: 15px;">₱<?= number_format($p['price'], 2) ?></div>
                
                <form action="/customer/add_to_cart" method="POST" style="display: flex; gap: 10px;">
                    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                    <input type="number" name="quantity" value="1" min="1" style="width: 60px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; text-align: center; font-family: inherit;">
                    <button type="submit" class="btn-primary" style="flex: 1; padding: 10px;">Add to Cart</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php $this->call->view('layouts/footer'); ?>