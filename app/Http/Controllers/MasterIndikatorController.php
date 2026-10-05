<?php

namespace App\Http\Controllers;

use App\Models\MasterIndikatorModel;
use App\Models\MasterKategoriModel;
use Illuminate\Http\Request;

class MasterIndikatorController extends Controller
{
    public function index()
    {
        $data = MasterIndikatorModel::with('kategori')->get();

        return view('content.master_indikator.index', compact('data'));
    }

    public function create()
    {
        $kategoris = MasterKategoriModel::all();

        return view(
            'content.master_indikator.create',
            compact('kategoris')
        );
    }

    public function store(Request $request)
    {
        MasterIndikatorModel::create([
            'kategori_id' => $request->kategori_id,
            'kode_indikator' => $request->kode_indikator,
            'indikator' => $request->indikator,
            'bobot' => $request->bobot,
        ]);

        return redirect()->route('master_indikator.index');
    }

    public function edit($id)
    {
        $data = MasterIndikatorModel::findOrFail($id);

        $kategoris = MasterKategoriModel::all();

        return view(
            'content.master_indikator.edit',
            compact('data', 'kategoris')
        );
    }

    public function update(Request $request, $id)
    {
        $data = MasterIndikatorModel::findOrFail($id);

        $data->update([
            'kategori_id' => $request->kategori_id,
            'kode_indikator' => $request->kode_indikator,
            'indikator' => $request->indikator,
            'bobot' => $request->bobot,
        ]);

        return redirect()->route('master_indikator.index');
    }

    public function destroy($id)
    {
        MasterIndikatorModel::destroy($id);

        return redirect()->route('master_indikator.index');
    }
}