<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Vendor extends Controller {
    
    public function __construct() {
        parent::__construct();
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'vendor') {
            header("Location: /login");
            exit();
        }
        $this->call->database();
    }

    public function dashboard() {
        $vendor_id = $_SESSION['user_id'];

        // KUNIN ANG BUSINESS NAME SA VENDORS TABLE
        $profile = $this->db->table('vendors')->where('user_id', $vendor_id)->get();
        $business_name = $profile ? $profile['business_name'] : $_SESSION['user_name'];

        // 1. Pending Orders
        $pending_orders = $this->db->table('orders')->where('vendor_id', $vendor_id)->where('status', 'pending')->count();

        // 2. Preparing Orders
        $preparing_orders = $this->db->table('orders')->where('vendor_id', $vendor_id)->where('status', 'preparing')->count();

        // 3. Total Sales
        $delivered_orders = $this->db->table('orders')->where('vendor_id', $vendor_id)->where('status', 'delivered')->get_all();
        $total_sales = 0;
        foreach($delivered_orders as $o) { $total_sales += $o['total_amount']; }

        // 4. Menu Items
        $total_products = $this->db->table('products')->where('vendor_id', $vendor_id)->count();

        // 5. Recent Orders
        $recent_orders = $this->db->table('orders')->where('vendor_id', $vendor_id)->order_by('created_at', 'DESC')->limit(5)->get_all();

        $data = [
            'role' => 'vendor',
            'user_name' => $business_name, // Ginagamit na natin ang business name
            'active_page' => 'dashboard',
            'pending_orders' => $pending_orders,
            'preparing_orders' => $preparing_orders,
            'total_sales' => $total_sales,
            'total_products' => $total_products,
            'recent_orders' => $recent_orders
        ];
        
        $this->call->view('vendor/dashboard', $data);
    }

    public function menu() {
        $vendor_id = $_SESSION['user_id'];
        
        // Kunin din ang business name para sa sidebar header
        $profile = $this->db->table('vendors')->where('user_id', $vendor_id)->get();
        $business_name = $profile ? $profile['business_name'] : $_SESSION['user_name'];

        $data = [
            'role' => 'vendor',
            'user_name' => $business_name,
            'active_page' => 'menu',
            'products' => $this->db->table('products')->where('vendor_id', $vendor_id)->get_all()
        ];
        $this->call->view('vendor/menu', $data);
    }

    public function create_product() {
        $vendor_id = $_SESSION['user_id'];
        $profile = $this->db->table('vendors')->where('user_id', $vendor_id)->get();
        $business_name = $profile ? $profile['business_name'] : $_SESSION['user_name'];

        $data = ['role' => 'vendor', 'user_name' => $business_name, 'active_page' => 'menu'];
        $this->call->view('vendor/create_product', $data);
    }

    public function store_product() {
        $vendor_id = $_SESSION['user_id'];
        $name = trim($_POST['name'] ?? '');
        $price = $_POST['price'] ?? '';
        
        if (empty($name) || empty($price) || !is_numeric($price)) {
            $_SESSION['error'] = "Please fill in all fields with valid data.";
            header("Location: /vendor/menu/create");
            exit();
        }

        $this->db->table('products')->insert([
            'vendor_id' => $vendor_id, 'name' => $name,
            'description' => $_POST['description'] ?? '', 'price' => $price, 'is_available' => 1
        ]);

        $_SESSION['success'] = "Product added successfully!";
        header("Location: /vendor/menu");
        exit();
    }

    public function edit_product() {
        $vendor_id = $_SESSION['user_id'];
        $profile = $this->db->table('vendors')->where('user_id', $vendor_id)->get();
        $business_name = $profile ? $profile['business_name'] : $_SESSION['user_name'];

        $id = $_GET['id'] ?? 0;
        $product = $this->db->table('products')->where('id', $id)->where('vendor_id', $vendor_id)->get();
        
        if (!$product) {
            $_SESSION['error'] = "Product not found.";
            header("Location: /vendor/menu");
            exit();
        }

        $data = ['role' => 'vendor', 'user_name' => $business_name, 'active_page' => 'menu', 'product' => $product];
        $this->call->view('vendor/edit_product', $data);
    }

    public function update_product() {
        $vendor_id = $_SESSION['user_id'];
        $id = $_GET['id'] ?? 0;
        $name = trim($_POST['name'] ?? '');
        $price = $_POST['price'] ?? '';
        
        if (empty($name) || empty($price) || !is_numeric($price)) {
            $_SESSION['error'] = "Please fill in all fields with valid data.";
            header("Location: /vendor/menu/edit?id=" . $id);
            exit();
        }

        $this->db->table('products')->where('id', $id)->where('vendor_id', $vendor_id)->update([
            'name' => $name, 'description' => $_POST['description'] ?? '',
            'price' => $price, 'is_available' => isset($_POST['is_available']) ? 1 : 0
        ]);

        $_SESSION['success'] = "Product updated successfully!";
        header("Location: /vendor/menu");
        exit();
    }

    public function delete_product() {
        $vendor_id = $_SESSION['user_id'];
        $id = $_GET['id'] ?? 0;
        $this->db->table('products')->where('id', $id)->where('vendor_id', $vendor_id)->delete();
        $_SESSION['success'] = "Product deleted successfully!";
        header("Location: /vendor/menu");
        exit();
    }

    public function orders() {
        $vendor_id = $_SESSION['user_id'];
        
        $profile = $this->db->table('vendors')->where('user_id', $vendor_id)->get();
        $business_name = $profile ? $profile['business_name'] : $_SESSION['user_name'];

        $data = [
            'role' => 'vendor',
            'user_name' => $business_name,
            'active_page' => 'orders',
            'orders' => $this->db->table('orders')->where('vendor_id', $vendor_id)->order_by('created_at', 'DESC')->get_all()
        ];
        $this->call->view('vendor/orders', $data);
    }

    public function update_order_status() {
        $vendor_id = $_SESSION['user_id'];
        $order_id = $_GET['id'] ?? 0;
        $new_status = $_GET['status'] ?? '';
        $valid_statuses = ['preparing', 'ready', 'cancelled'];
        
        if (in_array($new_status, $valid_statuses)) {
            $this->db->table('orders')->where('id', $order_id)->where('vendor_id', $vendor_id)->update(['status' => $new_status]);
            $_SESSION['success'] = "Order #$order_id status updated to " . ucfirst($new_status) . ".";
        }
        header("Location: /vendor/orders");
        exit();
    }
}