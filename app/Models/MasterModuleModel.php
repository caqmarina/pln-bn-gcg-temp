<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterModuleModel extends Model
{
    protected $table = 'master_modules';

    protected $fillable = [
        'nama_module',
        'slug',
    ];
}