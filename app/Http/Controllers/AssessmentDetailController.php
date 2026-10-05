<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AssessmentModel;
use App\Models\AssessmentDetailModel;
use App\Models\MasterKategoriModel;
use App\Models\MasterIndikatorModel;
use Illuminate\Support\Facades\File;

class AssessmentDetailController extends Controller
{
   public function index($assessment_id)
{
    $assessment = AssessmentModel::findOrFail($assessment_id);

    /*
    |--------------------------------------------------------------------------
    | CHECK DETAIL
    |--------------------------------------------------------------------------
    */

    $check = AssessmentDetailModel::where(
        'assessment_id',
        $assessment_id
    )->count();

    /*
    |--------------------------------------------------------------------------
    | AUTO GENERATE DETAIL
    |--------------------------------------------------------------------------
    */

    if ($check == 0) {

        // ambil kategori berdasarkan framework
        $kategoris = MasterKategoriModel::where(
            'framework_id',
            $assessment->framework_id
        )->get();

        foreach ($kategoris as $kategori) {

            // ambil indikator berdasarkan kategori
            $indikators = MasterIndikatorModel::where(
                'kategori_id',
                $kategori->id
            )->get();

            foreach ($indikators as $indikator) {

                AssessmentDetailModel::create([

                    'assessment_id' => $assessment->id,

                    'framework_id' => $assessment->framework_id,

                    'kategori_id' => $kategori->id,

                    'indikator_id' => $indikator->id,

                    'eviden' => null,

                    'bobot' => 0,
                ]);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | GET DATA
    |--------------------------------------------------------------------------
    */

    $data = AssessmentDetailModel::with([
        'framework',
        'kategori',
        'indikator'
    ])
    ->where('assessment_id', $assessment_id)
    ->get();

    return view(
        'content.assessment_detail.index',
        compact(
            'assessment',
            'data'
        )
    );
}

    /*
    |--------------------------------------------------------------------------
    | UPDATE EVIDEN & BOBOT
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $data = AssessmentDetailModel::findOrFail($id);

        $data->update([
            'eviden' => $request->eviden,
            'bobot' => $request->bobot,
        ]);

         /*
    |--------------------------------------------------------------------------
    | UPLOAD FILE
    |--------------------------------------------------------------------------
    */

       $fileName = $data->dokumen_softcopy;

        if ($request->hasFile('dokumen_softcopy')) {

            // hapus file lama jika ada
            $oldFile = public_path('uploads/eviden_assessment/' . $data->dokumen_softcopy);

            if (File::exists($oldFile)) {
                File::delete($oldFile);
            }

            // upload file baru
            $file = $request->file('dokumen_softcopy');

            $fileName = time() . '_' . $file->getClientOriginalName();

            $file->move(
                public_path('uploads/eviden_assessment/'),
                $fileName
            );
        }

     /*
    |--------------------------------------------------------------------------
    | UPDATE DATA
    |--------------------------------------------------------------------------
    */

        $data->update([

            'eviden' => $request->eviden,

            'dokumen_softcopy' => $fileName,

            'bobot' => $request->bobot,
        ]);


        
        /*
        |--------------------------------------------------------------------------
        | UPDATE TOTAL SKOR
        |--------------------------------------------------------------------------
        */

        $totalSkor = AssessmentDetailModel::where(
            'assessment_id',
            $data->assessment_id
        )->sum('bobot');

        AssessmentModel::where(
            'id',
            $data->assessment_id
        )->update([
            'skor' => $totalSkor
        ]);

        return redirect()->back();
    }
}