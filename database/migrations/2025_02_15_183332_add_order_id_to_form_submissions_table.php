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
            if(!app()->runningUnitTests()) {
                $table->dropForeign("form_submissions_user_id_foreign");
            }
            $table->dropColumn([
                'user_id',
            ]);
            $table->foreignId("order_id");
            $table->foreign("order_id")->references("id")->on("orders")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            if(!app()->runningUnitTests()) {
                $table->dropForeign("form_submissions_order_id_foreign");
            }
            $table->dropColumn([
                'order_id',
            ]);
            $table->foreignId("user_id")->nullable();
            $table->foreign("user_id")->references("id")->on("users")->nullOnDelete();
        });
    }
};
