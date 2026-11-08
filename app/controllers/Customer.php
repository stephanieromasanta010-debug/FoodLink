<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Customer extends Controller {
    
    public function __construct() {
        parent::__construct();
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
            header("Location: /login");
            exit();
        }
        $this->call->database();
    }

        public function dashboard() {
        $customer_id = $_SESSION['user_id'];
        $user_name = $_SESSION['user_name'];

        $all_orders = $this->db->table('orders')->where('customer_id', $customer_id)->get_all();
        $active_orders = 0;
        $completed_orders = 0;
        $total_spent = 0;

        foreach($all_orders as $order) {
            if(in_array($order['status'], ['pending', 'preparing', 'ready', 'delivering'])) { $active_orders++; }
            if($order['status'] == 'delivered') {
                $completed_orders++;
                $total_spent += $order['total_amount'];
            }
        }

        $recent_orders = [];
        $all_vendors = $this->db->table('users')->where('role', 'vendor')->get_all();
        $vendor_names = [];
        foreach($all_vendors as $v) { $vendor_names[$v['id']] = $v['name']; }

        // Dito inalis natin ang limit(3) para lumabas lahat
        $recent_query = $this->db->table('orders')->where('customer_id', $customer_id)->order_by('created_at', 'DESC')->get_all();
        
        foreach($recent_query as $o) {
            $o['vendor_name'] = $vendor_names[$o['vendor_id']] ?? 'Unknown Vendor';
            $recent_orders[] = $o;
        }

        $data = [
            'role' => 'customer',
            'user_name' => $user_name,
            'active_page' => 'dashboard',
            'active_orders' => $active_orders,
            'completed_orders' => $completed_orders,
            'total_spent' => $total_spent,
            'recent_orders' => $recent_orders // Lahat ng orders na ito
        ];
        
        $this->call->view('customer/dashboard', $data);
    }

    public function browse() {
        $data = [
            'role' => 'customer',
            'user_name' => $_SESSION['user_name'],
            'active_page' => 'browse',
            'products' => $this->db->table('products')->where('is_available', 1)->get_all()
        ];
        $this->call->view('customer/browse', $data);
    }

    public function add_to_cart() {
        $product_id = $_POST['product_id'] ?? 0;
        $quantity = (int)($_POST['quantity'] ?? 1);
        if ($quantity < 1) $quantity = 1;

        $product = $this->db->table('products')->where('id', $product_id)->get();
        
        if ($product) {
            if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }
            $found = false;
            foreach($_SESSION['cart'] as &$item) {
                if($item['id'] == $product_id) {
                    $item['qty'] += $quantity;
                    $found = true;
                    break;
                }
            }
            if(!$found) {
                $_SESSION['cart'][] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'vendor_id' => $product['vendor_id'],
                    'qty' => $quantity
                ];
            }
            $_SESSION['success'] = $quantity . "x " . $product['name'] . " added to cart!";
        }
        header("Location: /customer/browse");
        exit();
    }

    public function checkout() {
        $data = [
            'role' => 'customer',
            'user_name' => $_SESSION['user_name'],
            'active_page' => 'checkout',
            'cart' => $_SESSION['cart'] ?? []
        ];
        $this->call->view('customer/checkout', $data);
    }

    public function place_order() {
        if (empty($_SESSION['cart'])) {
            $_SESSION['error'] = "Your cart is empty.";
            header("Location: /customer/browse");
            exit();
        }

        $customer_id = $_SESSION['user_id'];
        $delivery_address = $_POST['address'] ?? '';
        $total_amount = 0;
        foreach ($_SESSION['cart'] as $item) { $total_amount += ($item['price'] * $item['qty']); }
        $vendor_id = $_SESSION['cart'][0]['vendor_id'];

        $order_id = $this->db->table('orders')->insert([
            'customer_id' => $customer_id,
            'vendor_id' => $vendor_id,
            'total_amount' => $total_amount,
            'status' => 'pending',
            'delivery_address' => $delivery_address
        ]);

        foreach ($_SESSION['cart'] as $item) {
            $this->db->table('order_items')->insert([
                'order_id' => $order_id,
                'product_id' => $item['id'],
                'quantity' => $item['qty'], 
                'subtotal' => ($item['price'] * $item['qty'])
            ]);
        }

        unset($_SESSION['cart']);
        $_SESSION['success'] = "Order placed successfully! Order #" . $order_id;
        header("Location: /customer/orders");
        exit();
    }

    public function orders() {
        $customer_id = $_SESSION['user_id'];
        $data = [
            'role' => 'customer',
            'user_name' => $_SESSION['user_name'],
            'active_page' => 'orders',
            'orders' => $this->db->table('orders')->where('customer_id', $customer_id)->order_by('created_at', 'DESC')->get_all()
        ];
        $this->call->view('customer/orders', $data);
    }
}