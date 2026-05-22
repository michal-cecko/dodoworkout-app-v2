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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->jsonb("title");
            $table->jsonb("content");
            $table->jsonb("excerpt");
            $table->jsonb("slug");
            $table->foreignId('category_id')->nullable();
            $table->foreign("category_id")->references("id")->on("event_categories")->nullOnDelete();
            $table->boolean("is_draft")->default(false);
            $table->timestamp("start_at");
            $table->timestamp("end_at")->nullable();
            $table->jsonb("address")->nullable();
            $table->boolean("has_location")->default(false);
            $table->float("latitude")->nullable();
            $table->float("longitude")->nullable();
            $table->unsignedInteger("participants_count")->nullable();
            $table->decimal("price")->nullable();
            $table->decimal("last_price")->nullable();
            /*$table->foreignId('form_id')->nullable();
            $table->foreign("form_id")->references("id")->on("forms")->nullOnDelete();*/
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
