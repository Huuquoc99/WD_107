<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('status_payments', function (Blueprint $table) {
            $table->id();
            $table->string("code", 50)->unique();
            $table->string("name", 255);
            $table->text("description");
            $table->integer("display_order");
            $table->boolean("is_active")->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_payments');
    }
};
