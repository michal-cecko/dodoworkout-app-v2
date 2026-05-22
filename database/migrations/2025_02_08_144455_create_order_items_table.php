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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_id");
            $table->foreign("order_id")->references("id")->on("orders")->cascadeOnDelete();
            $table->morphs("orderable");
            $table->string("name");
            $table->unsignedInteger("quantity")->default(1);

            $table->decimal("price_per_unit");
            $table->decimal("discount_amount_per_unit")->nullable();

            $table->decimal("total_no_vat");
            $table->decimal("vat_percentage");
            $table->decimal("vat_amount");
            $table->decimal("total_with_vat");

            $table->timestamps();
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
