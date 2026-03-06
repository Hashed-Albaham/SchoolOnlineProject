<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * List all payment methods
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('sort_order')->get();
        return view('admin.payment_methods.index', compact('paymentMethods'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.payment_methods.create');
    }

    /**
     * Store new payment method
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:100',
            'name_en'         => 'nullable|string|max:100',
            'type'            => 'required|in:bank_transfer,crypto,wallet,cash,other',
            'icon'            => 'nullable|string|max:10',
            'instructions_ar' => 'required|string',
            'instructions_en' => 'nullable|string',
            'account_number'  => 'nullable|string|max:255',
            'account_name'    => 'nullable|string|max:255',
            'sort_order'      => 'nullable|integer|min:0',
            'is_active'       => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        PaymentMethod::create($validated);

        return redirect()->route('admin.payment_methods.index')
            ->with('success', __('site.payment_method_created'));
    }

    /**
     * Show edit form
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment_methods.edit', compact('paymentMethod'));
    }

    /**
     * Update payment method
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:100',
            'name_en'         => 'nullable|string|max:100',
            'type'            => 'required|in:bank_transfer,crypto,wallet,cash,other',
            'icon'            => 'nullable|string|max:10',
            'instructions_ar' => 'required|string',
            'instructions_en' => 'nullable|string',
            'account_number'  => 'nullable|string|max:255',
            'account_name'    => 'nullable|string|max:255',
            'sort_order'      => 'nullable|integer|min:0',
            'is_active'       => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $paymentMethod->update($validated);

        return redirect()->route('admin.payment_methods.index')
            ->with('success', __('site.payment_method_updated'));
    }

    /**
     * Delete payment method
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return redirect()->route('admin.payment_methods.index')
            ->with('success', __('site.payment_method_deleted'));
    }

    /**
     * Toggle active status quickly
     */
    public function toggle(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update(['is_active' => !$paymentMethod->is_active]);
        return back()->with('success', __('site.payment_method_status_updated'));
    }
}
