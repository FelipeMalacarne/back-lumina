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
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 50);
            $table->string('type', 20)->nullable();
            $table->string('number', 16)->nullable();
            $table->string('check_digit', 1)->nullable();
            $table->integer('balance')->default(0);
            $table->string('description', 255)->nullable();
            $table->string('color', 20)->default('lavender');
            $table->string('bank_id', 3);

            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
