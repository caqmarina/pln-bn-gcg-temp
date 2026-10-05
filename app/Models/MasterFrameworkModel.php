<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterFrameworkModel extends Model
{
    protected $table = 'master_frameworks';

    protected $fillable = [
        'nama_framework',
        'kode_framework',
        'versi',
        'tahun_berlaku',
        'status',
    ];

    public function kategoris()
    {
        return $this->hasMany(
            MasterKategoriModel::class,
            'framework_id'
        );
    }
}