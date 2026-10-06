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
        Schema::create('arahan_details', function (Blueprint $table) {

    $table->id();

    $table->unsignedBigInteger('arahan_id');

    $table->string('aspek');

    $table->text('arahan');

    $table->text('tindak_lanjut')
        ->nullable();

    $table->enum('status', [
        'Open',
        'Progress',
        'Review',
        'Done'
    ])->default('Open');

    $table->string('eviden')
        ->nullable();

    $table->timestamps();

    $table->foreign('arahan_id')
        ->references('id')
        ->on('arahan')
        ->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arahan_details');
    }
};
