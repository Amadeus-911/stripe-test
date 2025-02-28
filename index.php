<?php

use Ollyo\Task\Routes;
use Ollyo\Task\Controllers\HomeController;
use Ollyo\Task\Controllers\PaymentController;
use Ollyo\Task\Controllers\CheckoutController;


require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/helper.php';

use Stripe\Checkout\Session;


$stripeConfig = require __DIR__ . '/config/stripe.php';
\Stripe\Stripe::setApiKey($stripeConfig['secret_key']);

define('BASE_PATH', dirname(__FILE__));
define('BASE_URL', baseUrl());

session_start();

$products = [
    [
        'name' => 'Minimalist Leather Backpack',
        'image' => BASE_URL . '/resources/images/backpack.webp',
        'qty' => 1,
        'price' => 120,
    ],
    [
        'name' => 'Wireless Noise-Canceling Headphones',
        'image' => BASE_URL . '/resources/images/headphone.jpg',
        'qty' => 1,
        'price' => 250,
    ],
    [
        'name' => 'Smart Fitness Watch',
        'image' => BASE_URL . '/resources/images/watch.webp', 
        'qty' => 1,
        'price' => 199,
    ],
    [
        'name' => 'Portable Bluetooth Speaker',
        'image' => BASE_URL . '/resources/images/speaker.webp',
        'qty' => 1,
        'price' => 89,
    ],
];
$shippingCost = 10;

$data = [
    'products' => $products,
    'shipping_cost' => $shippingCost,
    'address' => [
        'name' => 'Sherlock Holmes',
        'email' => 'sherlock@example.com',
        'address' => '221B Baker Street, London, England',
        'city' => 'London',
        'post_code' => '1234',
    ]
];

$_SESSION['products'] = $data['products'];
$_SESSION['address'] = $data['address'];;
$_SESSION['shippingCost'] = $data['shipping_cost'];

$homeController = new HomeController();
$paymentController = new PaymentController();
$checkoutController = new CheckoutController();


// Routes
Routes::get('/', function() use ($homeController) {
    return $homeController->index();
});

Routes::get('/checkout', function() use ($checkoutController, $data) {
    return $checkoutController->index($data);
});

Routes::post('/checkout', function($request) use ($checkoutController) {
    return $checkoutController->processCheckout($request);
});

Routes::get('/payment-success', function() use ($paymentController) {
    return $paymentController->success();
});

Routes::get('/cancel', function() use ($paymentController) {
    return $paymentController->cancel();
});

$route = Routes::getInstance();
$route->dispatch();
?>
