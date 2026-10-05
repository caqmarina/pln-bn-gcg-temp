<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterRoleModel extends Model
{
    protected $table = 'master_roles';

    protected $fillable = [
        'nama_role',
    ];
}