<?php

namespace Ollyo\Task\Controllers;

use Stripe\Checkout\Session;

// define('BASE_URL', baseUrl());



class CheckoutController
{

    public function index($data)
    {
        return view('checkout', $data);
    }

    public function processCheckout($request)
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'address' => 'required|min:5',
            'city' => 'required|min:2',
            'post_code' => 'required|postal_code'
        ];

        $validation = validate($request, $rules);

        if (!$validation['valid']) {
            return view('checkout', [
                'products' => $_SESSION['products'] ?? [],
                'subtotal' => $_SESSION['subtotal'] ?? 0,
                'shippingCost' => $_SESSION['shippingCost'] ?? 0,
                'total' => $_SESSION['total'] ?? 0,
                'address' => $request,
                'errors' => $validation['errors']
            ]);
        }

        try {
            $lineItems = $this->prepareLineItems();
            $session = $this->createStripeSession($request, $lineItems);

            header("HTTP/1.1 303 See Other");
            header("Location: " . $session->url);
            exit();

        } catch (\Exception $e) {
            error_log('Stripe Error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }

    private function prepareLineItems()
    {
        $lineItems = [];
        foreach ($_SESSION['products'] as $product) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product['name'],
                    ],
                    'unit_amount' => $product['price'] * 100, 
                ],
                'quantity' => $product['qty'],
            ];
        }

        if ($_SESSION['shippingCost'] > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Shipping Cost',
                    ],
                    'unit_amount' => $_SESSION['shippingCost'] * 100,
                ],
                'quantity' => 1,
            ];
        }

        return $lineItems;
    }

    private function createStripeSession($request, $lineItems)
    {
        $base_url = substr(BASE_URL, 0, -1);

        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $base_url . '/payment-success',
            'cancel_url' => $base_url . '/cancel',
            'customer_email' => $request['email'],
            'metadata' => [
                'customer_name' => $request['name'],
                'shipping_address' => $request['address'],
                'city' => $request['city'],
                'postal_code' => $request['post_code'],
            ],
        ]);
    }
}