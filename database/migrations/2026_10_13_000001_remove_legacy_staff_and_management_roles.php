<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $legacyRoleIds = DB::table('master_roles')
            ->whereIn('nama_role', ['Staff', 'Manajemen'])
            ->pluck('id');

        if ($legacyRoleIds->isEmpty()) {
            return;
        }

        // Preserve access for any old accounts by treating them as view-only users.
        $userRoleId = DB::table('master_roles')
            ->where('nama_role', 'User')
            ->value('id');

        if ($userRoleId) {
            DB::table('employees')
                ->whereIn('role_id', $legacyRoleIds)
                ->update(['role_id' => $userRoleId]);
        } else {
            DB::table('employees')
                ->whereIn('role_id', $legacyRoleIds)
                ->update(['role_id' => null]);
        }

        DB::table('role_permissions')->whereIn('role_id', $legacyRoleIds)->delete();
        DB::table('master_roles')->whereIn('id', $legacyRoleIds)->delete();
    }

    public function down(): void
    {
        DB::table('master_roles')->insertOrIgnore([
            ['nama_role' => 'Staff', 'created_at' => now(), 'updated_at' => now()],
            ['nama_role' => 'Manajemen', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
};
