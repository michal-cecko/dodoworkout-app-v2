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
        Schema::table('form_submission_fields', function (Blueprint $table) {
            $table->jsonb("label")->nullable();
            $table->enum("format", ['TEXT', 'EMAIL', 'BOOL', 'NUMBER', 'PHONE', 'DATE', 'TIME', 'DATETIME', 'SELECT', 'CHECKBOX', 'FILE'])->default('TEXT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_submission_fields', function (Blueprint $table) {
            $table->dropColumn('label');
            $table->dropColumn('format');
        });
    }
};
