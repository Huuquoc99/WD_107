<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductColorRequest extends FormRequest
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
            "name" => "required|max:255",
            // "color_code" => "required|max:7",
        ];
    }

        /**
     * Get the error message for the defined validation rules. 
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "name.required" => "Product Color names cannot be left blank",
            "name.max" => "Product Color name must not exceed 255 characters",
            // "color_code.required" => "Product Color code names cannot be left blank",
            // "color_code.max" => "Product Color code name must not exceed 7 characters",
        ];
    }
}
