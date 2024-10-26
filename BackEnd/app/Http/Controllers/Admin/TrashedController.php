<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TrashedController extends Controller
{
    public function trashed()
    {
        $trashed = Product::onlyTrashed()->get();
        return response()->json($trashed);
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        return response()->json(['message' => 'Product restore successful']);
    }
}
