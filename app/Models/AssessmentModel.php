<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentModel extends Model
{
    protected $table = 'assessments';

    protected $fillable = [
        'jenis_asesmen',
        'tahun_asesmen',
        'tahun_buku',
        'framework_id',
        'skor',
    ];

    public function framework()
    {
        return $this->belongsTo(
            MasterFrameworkModel::class,
            'framework_id'
        );
    }
}