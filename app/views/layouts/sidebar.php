<div class="dashboard-layout">
    <div class="topbar">
        <div class="logo">Food<span>Link</span></div>
        <div class="user-info">
            <span class="role-badge <?= $role ?>"><?= ucfirst($role) ?></span>
            <span class="name"><?= $user_name ?></span>
            <a href="/logout" class="signout">Sign out</a>
        </div>
    </div>
    <div class="main-body">
        <div class="sidebar">
            <div class="section-label"><?= ucfirst($role) ?></div>
            
            <?php if ($role == 'customer'): ?>
                <!-- Pansinin ang $active_page, dito natin malalaman kung anong page yung bukas -->
                <a href="/customer" class="<?= ($active_page == 'dashboard') ? 'active' : '' ?>">Dashboard</a>
                <a href="/customer/browse" class="<?= ($active_page == 'browse') ? 'active' : '' ?>">Browse Food</a>
                <a href="/customer/orders" class="<?= ($active_page == 'orders') ? 'active' : '' ?>">My Orders</a>
                
            <?php elseif ($role == 'vendor'): ?>
                <!-- VENDOR SIDEBAR -->
                <a href="/vendor" class="<?= (isset($active_page) && $active_page == 'dashboard') ? 'active' : '' ?>">Dashboard</a>
                <a href="/vendor/menu" class="<?= (isset($active_page) && $active_page == 'menu') ? 'active' : '' ?>">Menu Management</a>
                <a href="/vendor/orders" class="<?= (isset($active_page) && $active_page == 'orders') ? 'active' : '' ?>">Incoming Orders</a>
                
            <?php elseif ($role == 'delivery'): ?>
                <!-- DELIVERY SIDEBAR -->
                <a href="/delivery" class="<?= (isset($active_page) && $active_page == 'dashboard') ? 'active' : '' ?>">Dashboard</a>
                <a href="/delivery/tasks" class="<?= (isset($active_page) && $active_page == 'tasks') ? 'active' : '' ?>">Assigned Deliveries</a>
            <?php endif; ?>
            


        </div>
        <div class="content-area">