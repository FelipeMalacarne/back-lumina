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
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100);
            $table->string('type', 20)->default('personal');
            $table->timestamps();
        });

        Schema::create('project_user', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('project_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->index()->constrained()->cascadeOnDelete();
            $table->string('role', 20)->default('member');
            $table->timestamps();
        });

        Schema::create('account_project', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('account_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignUuid('project_id')->index()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignUuid('default_project_id')->nullable()->index()->constrained('projects')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_project');
        Schema::dropIfExists('members');
        Schema::dropIfExists('projects');
    }
};
