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
            'message' => 'List of successful products.',
        ]);
    }


    public function listProductsByCategory($id)
{
    // Lấy danh mục theo ID
    $catalogue = Catalogue::find($id);

    if (!$catalogue) {
        return response()->json(['error' => 'Category does not exist.'], 404);
    }

    $product = Product::where('catalogue_id', $id)
                       ->where('is_active', 1)
                       ->paginate(9);

    return response()->json([
        'data' => $product,
        'message' => 'List of products by category successfully.',
    ]);
}

}
