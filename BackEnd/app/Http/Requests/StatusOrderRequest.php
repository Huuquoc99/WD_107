<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatusOrderRequest extends FormRequest
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
            'code' => 'required|string|max:50|unique:status_orders,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'The code field is required.',
            'code.unique' => 'The code must be unique.',
            'code.max' => 'The code cannot exceed 50 characters.',
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string',
            'name.max' => 'The name cannot exceed 100 characters.',
        ];
    }
}
