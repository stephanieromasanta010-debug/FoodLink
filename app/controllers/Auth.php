<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Auth extends Controller {
    
    public function index() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $this->call->view('auth/login');
    }

    public function register() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $this->call->view('auth/register');
    }

    public function register_vendor() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $this->call->view('auth/register_vendor');
    }

    public function process_login() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $this->call->database();

        $email = $_POST['email'] ?? '';
        $password_input = $_POST['password'] ?? '';
        $selected_role = $_POST['role'] ?? 'customer';

        $user = $this->db->table('users')->where('email', $email)->get();

        if ($user) {
            if (password_verify($password_input, $user['password'])) {
                
                if ($user['role'] !== $selected_role) {
                    $_SESSION['error'] = "You are not registered as a " . ucfirst($selected_role) . ".";
                    header("Location: /login");
                    exit();
                }

                // SET NATIVE SESSION
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['is_logged_in'] = TRUE;

                if ($user['role'] == 'customer') {  
                    $profile = $this->db->table('customers')->where('user_id', $user['id'])->get();
                    $_SESSION['address'] = $profile ? $profile['address'] : '';
                    $_SESSION['phone'] = $profile ? $profile['contact_number'] : '';
                }   

                if ($user['role'] == 'customer') {
                    header("Location: /customer");
                } elseif ($user['role'] == 'vendor') {
                    header("Location: /vendor");
                } elseif ($user['role'] == 'delivery') {
                    header("Location: /delivery");
                }
                exit();

            } else {
                $_SESSION['error'] = 'Incorrect password. Please try again.';
                header("Location: /login");
                exit();
            }
        } else {
            $_SESSION['error'] = 'Email not found in our system.';
            header("Location: /login");
            exit();
        }
    }

            public function process_register() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $this->call->database();

        $name = $_POST['name'] ?? '';
        $address = $_POST['address'] ?? '';         // Kunin ang Address
        $contact_number = $_POST['contact_number'] ?? ''; // Kunin ang Phone
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $existing = $this->db->table('users')->where('email', $email)->get();

        if ($existing) {
            $_SESSION['error'] = "Email is already registered.";
            header("Location: /register");
            exit();
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 1. I-save sa USERS
        $user_id = $this->db->table('users')->insert([
            'name' => $name, 'email' => $email,
            'password' => $hashed_password, 'role' => 'customer' 
        ]);

        // 2. I-save sa CUSTOMERS (kasama ang address at phone)
        $this->db->table('customers')->insert([
            'user_id' => $user_id,
            'address' => $address,            // Totoong address na
            'contact_number' => $contact_number // Totoong phone na
        ]);

        $_SESSION['success'] = "Account created successfully! Please login.";
        header("Location: /login");
        exit();
    }

            public function process_vendor_register() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $this->call->database();

        // KUMUHAIN LAHAT NG INPUTS MUNA
        $business_name = $_POST['business_name'] ?? '';
        $store_address = $_POST['store_address'] ?? '';
        $contact_number = $_POST['contact_number'] ?? '';
        $email = $_POST['email'] ?? '';       // <-- ITO YUNG KULANG
        $password = $_POST['password'] ?? ''; // <-- ITO YUNG KULANG

        // 1. Check kung existing na ang email
        $existing = $this->db->table('users')->where('email', $email)->get();

        if ($existing) {
            $_SESSION['error'] = "Email is already registered.";
            header("Location: /register_vendor");
            exit();
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 2. I-save sa USERS table (Basic info at Role lang)
        $user_id = $this->db->table('users')->insert([
            'name' => $business_name, 
            'email' => $email,
            'password' => $hashed_password,
            'role' => 'vendor' 
        ]);

        // 3. I-save sa VENDORS table (Business specific info)
        $this->db->table('vendors')->insert([
            'user_id' => $user_id,
            'business_name' => $business_name,
            'store_address' => $store_address,
            'contact_number' => $contact_number
        ]);

        // 4. Redirect
        $_SESSION['success'] = "Vendor Account created successfully! Please login.";
        header("Location: /login");
        exit();
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        session_destroy();
        header("Location: /login");
        exit();
    }

        // Ipakita ang Delivery Registration Form
    public function register_delivery() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $this->call->view('auth/register_delivery');
    }

    // I-save ang Delivery Rider sa Database
    public function process_delivery_register() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $this->call->database();

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $vehicle_type = $_POST['vehicle_type'] ?? '';
        $plate_number = $_POST['plate_number'] ?? '';

        // Check kung existing ang email
        $existing = $this->db->table('users')->where('email', $email)->get();

        if ($existing) {
            $_SESSION['error'] = "Email is already registered.";
            header("Location: /register_delivery");
            exit();
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 1. I-save sa USERS
        $user_id = $this->db->table('users')->insert([
            'name' => $name, 
            'email' => $email,
            'password' => $hashed_password,
            'role' => 'delivery' 
        ]);

        // 2. I-save sa DELIVERY_RIDERS table
        $this->db->table('delivery_riders')->insert([
            'user_id' => $user_id,
            'vehicle_type' => $vehicle_type,
            'plate_number' => $plate_number
        ]);

        $_SESSION['success'] = "Delivery Rider Account created successfully! Please login.";
        header("Location: /login");
        exit();
    }
}