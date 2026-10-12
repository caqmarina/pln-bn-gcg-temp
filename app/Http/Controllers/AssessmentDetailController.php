<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AssessmentModel;
use App\Models\AssessmentDetailModel;
use App\Models\MasterKategoriModel;
use App\Models\MasterIndikatorModel;
use App\Services\AssessmentScoreService;
use Illuminate\Support\Facades\File;

class AssessmentDetailController extends Controller
{
   public function __construct(private AssessmentScoreService $scoreService)
   {
   }

   public function index($assessment_id)
{
    $assessment = AssessmentModel::findOrFail($assessment_id);

    /*
    |--------------------------------------------------------------------------
    | CHECK DETAIL
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | AUTO GENERATE DETAIL
    |--------------------------------------------------------------------------
    */

    $kategoris = MasterKategoriModel::where(
        'framework_id',
        $assessment->framework_id
    )->get();

    foreach ($kategoris as $kategori) {
        $indikators = MasterIndikatorModel::where('kategori_id', $kategori->id)->get();

        foreach ($indikators as $indikator) {
            AssessmentDetailModel::firstOrCreate(
                [
                    'assessment_id' => $assessment->id,
                    'indikator_id' => $indikator->id,
                ],
                [
                    'framework_id' => $assessment->framework_id,
                    'kategori_id' => $kategori->id,
                    'eviden' => null,
                    'bobot' => 0,
                    'pemenuhan' => 'Tidak',
                ]
            );
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

    $summary = $this->scoreService->summarize($data);
    $assessment->update(['skor' => round((float) $summary->sum('total_skor'), 2)]);
    $roleName = strtolower((string) auth()->user()?->role?->nama_role);
    $canUpdate = in_array($roleName, ['admin', 'assessor', 'tim gcg'], true);

    return view(
        'content.assessment_detail.index',
        compact(
            'assessment',
            'data',
            'summary',
            'canUpdate'
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
        $request->validate([
            'pemenuhan' => ['required', 'in:N/A,Tidak,Ya'],
        ]);

        $data = AssessmentDetailModel::findOrFail($id);

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

            'pemenuhan' => $request->pemenuhan,
        ]);


        
        /*
        |--------------------------------------------------------------------------
        | UPDATE TOTAL SKOR
        |--------------------------------------------------------------------------
        */

        $this->scoreService->recalculate(
            AssessmentModel::findOrFail($data->assessment_id)
        );

        return redirect()->back();
    }
}
