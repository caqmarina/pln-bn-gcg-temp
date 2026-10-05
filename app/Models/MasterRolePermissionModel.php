<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterRolePermissionModel extends Model
{
    protected $table = 'role_permissions';

    protected $fillable = [

        'role_id',
        'module_id',

        'can_create',
        'can_read',
        'can_update',
        'can_delete',
    ];

    public function role()
    {
        return $this->belongsTo(
            MasterRoleModel::class,
            'role_id'
        );
    }

    public function module()
    {
        return $this->belongsTo(
            MasterModuleModel::class,
            'module_id'
        );
    }
}