<?php

namespace App\Http\Controllers\Admin;

use App\Models\Catalogue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogueRequest;

class CatalogueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $listCatalogue = Catalogue::withCount("products")->get();
        // $listCatalogue = Catalogue::all();
        return response()->json( $listCatalogue, 201);
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
    public function store(CatalogueRequest $request)
    {
         if($request->isMethod("POST"))
        {
            $param = $request->except("_token");

            if($request->hasFile("cover"))
            {
                $filepath = $request->file("cover")->store("uploads/catalogues", "public");
            }else{
                $filepath = null;
            }

            $param["cover"] = $filepath;
            Catalogue::create($param);

            return response()->json(['message' => 'Catalogue created successfully']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
