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
        Schema::create('role_permissions', function (Blueprint $table) {

        $table->id();

        $table->foreignId('role_id');

        $table->foreignId('module_id');

        $table->boolean('can_create')->default(false);

        $table->boolean('can_read')->default(false);

        $table->boolean('can_update')->default(false);

        $table->boolean('can_delete')->default(false);

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
