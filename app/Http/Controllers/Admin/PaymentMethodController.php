<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::paginate(10);
        return view('admin.pages.payment-methods.index', compact('paymentMethods'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'account_no' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|boolean',
        ]);

        $validated['status'] = $request->has('status');

        PaymentMethod::create($validated);

        return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method created successfully.');
    }
public function create()
{
    return view('admin.pages.payment-methods.create');
}
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.pages.payment-methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'account_no' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|boolean',
        ]);

        $validated['status'] = $request->has('status');

        $paymentMethod->update($validated);

        return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method updated successfully.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method deleted successfully.');
    }
}
