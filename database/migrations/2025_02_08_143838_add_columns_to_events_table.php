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
        Schema::table('events', function (Blueprint $table) {
            $table->enum('locale_scope', ['SK', 'EN'])->nullable();
            $table->boolean("vat_included")->default(false);
            $table->foreignId("form_id")->nullable();
            $table->foreign("form_id")->references("id")->on("forms")->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if(app()->runningUnitTests()) {
                $table->dropForeign("form_id");
            }

            $table->dropColumn([
                'locale_scope',
                'vat_included',
                'form_id',
            ]);
        });
    }
};
