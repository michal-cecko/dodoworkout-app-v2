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
        Schema::create('form_submission_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId("form_submission_id")->nullable();
            $table->foreign("form_submission_id")->references("id")->on("form_submissions")->nullOnDelete();
            $table->foreignId("form_field_id")->nullable();
            $table->foreign("form_field_id")->references("id")->on("form_fields")->nullOnDelete();
            $table->text("value");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submission_fields');
    }
};
