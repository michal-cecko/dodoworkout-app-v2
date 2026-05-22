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
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->foreignId("order_item_id")->nullable();
            $table->foreign("order_item_id")->references("id")->on("order_items")->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            if(!app()->runningUnitTests()) {
                $table->dropForeign("form_submissions_order_item_id_foreign");
            }
            $table->dropColumn([
                'order_item_id',
            ]);
        });
    }
};
