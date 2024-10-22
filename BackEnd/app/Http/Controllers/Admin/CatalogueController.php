<?php

namespace App\Http\Controllers\Admin;

use App\Models\Catalogue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogueRequest;
use Illuminate\Support\Facades\Storage;

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
        $catalogue = Catalogue::query()->findOrFail($id);
        return response()->json($catalogue);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $catalogue = Catalogue::findOrFail($id);
        return response()->json($catalogue);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CatalogueRequest $request, string $id)
    {
        if($request->isMethod("PUT"))
        {
            $param = $request->except("_token", "_method");
            $catalogue = Catalogue::findOrFail($id);
            if($request->hasFile("cover")){
                if($catalogue->hasFile && Storage::disk("public")->exists($catalogue->cover))
                {
                    Storage::disk("public")->delete($catalogue->cover);
                }
                $filepath = $request->file("cover")->store("uploads/catalogues", "public");
            }else{
                $filepath = $catalogue->cover;
            }

            $param["cover"] = $filepath;
            $catalogue->update($param);

            if($catalogue->is_active == 0)
            {
                $catalogue->hide();
            }else{
                $catalogue->show();
            }

            return response()->json(['message' => 'Catalogue updated successfully']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
