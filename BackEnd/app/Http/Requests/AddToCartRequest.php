<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
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
            'quantity' => 'required|integer|min:1',
            'product_id' => 'required|exists:products,id',
            'product_capacity_id' => 'required',
            'product_color_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'quantity.required' => 'Vui lòng nhập số lượng',
            'quantity.integer' => 'Số lượng phải là số nguyên',
            'quantity.min' => 'Số lượng tối thiểu là 1',
            'product_capacity_id.required' => 'Dung lượng không được bỏ trống',
            'product_color_id.required' => 'Màu sắc không được bỏ trống',
        ];
    }
}
