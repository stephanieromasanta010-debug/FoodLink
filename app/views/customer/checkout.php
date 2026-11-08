<?php $this->call->view('layouts/header'); ?>
<?php $this->call->view('layouts/sidebar', ['role' => $role, 'user_name' => $user_name, 'active_page' => $active_page]); ?>

<h1>Checkout</h1>
<p class="page-sub">Review your order and confirm delivery details.</p>

<div class="table-section" style="max-width: 600px;">
    <h3>Your Cart</h3>
    <table>
        <thead><tr><th>Item</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead>
                <tbody>
            <?php 
            $total = 0;
            if(empty($cart)): ?>
                <tr><td colspan="4" style="text-align: center; color:#94a3b8;">Walang laman ang cart mo.</td></tr>
            <?php else: ?>
                <?php foreach($cart as $item): 
                    $subtotal = $item['price'] * $item['qty'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td>x<?= $item['qty'] ?></td>
                        <td>₱<?= number_format($item['price'], 2) ?></td>
                        <td>₱<?= number_format($subtotal, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr style="font-weight: bold; background: #f8fafc;">
                    <td colspan="3" style="text-align: right;">Total Amount:</td>
                    <td>₱<?= number_format($total, 2) ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if(!empty($cart)): ?>
        <form action="/customer/place_order" method="POST" style="margin-top: 20px;">
            <div class="form-group">
                <label>Delivery Address *</label>
                <div class="input-wrapper">
                    <input type="text" name="address" placeholder="Ilagay ang address" required value="<?= $_SESSION['address'] ?? '' ?>">
                </div>
            </div>
            <button type="submit" class="btn-primary">Place Order Now</button>
        </form>
    <?php endif; ?>
</div>

<?php $this->call->view('layouts/footer'); ?>