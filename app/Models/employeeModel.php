<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class employeeModel extends Authenticatable
{
    protected $table = 'employees';

    protected $fillable = [
        'nama',
        'nip',
<<<<<<< HEAD
        'Jabatan',
        'tanggal_lahir',
=======
>>>>>>> 10c3b1e (Add admin role access control)
        'direktorat',
        'bidang',
        'email',
        'role_id',
        'password',
    ];

    public function role()
    {
        return $this->belongsTo(MasterRoleModel::class, 'role_id');
    }

    protected $hidden = [
        'password',
    ];
}
