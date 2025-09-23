<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::where('status', true)->get();
        return response()->json($paymentMethods);
    }
    public function show($id)
    {
        $paymentMethod = PaymentMethod::where('id', $id)
                                    ->where('status', true)
                                    ->first();

        if (!$paymentMethod) {
            return response()->json(['message' => 'Payment Method not found or inactive'], 404);
        }

        return response()->json($paymentMethod);
    }
}
