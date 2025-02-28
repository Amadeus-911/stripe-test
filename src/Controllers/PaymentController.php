<?php

namespace Ollyo\Task\Controllers;

use Stripe\Checkout\Session;

class PaymentController
{
    public function showPaymentPage()
    {
        return view('checkout', []);
    }

    public function success()
    {
        return view('payment-success', ['message' => 'Payment successful!']);
    }

    public function cancel()
    {
        return view('payment-cancel', ['message' => 'Payment cancelled.']);
    }
}