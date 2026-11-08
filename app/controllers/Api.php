<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Api extends Controller {
    
    public function __construct() {
        parent::__construct();
        $this->call->database();

        $this->db->raw("SET time_zone = '+08:00'");

        header("Access-Control-Allow-Origin: https://foodlink-react.onrender.com/");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Credentials: true");
    }

    // LOGIN
    public function login() {
        $input = json_decode(file_get_contents('php://input'), true);
        $user = $this->db->table('users')->where('email', $input['email'] ?? '')->get();
        if ($user && password_verify($input['password'] ?? '', $user['password'])) {
            echo json_encode(['status' => 'success', 'user' => ['id' => $user['id'], 'name' => $user['name'], 'role' => $user['role']]]);
        } else {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
        }
    }

    // PRODUCTS
    public function products() {
        echo json_encode($this->db->table('products')->get_all());
    }

    // ADD PRODUCT
    public function add_product() {
        $input = json_decode(file_get_contents('php://input'), true);
        $this->db->table('products')->insert([
            'vendor_id' => $input['vendor_id'] ?? 1,
            'name' => $input['name'],
            'description' => $input['description'] ?? '',
            'price' => $input['price'],
            'is_available' => 1
        ]);
        echo json_encode(['status' => 'success']);
    }

    // EDIT PRODUCT
    public function update_product() {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? 0;
        $this->db->table('products')->where('id', $id)->update([
            'name' => $input['name'],
            'description' => $input['description'] ?? '',
            'price' => $input['price']
        ]);
        echo json_encode(['status' => 'success']);
    }

    // DELETE PRODUCT
    public function delete_product() {
        $id = $_GET['id'] ?? 0;
        $this->db->table('products')->where('id', $id)->delete();
        echo json_encode(['status' => 'success']);
    }

    // ORDERS
    public function orders() {
    $vendor_id = $_GET['vendor_id'] ?? null;
    $rider_id = $_GET['rider_id'] ?? null;
    $customer_id = $_GET['customer_id'] ?? null;
    
    if ($vendor_id) {
        $orders = $this->db->table('orders')->where('vendor_id', $vendor_id)->get_all();
    } elseif ($rider_id) {
        $orders = $this->db->table('orders')->where('rider_id', $rider_id)->get_all();
    } elseif ($customer_id) {
        $orders = $this->db->table('orders')->where('customer_id', $customer_id)->get_all();
    } else {
        $orders = $this->db->table('orders')->get_all();
    }
    
    echo json_encode($orders);
}

    // UPDATE ORDER STATUS
    public function update_order_status() {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? 0;
    $status = $input['status'] ?? '';
    $rider_id = $input['rider_id'] ?? null;
    $proof_of_delivery = $input['proof_of_delivery'] ?? null;
    
    $data = ['status' => $status];
    if ($rider_id) { $data['rider_id'] = $rider_id; }
    if ($proof_of_delivery) { $data['proof_of_delivery'] = $proof_of_delivery; }
    
    // Kapag na-mark as delivered, awtomatikong paid (para sa COD)
    if ($status === 'delivered') {
        $data['payment_status'] = 'paid';
    }
    
    if ($id && $status) {
        $this->db->table('orders')->where('id', $id)->update($data);
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    }
}

    // PLACE ORDER (CHECKOUT)
    public function place_order() {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $customer_id = $input['customer_id'] ?? 1;
    $vendor_id = $input['vendor_id'] ?? 1;
    $address = $input['address'] ?? '';
    $total = $input['total'] ?? 0;
    $payment_method = $input['payment_method'] ?? 'Cash on Delivery';
    $cart = $input['cart'] ?? [];
    $delivery_fee = $input['delivery_fee'] ?? 0;   // <-- BAGO
    $distance_km = $input['distance_km'] ?? 0;     // <-- BAGO

    $order_id = $this->db->table('orders')->insert([
        'customer_id' => $customer_id,
        'vendor_id' => $vendor_id,
        'total_amount' => $total,
        'status' => 'pending',
        'delivery_address' => $address,
        'payment_method' => $payment_method,
        'payment_status' => 'unpaid',
        'delivery_fee' => $delivery_fee,   // <-- BAGO
        'distance_km' => $distance_km      // <-- BAGO
    ]);

    if (!empty($cart)) {
        foreach ($cart as $item) {
            $this->db->table('order_items')->insert([
                'order_id' => $order_id,
                'product_id' => $item['id'],
                'quantity' => 1,
                'subtotal' => $item['price']
            ]);
        }
    }

    echo json_encode(['status' => 'success', 'order_id' => $order_id]);
}

    public function create_checkout_session() {
    require_once ROOT_DIR . 'vendor/autoload.php';
    \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY') ?: '');

    $input = json_decode(file_get_contents('php://input'), true);
    $cart = $input['cart'] ?? [];
    $customer_id = $input['customer_id'] ?? 1;
    $vendor_id = $input['vendor_id'] ?? 1;
    $address = $input['address'] ?? '';
    $delivery_fee = $input['delivery_fee'] ?? 0;   // <-- BAGO
    $distance_km = $input['distance_km'] ?? 0;     // <-- BAGO

    $line_items = [];
    $total = 0;
    foreach ($cart as $item) {
        $line_items[] = [
            'price_data' => [
                'currency' => 'php',
                'product_data' => ['name' => $item['name']],
                'unit_amount' => (int)($item['price'] * 100),
            ],
            'quantity' => 1,
        ];
        $total += $item['price'];
    }

    // Idagdag ang delivery fee sa total
    $total += $delivery_fee;

    // Idagdag ang delivery fee bilang line item (para lumabas sa Stripe)
    if ($delivery_fee > 0) {
        $line_items[] = [
            'price_data' => [
                'currency' => 'php',
                'product_data' => ['name' => 'Delivery Fee'],
                'unit_amount' => (int)($delivery_fee * 100),
            ],
            'quantity' => 1,
        ];
    }

    try {
        $session = \Stripe\Checkout\Session::create([
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => 'http://localhost:5173/customer/orders?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://localhost:5173/customer/checkout',
            'metadata' => [
                'customer_id' => $customer_id,
                'vendor_id' => $vendor_id,
                'address' => $address,
                'total' => $total,
                'delivery_fee' => $delivery_fee,   // <-- BAGO
                'distance_km' => $distance_km      // <-- BAGO
            ],
        ]);

        echo json_encode(['id' => $session->id, 'url' => $session->url]);
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

public function verify_payment() {
    require_once ROOT_DIR . 'vendor/autoload.php';
    \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY') ?: '');

    $session_id = $_GET['session_id'] ?? '';

    try {
        $session = \Stripe\Checkout\Session::retrieve($session_id);
        if ($session->payment_status === 'paid') {
            $meta = $session->metadata;

            $order_id = $this->db->table('orders')->insert([
                'customer_id' => $meta->customer_id,
                'vendor_id' => $meta->vendor_id,
                'total_amount' => $meta->total,
                'status' => 'pending',
                'delivery_address' => $meta->address,
                'payment_method' => 'Stripe (Card)',
                'payment_status' => 'paid',
                'payment_reference' => $session_id,
                'delivery_fee' => $meta->delivery_fee ?? 0,   // <-- BAGO
                'distance_km' => $meta->distance_km ?? 0      // <-- BAGO
            ]);

            echo json_encode(['status' => 'success', 'order_id' => $order_id]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Payment not completed']);
        }
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

    public function order_details() {
    $id = $_GET['id'] ?? 0;
    
    // 1. Kunin ang order
    $order = $this->db->table('orders')->where('id', $id)->get();
    
    if (!$order) {
        http_response_code(404);
        echo json_encode(['error' => 'Order not found']);
        return;
    }
    
    // 2. Kunin ang items gamit ang standard query builder (hindi raw)
    $items = $this->db->table('order_items')
        ->where('order_id', $id)
        ->get_all();
    
    // 3. Kunin ang product names para sa bawat item
    foreach ($items as &$item) {
        $product = $this->db->table('products')->where('id', $item['product_id'])->get();
        $item['product_name'] = $product ? $product['name'] : 'Product #' . $item['product_id'];
    }
    
    echo json_encode([
        'order' => $order,
        'items' => $items
    ]);
}

public function calculate_distance() {
    $vendor_lat = $_GET['vendor_lat'] ?? 0;
    $vendor_lng = $_GET['vendor_lng'] ?? 0;
    $customer_lat = $_GET['customer_lat'] ?? 0;
    $customer_lng = $_GET['customer_lng'] ?? 0;

    $distance_km = 0;
    $source = 'OSRM';

    // Subukan OSRM
    $url = "http://router.project-osrm.org/route/v1/driving/{$vendor_lng},{$vendor_lat};{$customer_lng},{$customer_lat}?overview=false";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200 && $response) {
        $data = json_decode($response, true);
        if (isset($data['routes'][0]['distance'])) {
            $distance_km = $data['routes'][0]['distance'] / 1000;
        }
    }

    // Fallback: Haversine formula
    if ($distance_km === 0) {
        $source = 'Haversine';
        $earth_radius = 6371;
        $dLat = deg2rad($customer_lat - $vendor_lat);
        $dLng = deg2rad($customer_lng - $vendor_lng);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($vendor_lat)) * cos(deg2rad($customer_lat)) * sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance_km = $earth_radius * $c;
    }

    $delivery_fee = 30;
    if ($distance_km > 2) $delivery_fee += ($distance_km - 2) * 10;

    echo json_encode([
        'status' => 'success',
        'distance_km' => round($distance_km, 2),
        'delivery_fee' => round($delivery_fee, 2),
        'source' => $source
    ]);
}

public function get_coordinates() {
    $customer_id = $_GET['user_id'] ?? 0;
    $vendor_id = $_GET['vendor_id'] ?? 0;
    
    $customer = $this->db->table('users')->where('id', $customer_id)->get();
    $vendor = $this->db->table('users')->where('id', $vendor_id)->get();
    
    echo json_encode([
        'customer_lat' => $customer['latitude'] ?? null,
        'customer_lng' => $customer['longitude'] ?? null,
        'vendor_lat' => $vendor['latitude'] ?? null,
        'vendor_lng' => $vendor['longitude'] ?? null,
    ]);
}

public function geocode_address() {
    $address = $_GET['address'] ?? '';
    
    if (empty($address)) {
        echo json_encode(['status' => 'error', 'message' => 'Address required']);
        return;
    }

    $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($address) . "&limit=1&countrycodes=ph";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'FoodLink/1.0 (student project)'); // <-- IMPORTANTE
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Para sa local testing
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        echo json_encode(['status' => 'error', 'message' => 'Network error: ' . $curl_error]);
        return;
    }

    $data = json_decode($response, true);

    if (!empty($data) && isset($data[0]['lat'])) {
        echo json_encode([
            'status' => 'success',
            'lat' => $data[0]['lat'],
            'lng' => $data[0]['lon'],
            'display_name' => $data[0]['display_name']
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Address not found',
            'http_code' => $http_code,
            'raw' => substr($response, 0, 200) // For debugging
        ]);
    }
}

}