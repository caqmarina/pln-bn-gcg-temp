<?php

namespace App\Http\Middleware;

use App\Models\MasterModuleModel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    public function handle(Request $request, Closure $next, string $module, string $action = 'read'): Response
    {
        $user = $request->user();
        $role = $user?->role;

        if (! $role) {
            abort(403, 'Anda tidak memiliki role yang valid.');
        }

        if (strtolower($role->nama_role) === 'admin') {
            return $next($request);
        }

        $column = match (strtolower($action)) {
            'create' => 'can_create',
            'update' => 'can_update',
            'delete' => 'can_delete',
            default => 'can_read',
        };

        $moduleId = MasterModuleModel::where('slug', $module)->value('id');
        $allowed = $moduleId
            ? $role->permissions()->where('module_id', $moduleId)->value($column)
            : false;

        abort_unless((bool) $allowed, 403, 'Anda tidak memiliki izin untuk aksi ini.');

        return $next($request);
    }
}
