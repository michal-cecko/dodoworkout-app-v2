<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('CREATE SEQUENCE order_number_seq START 1;');

        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string("email");
            $table->string("company_name")->nullable();
            $table->string("billing_first_name")->nullable();
            $table->string("billing_last_name")->nullable();
            $table->string("billing_address");
            $table->string("billing_city");
            $table->string("billing_zip");
            $table->string("billing_country");
            $table->string("billing_phone");

            $table->boolean("is_company")->default(false);
            $table->string("business_id")->nullable();
            $table->string("tax_id")->nullable();
            $table->string("vat_id")->nullable();

            $table->enum("status", ['FREE', 'ACCEPTED', 'CANCELED', 'PAID'])->default('ACCEPTED');
            $table->unsignedInteger('order_number')->default(DB::raw("nextval('order_number_seq')"));

            $table->decimal("subtotal");
            $table->decimal("discount_amount")->nullable();
            $table->decimal("shipping_type_price");
            $table->decimal("payment_type_price");
            $table->decimal("total_no_vat");
            $table->decimal("vat_percentage");
            $table->decimal("vat_amount");
            $table->decimal("total_with_vat");

            $table->boolean("is_shipping_address")->default(false);
            $table->string("shipping_first_name")->nullable();
            $table->string("shipping_last_name")->nullable();
            $table->string("shipping_address")->nullable();
            $table->string("shipping_city")->nullable();
            $table->string("shipping_zip")->nullable();
            $table->string("shipping_country")->nullable();
            $table->string("shipping_phone")->nullable();

            $table->foreignId("user_id")->nullable();
            $table->foreign("user_id")->references("id")->on("users")->nullOnDelete();
            $table->text("note")->nullable();

            $table->string("payment_type_label");
            $table->foreignId("payment_type_id")->nullable();
            $table->foreign("payment_type_id")->references("id")->on("payment_types")->nullOnDelete();

            $table->string("shipping_type_label");
            $table->foreignId("shipping_type_id")->nullable();
            $table->foreign("shipping_type_id")->references("id")->on("shipping_types")->nullOnDelete();

            $table->timestamp("canceled_at")->nullable();
            $table->timestamp("paid_at")->nullable();
            $table->timestamps();
        });

        // Optionally, you can set the sequence to be owned by the order_number column
        DB::statement('ALTER SEQUENCE order_number_seq OWNED BY orders.order_number;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');

        // Drop the sequence when rolling back the migration
        DB::statement('DROP SEQUENCE IF EXISTS order_number_seq;');
    }
};
