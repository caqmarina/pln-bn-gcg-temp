<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterRoleModel extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'nama_role',
    ];
}
