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
    public function list()
    {
        $totalAmount = 0;
        $unifiedCart = [];
        $sessionCart = session()->get('cart', []);

        if (Auth::check()) {
            $dbCart = Cart::with(['items.productVariant.product'])
                ->where('user_id', Auth::id())
                ->first();

            if ($dbCart) {
                foreach ($dbCart->items as $item) {
                    $product = $item->productVariant->product;
                    $productVariant = $item->productVariant;

                    $unifiedCart[$productVariant->id] = [
                        'product_variant_id' => $productVariant->id,
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price_sale ?? $product->price,
                        'quantity' => $item->quantity,
                        'color' => $productVariant->color->name,
                        'size' => $productVariant->capacity->name,
                        'image' => $productVariant->image
                    ];

                    $totalAmount += $item->quantity * ($product->price_sale ?? $product->price);
                }
                // Thêm giỏ hàng session và db
                if (!empty($sessionCart)) {
                    foreach ($sessionCart as $item) {
                        $cartItem = CartItem::query()->where([
                            'cart_id' => $dbCart->id,
                            'product_variant_id' => $item['product_variant_id']
                        ])->first();

                        if ($cartItem) {
                            $cartItem->quantity += $item['quantity'];
                            $cartItem->save();
                            // Cập nhật số lượng
                            if (isset($unifiedCart[$item['product_variant_id']])) {
                                $unifiedCart[$item['product_variant_id']]['quantity'] += $item['quantity'];
                            }
                        } else {
                            CartItem::query()->create([
                                'cart_id' => $dbCart->id,
                                'product_variant_id' => $item['product_variant_id'],
                                'quantity' => $item['quantity'],
                                'price' => $item['price']
                            ]);

                            $unifiedCart[$item['product_variant_id']] = $item;
                        }
                        $totalAmount += $item['quantity'] * $item['price'];
                    }
                    // Xóa session sau khi đã lưu vào db
                    session()->forget('cart');
                }
            } else {
                // Tạo mới giỏ hàng trong db nếu giỏ hàng db trống
                if (!empty($sessionCart)) {
                    $dbCart = Cart::query()->create([
                        'user_id' => Auth::id()
                    ]);

                    foreach ($sessionCart as $item) {
                        CartItem::query()->create([
                            'cart_id' => $dbCart->id,
                            'product_variant_id' => $item['product_variant_id'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price']
                        ]);

                        $unifiedCart[$item['product_variant_id']] = $item;
                        $totalAmount += $item['quantity'] * $item['price'];
                    }

                    session()->forget('cart');
                }
            }
        } else {
            $unifiedCart = $sessionCart;
            foreach ($sessionCart as $item) {
                $totalAmount += $item['quantity'] * $item['price'];
            }
        }

        return response()->json([
            'unifiedCart' => $unifiedCart,
            'totalAmount' => $totalAmount,
        ], 200);
    }


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
