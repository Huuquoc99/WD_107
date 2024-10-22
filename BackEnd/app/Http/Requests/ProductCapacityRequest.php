<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCapacityRequest extends FormRequest
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
            "name.required" => "Product capacity names cannot be left blank",
            "name.max" => "Product capacity name must not exceed 255 characters",
        ];
    }
}
