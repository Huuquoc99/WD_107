<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ProductCapacity;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCapacityRequest;

class ProductCapacityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listProductCapacity = ProductCapacity::all();
        return response()->json($listProductCapacity, 200);
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
    public function store(Request $request)
    {
        if ($request->isMethod("POST")) {
            $param = $request->except("_token");
        
            ProductCapacity::create($param);
        
            return response()->json(['message' => 'Product Capacity created successfully']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $productCapacity = ProductCapacity::query()->findOrFail($id);
        return response()->json($productCapacity);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $productCapacity = ProductCapacity::findOrFail($id);
        return response()->json($productCapacity);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductCapacityRequest $request, string $id)
    {
        $param = $request->except("_token", "_method");
    
        $productCapacity = ProductCapacity::findOrFail($id);
        $productCapacity->update($param);
    
        return response()->json([
            'message' => 'Product Capacity updated successfully',
            'data' => $productCapacity
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
