<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255',
            'user_phone' => 'required|string|max:255',
            'user_address' => 'required|string|max:255',
            'user_note' => 'nullable|string|max:255',
            'is_ship_user_same_user' => 'required|boolean',
            'ship_user_name' => 'nullable|string|max:255',
            'ship_user_email' => 'nullable|email|max:255',
            'ship_user_phone' => 'nullable|string|max:255',
            'ship_user_address' => 'nullable|string|max:255',
            'ship_user_note' => 'nullable|string|max:255',
            'status_order_id' => 'required|exists:status_orders,id',
            'status_payment_id' => 'required|exists:status_payments,id',
        ];
    }

    public function messages()
    {
        return [
            'user_name.required' => 'User name is required.',
            'user_email.required' => 'Email is required.',
            'user_email.email' => 'Email must be a valid email address.',
            'user_phone.required' => 'Phone number is required.',
            'user_address.required' => 'Address is required.',
            'is_ship_user_same_user.required' => 'Please specify shipping information.',
            'status_order_id.required' => 'Order status is required.',
            'status_payment_id.required' => 'Payment status is required.',
            'status_order_id.exists' => 'The selected order status does not exist.',
            'status_payment_id.exists' => 'The selected payment status does not exist.',
        ];
    }
}
