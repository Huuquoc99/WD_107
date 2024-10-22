<?php

use App\Models\User;
use App\Models\StatusOrder;
use App\Models\StatusPayment;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(model: User::class)->constrained();
            $table->string("user_name", 255);
            $table->string("user_email", 255);
            $table->string("user_phone", 255);
            $table->string("user_address", 255);
            $table->string("user_note", 255)->nullable();
            $table->tinyInteger("is_ship_user_same_user")->default(1);
            $table->string("ship_user_name", 255)->nullable();
            $table->string("ship_user_email", 255)->nullable();
            $table->string("ship_user_phone", 255)->nullable();
            $table->string("ship_user_address", 255)->nullable();
            $table->string("ship_user_note", 255)->nullable();
            $table->foreignIdFor(model: StatusOrder::class)->constrained();
            $table->foreignIdFor(model: StatusPayment::class)->constrained();
            $table->double("total_price", 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
