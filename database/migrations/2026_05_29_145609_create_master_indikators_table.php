<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_indikators', function (Blueprint $table) {

            $table->id();

            $table->foreignId('kategori_id')
                ->constrained('master_kategoris')
                ->onDelete('cascade');

            $table->string('kode_indikator');

            $table->text('indikator');

            $table->double('bobot')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_indikators');
    }
};