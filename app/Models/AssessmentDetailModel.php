<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentDetailModel extends Model
{
    protected $table = 'assessment_details';

    protected $fillable = [
    'assessment_id',
    'framework_id',
    'kategori_id',
    'indikator_id',
    'eviden',
    'dokumen_softcopy',
    'bobot',
    ];

    public function assessment()
    {
        return $this->belongsTo(
            AssessmentModel::class,
            'assessment_id'
        );
    }

    public function framework()
    {
        return $this->belongsTo(
            MasterFrameworkModel::class,
            'framework_id'
        );
    }

    public function kategori()
    {
        return $this->belongsTo(
            MasterKategoriModel::class,
            'kategori_id'
        );
    }

    public function indikator()
    {
        return $this->belongsTo(
            MasterIndikatorModel::class,
            'indikator_id'
        );
    }
}