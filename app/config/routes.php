<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
/**
 * ------------------------------------------------------------------
 * LavaLust - an opensource lightweight PHP MVC Framework
 * ------------------------------------------------------------------
 *
 * MIT License
 *
 * Copyright (c) 2020 Ronald M. Marasigan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package LavaLust
 * @author Ronald M. Marasigan <ronald.marasigan@yahoo.com>
 * @since Version 1
 * @link https://github.com/ronmarasigan/LavaLust
 * @license https://opensource.org/licenses/MIT MIT License
 */

/*
| -------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------
| Here is where you can register web routes for your application.
|
|
*/
/** @var object $router **/

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: " . ($_SERVER['HTTP_ORIGIN'] ?? '*'));
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");
    http_response_code(200);
    exit();
}



// Default route goes to the Login page
$router->get('/', 'Landing::index'); 
$router->get('/login', 'Auth::index');

$router->get('/register', 'Auth::register');               
$router->post('/process_register', 'Auth::process_register'); 

// Handle the login form submission 
$router->post('/process_login', 'Auth::process_login');
$router->get('/logout', 'Auth::logout');

// Customer Routes
$router->get('/customer', 'Customer::dashboard');
$router->get('/customer/browse', 'Customer::browse');     
$router->get('/customer/orders', 'Customer::orders');

// Bagong Routes para sa Ordering
$router->post('/customer/add_to_cart', 'Customer::add_to_cart');
$router->get('/customer/checkout', 'Customer::checkout');
$router->post('/customer/place_order', 'Customer::place_order');

// Vendor Routes
$router->get('/vendor', 'Vendor::dashboard');
$router->get('/vendor/menu', 'Vendor::menu');       
$router->get('/vendor/orders', 'Vendor::orders');   
$router->get('/register_vendor', 'Auth::register_vendor');
$router->post('/process_vendor_register', 'Auth::process_vendor_register');

// CRUD Routes for Products
$router->get('/vendor/menu/create', 'Vendor::create_product');
$router->post('/vendor/menu/store', 'Vendor::store_product');
$router->get('/vendor/menu/edit', 'Vendor::edit_product');     
$router->post('/vendor/menu/update', 'Vendor::update_product');  
$router->get('/vendor/menu/delete', 'Vendor::delete_product');   

// Vendor Order Update Route
$router->get('/vendor/orders/update_status', 'Vendor::update_order_status');

// Delivery Routes
$router->get('/delivery', 'Delivery::dashboard');
$router->get('/delivery/tasks', 'Delivery::tasks'); 
$router->get('/delivery/update_status', 'Delivery::update_status');
$router->get('/register_delivery', 'Auth::register_delivery');
$router->post('/process_delivery_register', 'Auth::process_delivery_register');

$router->post('/api/login', 'Api::login');
$router->get('/api/products', 'Api::products');
$router->post('/api/add_product', 'Api::add_product');
$router->post('/api/update_product', 'Api::update_product');
$router->get('/api/delete_product', 'Api::delete_product');
$router->get('/api/orders', 'Api::orders');
$router->post('/api/update_order_status', 'Api::update_order_status');
$router->post('/api/place_order', 'Api::place_order');

$router->post('/api/create_checkout_session', 'Api::create_checkout_session');
$router->get('/api/verify_payment', 'Api::verify_payment');
$router->post('/api/upload_proof', 'Api::upload_proof');
$router->post('/api/update_order_status', 'Api::update_order_status');
$router->get('/api/order_details', 'Api::order_details');
$router->get('/api/get_coordinates', 'Api::get_coordinates');
$router->get('/api/calculate_distance', 'Api::calculate_distance');
$router->get('/api/geocode_address', 'Api::geocode_address');