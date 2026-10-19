<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArahanModel extends Model
{
    protected $table = 'arahan';

    protected $fillable = [
        'judul_arahan',
        'tanggal_arahan',
        'progress'
    ];

    protected $casts = [
        'tanggal_arahan' => 'date',
    ];

    public function details()
    {
        return $this->hasMany(
            ArahanDetailModel::class,
            'arahan_id'
        );
    }
}
