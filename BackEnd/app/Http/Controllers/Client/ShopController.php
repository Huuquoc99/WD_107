<?php

namespace App\Http\Controllers\Client;

use App\Models\Product;
use App\Models\Catalogue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopController extends Controller
{
    public function listProduct()
    {
        // $product = Product::paginate(9);
        $product = Product::where('is_active', 1)->with("catalogue")->get();
        $catalogue = Catalogue::all();

        return response()->json([
            'data' => [
                'product' => $product,
                'catalogue' => $catalogue,
            ],
            'message' => 'Danh sách sản phẩm thành công.',
        ]);
    }
}
