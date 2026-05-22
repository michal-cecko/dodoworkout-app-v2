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
        Schema::table('payment_types', function (Blueprint $table) {
            $table->enum("type", ['COD', 'BANK_TRANSFER', 'CARD'])->nullable();
        });

        Schema::table('shipping_types', function (Blueprint $table) {
            $table->enum("type", ['EMAIL', 'COURIER', 'PERSON'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_types', function (Blueprint $table) {
            $table->dropColumn([
                'type',
            ]);
        });

        Schema::table('payment_types', function (Blueprint $table) {
            $table->dropColumn([
                'type',
            ]);
        });
    }
};
