<?php $this->call->view('layouts/header'); ?>
<?php $this->call->view('layouts/sidebar', ['role' => $role, 'user_name' => $user_name, 'active_page' => $active_page]); ?>

<h1>Add New Menu Item</h1>
<p class="page-sub">Create a new product for your customers.</p>

<div class="table-section" style="max-width: 600px;">
    <form action="/vendor/menu/store" method="POST">
        <div class="form-group">
            <label>Product Name *</label>
            <div class="input-wrapper"><input type="text" name="name" placeholder="e.g. Chicken Adobo" required></div>
        </div>
        <div class="form-group">
            <label>Description</label>
            <div class="input-wrapper"><input type="text" name="description" placeholder="Short description"></div>
        </div>
        <div class="form-group">
            <label>Price (₱) *</label>
            <div class="input-wrapper"><input type="number" step="0.01" name="price" placeholder="0.00" required></div>
        </div>
        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" class="btn-primary" style="width: auto; padding: 10px 24px;">Save Product</button>
            <a href="/vendor/menu" class="btn-primary" style="background: #e2e8f0; color: #475569; width: auto; padding: 10px 24px; text-decoration: none; text-align: center;">Cancel</a>
        </div>
    </form>
</div>

<?php $this->call->view('layouts/footer'); ?>