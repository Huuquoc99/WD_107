<?php

use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(model: Order::class)->constrained();
            $table->foreignIdFor(model: ProductVariant::class)->constrained();
            $table->integer("quantity")->default(0);
            $table->string("product_name", 255);
            $table->string("product_sku", 255);
            $table->string(column: "product_img_thumbnail");
            $table->double("product_price_regular");
            $table->double("product_price_sale");
            $table->string("variant_capacity_name", 255);
            $table->string("variant_color_name", 255);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
