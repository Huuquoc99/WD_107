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
}
