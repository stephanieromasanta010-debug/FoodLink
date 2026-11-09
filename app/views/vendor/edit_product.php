<?php $this->call->view('layouts/header'); ?>
<?php $this->call->view('layouts/sidebar', ['role' => $role, 'user_name' => $user_name, 'active_page' => $active_page]); ?>

<h1>Edit Menu Item</h1>
<p class="page-sub">Update details for <?= htmlspecialchars($product['name']) ?>.</p>

<div class="table-section" style="max-width: 600px;">
    <form action="/vendor/menu/update?id=<?= $product['id'] ?>" method="POST">
        <div class="form-group">
            <label>Product Name *</label>
            <div class="input-wrapper"><input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required></div>
        </div>
        <div class="form-group">
            <label>Description</label>
            <div class="input-wrapper"><input type="text" name="description" value="<?= htmlspecialchars($product['description']) ?>"></div>
        </div>
        <div class="form-group">
            <label>Price (₱) *</label>
            <div class="input-wrapper"><input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required></div>
        </div>
        <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
            <input type="checkbox" name="is_available" id="is_available" style="width: 18px; height: 18px;" <?= $product['is_available'] ? 'checked' : '' ?>>
            <label for="is_available" style="margin: 0;">Item is Available for Ordering</label>
        </div>
        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" class="btn-primary" style="width: auto; padding: 10px 24px;">Update Product</button>
            <a href="/vendor/menu" class="btn-primary" style="background: #e2e8f0; color: #475569; width: auto; padding: 10px 24px; text-decoration: none; text-align: center;">Cancel</a>
        </div>
    </form>
</div>

<?php $this->call->view('layouts/footer'); ?>