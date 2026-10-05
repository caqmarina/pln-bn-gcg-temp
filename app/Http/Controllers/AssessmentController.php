<?php

namespace App\Http\Controllers;

use App\Models\AssessmentModel;
use App\Models\MasterFrameworkModel;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index()
    {
        $data = AssessmentModel::with('framework')->get();

        return view('content.assessment.index', compact('data'));
    }

    public function create()
    {
        $frameworks = MasterFrameworkModel::all();

        return view(
            'content.assessment.create',
            compact('frameworks')
        );
    }

    public function store(Request $request)
    {
        AssessmentModel::create([
            'jenis_asesmen' => $request->jenis_asesmen,
            'tahun_asesmen' => $request->tahun_asesmen,
            'tahun_buku' => $request->tahun_buku,
            'framework_id' => $request->framework_id,
            'skor' => 0,
        ]);

        return redirect()->route('assessment.index');
    }

    public function edit($id)
    {
        $data = AssessmentModel::findOrFail($id);

        $frameworks = MasterFrameworkModel::all();

        return view(
            'content.assessment.edit',
            compact('data', 'frameworks')
        );
    }

    public function update(Request $request, $id)
    {
        $data = AssessmentModel::findOrFail($id);

        $data->update([
            'jenis_asesmen' => $request->jenis_asesmen,
            'tahun_asesmen' => $request->tahun_asesmen,
            'tahun_buku' => $request->tahun_buku,
            'framework_id' => $request->framework_id,
          
        ]);

        return redirect()->route('assessment.index');
    }

    public function destroy($id)
    {
        AssessmentModel::destroy($id);

        return redirect()->route('assessment.index');
    }

    public function detail($id)
    {
        $assessment = AssessmentModel::with('framework')->findOrFail($id);

        return view(
            'content.assessment.detail',
            compact('assessment')
        );
    }
}