<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartControler extends Controller
{
    public function addToCart(AddToCartRequest $request)
    {
        try {
            DB::beginTransaction();

            $product = Product::query()->findOrFail($request->product_id);
            $productVariant = ProductVariant::query()
                ->with(['color', 'capacity'])
                ->where([
                    'product_id' => $request->product_id,
                    'product_capacity_id' => $request->product_capacity_id,
                    'product_color_id' => $request->product_color_id,
                ])
                ->firstOrFail();


            $quantity = (int) $request->input('quantity', 0);

            $stock_quantity = $productVariant->quantity;

            if (Auth::check()) {
                $cart = Cart::query()->firstOrCreate([
                    'user_id' => Auth::id()
                ]);

                $cartItem = CartItem::where([
                    'cart_id' => $cart->id,
                    'product_variant_id' => $productVariant->id
                ])->first();

                if (!$cartItem) {
                    if ($quantity > $stock_quantity) {
                        return response()->json([
                            'error' => 'Vượt quá số lượng cho phép'
                        ], 400);
                    }

                    CartItem::query()->create([
                        'cart_id' => $cart->id,
                        'product_variant_id' => $productVariant->id,
                        'quantity' => $quantity,
                        'price' => $product->price_sale,
                    ]);
                } else {
                    if (($cartItem->quantity + $quantity) > $stock_quantity) {
                        return response()->json([
                            'error' => 'Vượt quá số lượng cho phép'
                        ], 400);
                    }

                    $cartItem->update([
                        'quantity' => $cartItem->quantity + $quantity,
                        'price' => $product->price_sale
                    ]);
                }
            }

            $cart = session()->get('cart', []);
            $cartItemKey = $productVariant->id;

            if (isset($cart[$cartItemKey])) {
                $newQuantity = $cart[$cartItemKey]['quantity'] + $quantity;

                if ($newQuantity > $stock_quantity) {
                    return response()->json([
                        'error' => 'Vượt quá số lượng cho phép'
                    ], 400);
                }

                $cart[$cartItemKey]['quantity'] = $newQuantity;
            } else {
                if ($quantity > $stock_quantity) {
                    return response()->json([
                        'error' => 'Vượt quá số lượng cho phép'
                    ], 400);
                }

                $cart[$cartItemKey] = [
                    'product_variant_id' => $productVariant->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price_sale,
                    'quantity' => $quantity,
                    'color' => $productVariant->color->name,
                    'capacity' => $productVariant->capacity->name,
                    'image' => $productVariant->image
                ];
            }

            session()->put('cart', $cart);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Thêm vào giỏ hàng thành công',
                'cart' => Auth::check() ? $cart->items : session()->get('cart')
            ], 201);

        }
        catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'Sản phẩm không tồn tại'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Có lỗi xảy ra, vui lòng thử lại',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
