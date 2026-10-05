<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArahanDetailModel extends Model
{
    protected $table = 'arahan_details';

    protected $fillable = [

        'arahan_id',
        'aspek',
        'arahan',
        'tindak_lanjut',
        'status',
        'eviden',

    ];

    public function arahan()
    {
        return $this->belongsTo(
            ArahanModel::class,
            'arahan_id'
        );
    }
}