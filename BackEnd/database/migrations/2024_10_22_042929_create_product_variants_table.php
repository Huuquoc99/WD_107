<?php

use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductCapacity;
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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(model: Product::class)->constrained();
            $table->foreignIdFor(model: ProductCapacity::class)->constrained();
            $table->foreignIdFor(model: ProductColor::class)->constrained();
            $table->unsignedInteger('quantity')->default(0);
            $table->string('image', 255)->nullable();
            $table->string("sku", 255)->nullable();
            $table->double("price")->nullable();
            $table->tinyInteger("status")->default(1);
            $table->timestamps();
            $table->unique(['product_id', 'product_capacity_id', 'product_color_id'], 'product_variant_unique');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
