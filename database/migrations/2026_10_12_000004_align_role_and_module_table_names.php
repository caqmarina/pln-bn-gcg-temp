<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('roles') && ! Schema::hasTable('master_roles')) {
            Schema::rename('roles', 'master_roles');
        }

        if (Schema::hasTable('modules') && ! Schema::hasTable('master_modules')) {
            Schema::rename('modules', 'master_modules');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('master_roles') && ! Schema::hasTable('roles')) {
            Schema::rename('master_roles', 'roles');
        }

        if (Schema::hasTable('master_modules') && ! Schema::hasTable('modules')) {
            Schema::rename('master_modules', 'modules');
        }
    }
};
