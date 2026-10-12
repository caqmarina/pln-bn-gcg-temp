<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $modules = [
            ['nama_module' => 'Assessment', 'slug' => 'assessment'],
            ['nama_module' => 'Arahan', 'slug' => 'arahan'],
            ['nama_module' => 'Employee', 'slug' => 'employee'],
            ['nama_module' => 'Master Framework', 'slug' => 'master_framework'],
            ['nama_module' => 'Master Kategori', 'slug' => 'master_kategori'],
            ['nama_module' => 'Master Indikator', 'slug' => 'master_indikator'],
            ['nama_module' => 'Master Role', 'slug' => 'master_role'],
            ['nama_module' => 'Master Module', 'slug' => 'master_module'],
            ['nama_module' => 'Role Permission', 'slug' => 'role_permission'],
        ];

        foreach ($modules as $module) {
            DB::table('master_modules')->updateOrInsert(
                ['slug' => $module['slug']],
                ['nama_module' => $module['nama_module'], 'updated_at' => now(), 'created_at' => now()]
            );
        }

        $roles = ['Admin', 'Assessor', 'Tim GCG', 'User'];
        foreach ($roles as $roleName) {
            DB::table('master_roles')->updateOrInsert(
                ['nama_role' => $roleName],
                ['updated_at' => now(), 'created_at' => now()]
            );
        }

        $allModules = DB::table('master_modules')->pluck('id', 'slug');
        $permissions = [
            'Assessor' => [
                'assessment' => ['read' => 1, 'update' => 1],
                'arahan' => ['read' => 1],
            ],
            'Tim GCG' => [
                'assessment' => ['read' => 1, 'create' => 1, 'update' => 1],
                'arahan' => ['read' => 1],
            ],
            'User' => [
                'assessment' => ['read' => 1],
                'arahan' => ['read' => 1],
            ],
        ];

        foreach ($permissions as $roleName => $modulePermissions) {
            $roleId = DB::table('master_roles')->where('nama_role', $roleName)->value('id');

            foreach ($modulePermissions as $slug => $actions) {
                $moduleId = $allModules[$slug] ?? null;
                if (! $moduleId) {
                    continue;
                }

                DB::table('role_permissions')->updateOrInsert(
                    ['role_id' => $roleId, 'module_id' => $moduleId],
                    [
                        'can_create' => $actions['create'] ?? 0,
                        'can_read' => $actions['read'] ?? 0,
                        'can_update' => $actions['update'] ?? 0,
                        'can_delete' => $actions['delete'] ?? 0,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        foreach (['Assessor', 'Tim GCG', 'User'] as $roleName) {
            $roleId = DB::table('master_roles')->where('nama_role', $roleName)->value('id');
            if ($roleId) {
                DB::table('role_permissions')->where('role_id', $roleId)->delete();
            }
        }
    }
};
