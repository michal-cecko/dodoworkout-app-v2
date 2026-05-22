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
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId("form_id")->nullable();
            $table->foreign("form_id")->references("id")->on("forms")->nullOnDelete();
            $table->foreignId("user_id")->nullable();
            $table->foreign("user_id")->references("id")->on("users")->nullOnDelete();
            $table->morphs("priceable");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
    }
};
