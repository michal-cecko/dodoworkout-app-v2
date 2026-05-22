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
        Schema::table('form_fields', function (Blueprint $table) {
            $table->boolean("is_required")->default(false);
            $table->string("min")->nullable()->change();
            $table->string("max")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_fields', function (Blueprint $table) {
            $table->string("min")->nullable()->change();
            $table->string("max")->nullable()->change();
            $table->dropColumn([
                'is_required',
            ]);
        });
    }
};
