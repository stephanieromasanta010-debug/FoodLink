<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Delivery extends Controller {
    
    public function __construct() {
        parent::__construct();
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'delivery') {
            header("Location: /login");
            exit();
        }
        $this->call->database();
    }

    public function dashboard() {
        $rider_id = $_SESSION['user_id'];

        // 1. Bilangin ang Ready for Pickup
        $ready_tasks = $this->db->table('orders')
            ->where('status', 'ready')
            ->count();

        // 2. Bilangin ang In Transit (kung saan siya ang naka-assign)
        $in_transit = $this->db->table('orders')
            ->where('rider_id', $rider_id)
            ->where('status', 'delivering')
            ->count();

        // 3. Bilangin ang Completed Today
        $completed_today = $this->db->table('orders')
            ->where('rider_id', $rider_id)
            ->where('status', 'delivered')
            ->count();

        // 4. Kunin ang mga Assigned Tasks (Ready at Delivering)
        $assigned_tasks = $this->db->table('orders')
            ->where('rider_id', $rider_id)
            ->or_where('status', 'ready')
            ->order_by('created_at', 'DESC')
            ->get_all();

        $data = [
            'role' => 'delivery',
            'user_name' => $_SESSION['user_name'],
            'active_page' => 'dashboard',
            'ready_tasks' => $ready_tasks,
            'in_transit' => $in_transit,
            'completed_today' => $completed_today,
            'assigned_tasks' => $assigned_tasks
        ];
        
        $this->call->view('delivery/dashboard', $data);
    }

    public function tasks() {
        $rider_id = $_SESSION['user_id'];
        
        $data = [
            'role' => 'delivery',
            'user_name' => $_SESSION['user_name'],
            'active_page' => 'tasks',
            'orders' => $this->db->table('orders')
                ->where('rider_id', $rider_id)
                ->or_where('status', 'ready')
                ->order_by('created_at', 'DESC')
                ->get_all()
        ];
        $this->call->view('delivery/tasks', $data);
    }

    // Function para mag-update ng delivery status
    public function update_status() {
        $rider_id = $_SESSION['user_id'];
        $order_id = $_GET['id'] ?? 0;
        $new_status = $_GET['status'] ?? '';
        $valid_statuses = ['delivering', 'delivered'];
        
        if (in_array($new_status, $valid_statuses)) {
            // I-update ang status at i-assign ang rider (kung hindi pa)
            $this->db->table('orders')->where('id', $order_id)->update([
                'status' => $new_status,
                'rider_id' => $rider_id
            ]);
            $_SESSION['success'] = "Order #$order_id updated to " . ucfirst($new_status) . ".";
        }
        
        header("Location: /delivery/tasks");
        exit();
    }
}