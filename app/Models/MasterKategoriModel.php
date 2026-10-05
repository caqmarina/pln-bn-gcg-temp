<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKategoriModel extends Model
{
    protected $table = 'master_kategoris';

    protected $fillable = [
        'framework_id',
        'nama_kategori',
        'bobot',
    ];

    public function framework()
    {
        return $this->belongsTo(
            MasterFrameworkModel::class,
            'framework_id'
        );
    }

    public function indikators()
    {
        return $this->hasMany(
            MasterIndikatorModel::class,
            'kategori_id'
        );
    }
}