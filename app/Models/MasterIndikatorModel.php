<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterIndikatorModel extends Model
{
    protected $table = 'master_indikators';

    protected $fillable = [
        'kategori_id',
        'kode_indikator',
        'indikator',
        'bobot',
    ];

    public function kategori()
    {
        return $this->belongsTo(
            MasterKategoriModel::class,
            'kategori_id'
        );
    }
}