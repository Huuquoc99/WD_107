<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductColor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductColorRequest;

class ProductColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listProductColor = ProductColor::all();
        return response()->json($listProductColor, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductColorRequest $request)
    {
        if ($request->isMethod("POST")) {
            $param = $request->except("_token");
        
            ProductColor::create($param);
        
            return response()->json(['message' => 'Product Color created successfully']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $productColor = ProductColor::query()->findOrFail($id);
        return response()->json($productColor);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $productColor = ProductColor::findOrFail($id);
        return response()->json($productColor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductColorRequest $request, string $id)
    {
        $param = $request->except("_token", "_method");
    
        $productColor = ProductColor::findOrFail($id);
        $productColor->update($param);
    
        return response()->json([
            'message' => 'Product Color updated successfully',
            'data' => $productColor
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
