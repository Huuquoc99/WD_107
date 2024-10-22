<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listBanner = Banner::all();
        return response()->json($listBanner, 201);
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
        if($request->isMethod("POST"))
        {
            $param = $request->except("_token");

            if($request->hasFile("image"))
            {
                $filepath = $request->file("image")->store("uploads/banners", "public");
            }else{
                $filepath = null;
            }

            $param["image"] = $filepath;
            Banner::create($param);

            return response()->json(['message' => 'Banner created successfully']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $banner = Banner::query()->findOrFail($id);
        return response()->json($banner);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $banner = Banner::findOrFail($id);
        return response()->json($banner);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if($request->isMethod("PUT"))
        {
            $param = $request->except("_token", "_method");
            $banner = Banner::findOrFail($id);
            if($request->hasFile("image")){
                if($banner->image && Storage::disk("public")->exists($banner->image))
                {
                    Storage::disk("public")->delete($banner->image);
                }
                $filepath = $request->file("image")->store("uploads/banners", "public");
            }else{
                $filepath = $banner->image;
            }

            $param["image"] = $filepath;
            $banner->update($param);

            return response()->json(['message' => 'Banner updated successfully']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();
        if($banner->image && Storage::disk("public")->exists($banner->image))
        {
            Storage::disk("public")->delete($banner->image);
        }

        return response()->json(['message' => 'Banner deleted successfully']);
    }
}
