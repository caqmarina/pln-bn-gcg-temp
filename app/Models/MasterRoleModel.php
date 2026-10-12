<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterRoleModel extends Model
{
    // The SQL dump defines this table as `master_roles`.
    protected $table = 'master_roles';

    protected $fillable = [
        'nama_role',
    ];
}
