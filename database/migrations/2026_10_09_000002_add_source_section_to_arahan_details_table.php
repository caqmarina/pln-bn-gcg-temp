<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('arahan_details', function (Blueprint $table) {
            $table->string('source_section', 40)->nullable()->after('source_level');
        });
    }

    public function down(): void
    {
        Schema::table('arahan_details', function (Blueprint $table) {
            $table->dropColumn('source_section');
        });
    }
};
