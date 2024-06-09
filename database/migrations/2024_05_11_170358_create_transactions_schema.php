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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('fitid')->nullable();
            $table->integer('amount');
            $table->string('memo')->nullable();
            $table->string('currency', 3)->default('BRL');
            $table->string('account_number', 8)->nullable();
            $table->timestamp('date_posted')->default(now());
            $table->foreignUuid('account_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('transactions');
    }
};
