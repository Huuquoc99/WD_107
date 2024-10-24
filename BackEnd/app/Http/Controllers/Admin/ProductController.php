<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Catalogue;
use App\Models\Product;
use App\Models\ProductCapacity;
use App\Models\ProductColor;
use App\Models\ProductGallery;
use App\Models\ProductVariant;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Product::query()->latest('id')->paginate(8);

        return response()->json([
            'message' => 'Sản phẩm đã được lấy thành công.',
            'data' => $data
        ], 200);
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
    public function store(StoreProductRequest $request)
    {
        list(
            $dataProduct,
            $dataProductVariants,
            $dataProductGalleries,
            $dataProductTags
            ) = $this->handleData($request);

        try {
            DB::beginTransaction();

            /** @var Product $product */
            $product = Product::query()->create($dataProduct);

            foreach ($dataProductVariants as $item) {
                $item += ['product_id' => $product->id];

                ProductVariant::query()->create($item);
            }

            $product->tags()->attach($dataProductTags);

            foreach ($dataProductGalleries as $item) {
                $item += ['product_id' => $product->id];

                ProductGallery::query()->create($item);
            }

            DB::commit();

            return response()->json([
                'message' => 'Sản phẩm đã được tạo thành công.',
                'data' => $product->load(['variants', 'tags', 'galleries'])
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi tạo sản phẩm.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['variants', 'galleries', 'tags']);

        return response()->json([
            'message' => 'Sản phẩm đã được lấy thành công.',
            'data' => $product
        ], 200);
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
    public function update(Request $request, Product $product)
    {
        list(
            $dataProduct,
            $dataProductVariants,
            $dataProductGalleries,
            $dataProductTags
            ) = $this->handleData($request);

        try {
            DB::beginTransaction();

            $productImgThumbnailCurrent = $product->img_thumbnail;

            $product->update($dataProduct);

            $product->tags()->sync($dataProductTags);

            foreach ($dataProductVariants as  $item) {
                $existingVariant = ProductVariant::query()->where([
                    'product_id' => $product->id,
                    'product_capacity_id' => $item['product_capacity_id'],
                    'product_color_id' => $item['product_color_id'],
                ])->first();

                if ($existingVariant) {
                    if (empty($item['image'])) {
                        $item['image'] = $existingVariant->image;
                    }
                    $existingVariant->update($item);
                }
            }

            foreach ($dataProductGalleries as $item) {
                $item += ['product_id' => $product->id];
                ProductGallery::query()->updateOrCreate(
                    [
                        'id' => $item['id']
                    ],
                    $item
                );
            }

            DB::commit();

            if (!empty($dataProduct['img_thumbnail']) && $dataProduct['img_thumbnail'] !== $productImgThumbnailCurrent) {
                if (!empty($productImgThumbnailCurrent) && Storage::exists($productImgThumbnailCurrent)) {
                    Storage::delete($productImgThumbnailCurrent);
                }
            }

            return response()->json([
                'data' => $product->load(['variants', 'tags', 'galleries']),
                'message' => 'Cập nhật sản phẩm thành công',
                'status' => 'success'
            ], 200);

        } catch (\Exception $exception) {
            DB::rollBack();

            foreach ($dataProductGalleries as $item) {
                if (!empty($item['image']) && Storage::exists($item['image'])) {
                    Storage::delete($item['image']);
                }
            }

            foreach ($dataProductVariants as $item) {
                if (!empty($item['image']) && Storage::exists($item['image'])) {
                    Storage::delete($item['image']);
                }
            }

            return response()->json([
                'message' => 'Đã xảy ra lỗi: ' . $exception->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $dataHasImage = $product->galleries->toArray() + $product->variants->toArray();

            DB::transaction(function () use ($product) {
                $product->tags()->sync([]);

                $product->galleries()->delete();

                foreach ($product->variants as $variant) {
                    $variant->orderItems()->delete();
                }
                $product->variants()->delete();

                $product->delete();
            }, 3);

            foreach ($dataHasImage as $item) {
                if (!empty($item->image) && Storage::exists($item->image)) {
                    Storage::delete($item->image);
                }
            }

            return response()->json([
                'message' => 'Xóa sản phẩm thành công',
                'status' => 'success'
            ], 200);
        } catch (\Exception $exception) {

            return response()->json([
                'message' => 'Đã xảy ra lỗi: ' . $exception->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }

    private function handleData(Request $request)
    {
        $dataProduct = $request->except(['product_variants', 'tags', 'product_galleries']);
        $dataProduct['is_active'] ??= 0;
        $dataProduct['is_hot_deal'] ??= 0;
        $dataProduct['is_good_deal'] ??= 0;
        $dataProduct['is_new'] ??= 0;
        $dataProduct['is_show_home'] ??= 0;
        $dataProduct['slug'] = Str::slug($dataProduct['name']) . '-' . $dataProduct['sku'];

        if (!empty($dataProduct['img_thumbnail'])) {
            $dataProduct['img_thumbnail'] = Storage::put('products', $dataProduct['img_thumbnail']);
        }

        $dataProductVariantsTmp = $request->product_variants;
        $dataProductVariants = [];
        foreach ($dataProductVariantsTmp as $key => $item) {
            $tmp = explode('-', $key);
            $dataProductVariants[] = [
                'product_capacity_id' => $tmp[0],
                'product_color_id' => $tmp[1],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'sku' => $item['sku'],
                'status' => isset($item['status']) && $item['status'] == 1 ? 1 : 0,
                'image' => !empty($item['image']) ? Storage::put('product_variants', $item['image']) : null
            ];
        }

        $dataProductGalleriesTmp = $request->product_galleries ?: [];
        $dataProductGalleries = [];
        foreach ($dataProductGalleriesTmp as $image) {
            if (!empty($image)) {
                $dataProductGalleries[] = [
                    'image' => Storage::put('product_galleries', $image)
                ];
            }
        }

        $dataProductTags = $request->tags;

        return [$dataProduct,$dataProductVariants, $dataProductGalleries, $dataProductTags];
    }
}
