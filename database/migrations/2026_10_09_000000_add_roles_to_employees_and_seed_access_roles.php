<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('role_id')
                ->nullable()
                ->after('password')
                ->constrained('roles')
                ->nullOnDelete();
        });

        foreach (['Admin', 'Assessor', 'Tim GCG', 'User'] as $roleName) {
            DB::table('roles')->updateOrInsert(['nama_role' => $roleName]);
        }

        // Preserve access for the original account while new accounts are assigned explicitly.
        $firstEmployeeId = DB::table('employees')->orderBy('id')->value('id');
        $adminRoleId = DB::table('roles')->where('nama_role', 'Admin')->value('id');

        if ($firstEmployeeId && $adminRoleId) {
            DB::table('employees')->where('id', $firstEmployeeId)->update(['role_id' => $adminRoleId]);
        }
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
        });
    }
};
