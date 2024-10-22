<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCapacity;
use App\Models\ProductColor;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function productDetail($slug): \Illuminate\Http\JsonResponse
    {
        $product = Product::query()->with(['variants','galleries'])->where('slug', $slug)->first();

        if (!$product) {
            return response()->json([
                'message' => 'Sản phẩm không tồn tại.',
                'status' => 'error'
            ], 404);
        }

        $colors = ProductColor::query()->pluck('name', 'id')->all();
        $sizes = ProductCapacity::query()->pluck('name', 'id')->all();

        return response()->json([
            'message' => 'Lấy thông tin sản phẩm thành công.',
            'data' => [
                'product' => $product,
                'colors' => $colors,
                'sizes' => $sizes
            ],
            'status' => 'success'
        ], 200);
    }
}
