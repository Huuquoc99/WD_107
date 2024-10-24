<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku',
            'catalogue_id' => 'required|integer|exists:catalogues,id',
            'price_regular' => 'required|numeric|min:0',
            'price_sale' => 'nullable|numeric|min:0|lt:price_regular',
            'short_description' => 'nullable|string|max:500',
            'product_variants' => 'required|array',
            'product_variants.*.quantity' => 'required|integer|min:0',
            'product_variants.*.price' => 'required|numeric|min:0',
            'product_variants.*.sku' => 'required|string|max:255|unique:product_variants,sku',
            'product_variants.*.status' => 'required|boolean',
            'product_variants.*.image' => 'nullable|image|max:2048',
            'tags' => 'required|array',
            'tags.*' => 'integer|exists:tags,id',
            'product_galleries' => 'nullable|array',
            'product_galleries.*' => 'nullable|image|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'name.string' => 'Tên sản phẩm phải là chuỗi ký tự.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',

            'sku.required' => 'SKU là bắt buộc.',
            'sku.string' => 'SKU phải là chuỗi ký tự.',
            'sku.max' => 'SKU không được vượt quá 255 ký tự.',
            'sku.unique' => 'SKU đã tồn tại, vui lòng chọn SKU khác.',

            'catalogue_id.required' => 'Danh mục sản phẩm là bắt buộc.',
            'catalogue_id.integer' => 'Danh mục sản phẩm phải là số nguyên.',
            'catalogue_id.exists' => 'Danh mục sản phẩm không tồn tại.',

            'price_regular.required' => 'Giá gốc là bắt buộc.',
            'price_regular.numeric' => 'Giá gốc phải là một số.',
            'price_regular.min' => 'Giá gốc phải lớn hơn hoặc bằng 0.',

            'price_sale.numeric' => 'Giá khuyến mãi phải là một số.',
            'price_sale.min' => 'Giá khuyến mãi phải lớn hơn hoặc bằng 0.',
            'price_sale.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',

            'short_description.string' => 'Mô tả ngắn phải là chuỗi ký tự.',
            'short_description.max' => 'Mô tả ngắn không được vượt quá 500 ký tự.',

            'product_variants.required' => 'Danh sách biến thể sản phẩm là bắt buộc.',
            'product_variants.array' => 'Danh sách biến thể sản phẩm phải là một mảng.',

            'product_variants.*.quantity.required' => 'Số lượng biến thể là bắt buộc.',
            'product_variants.*.quantity.integer' => 'Số lượng biến thể phải là số nguyên.',
            'product_variants.*.quantity.min' => 'Số lượng biến thể phải lớn hơn hoặc bằng 0.',

            'product_variants.*.price.required' => 'Giá biến thể là bắt buộc.',
            'product_variants.*.price.numeric' => 'Giá biến thể phải là một số.',
            'product_variants.*.price.min' => 'Giá biến thể phải lớn hơn hoặc bằng 0.',

            'product_variants.*.sku.required' => 'SKU của biến thể là bắt buộc.',
            'product_variants.*.sku.string' => 'SKU của biến thể phải là chuỗi ký tự.',
            'product_variants.*.sku.max' => 'SKU của biến thể không được vượt quá 255 ký tự.',
            'product_variants.*.sku.unique' => 'SKU của biến thể đã tồn tại, vui lòng chọn SKU khác.',

            'product_variants.*.status.required' => 'Trạng thái biến thể là bắt buộc.',
            'product_variants.*.status.boolean' => 'Trạng thái biến thể phải là giá trị boolean.',

            'product_variants.*.image.image' => 'Ảnh biến thể phải là tệp ảnh.',
            'product_variants.*.image.max' => 'Ảnh biến thể không được vượt quá 2MB.',

            'tags.required' => 'Danh sách tag là bắt buộc.',
            'tags.array' => 'Danh sách tag phải là một mảng.',
            'tags.*.integer' => 'Mỗi tag phải là số nguyên.',
            'tags.*.exists' => 'Tag không tồn tại.',

            'product_galleries.array' => 'Danh sách thư viện ảnh phải là một mảng.',
            'product_galleries.*.image' => 'Ảnh trong thư viện phải là tệp ảnh.',
            'product_galleries.*.image.max' => 'Ảnh trong thư viện không được vượt quá 2MB.'
        ];
    }
}
