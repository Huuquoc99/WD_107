<?php

namespace App\Http\Controllers\Client;

use App\Models\Banner;
use App\Models\Product;
use App\Models\ProductColor;
use Illuminate\Http\Request;
use App\Models\ProductCapacity;
use App\Http\Controllers\Controller;
use App\Models\Catalogue;

class HomeController extends Controller
{
    
    public function index()
    {
        $productActive = Product::with(['variants', 'galleries'])
            ->where('is_active', 1)
            ->get();

        $productHot = Product::with(['variants', 'galleries'])
            ->where('is_hot_deal', 1)
            ->get();

        $productGood = Product::with(['variants', 'galleries'])
            ->where('is_good_deal', 1)
            ->get();

        $productNew = Product::with(['variants', 'galleries'])
            ->where('is_new', 1)
            ->get();

        $productHome = Product::with(['variants', 'galleries'])
            ->where('is_show_home', 1)
            ->get();

        
        $catalogues = Catalogue::where('is_active', 1)->get();

        $banners = Banner::where('is_active', 1)->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'is_active' => $productActive,
                'is_hot_deal' => $productHot,
                "is_good_deal" => $productGood,
                "is_new" => $productNew,
                "is_show_home" => $productHome,
                'catalogues' => $catalogues,
                'banners' => $banners,
            ],
        ], 200);
    }
}
