<?php

namespace App\Http\Controllers;

use App\Models\MasterKategoriModel;
use App\Models\MasterFrameworkModel;
use Illuminate\Http\Request;

class MasterKategoriController extends Controller
{
    public function index()
    {
        $data = MasterKategoriModel::with('framework')->get();

        return view('content.master_kategori.index', compact('data'));
    }

    public function create()
    {
        $frameworks = MasterFrameworkModel::all();

        return view(
            'content.master_kategori.create',
            compact('frameworks')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'framework_id' => 'required',
            'nama_kategori' => 'required',
            'bobot' => 'required|numeric'
        ], [
            'framework_id.required' => 'Framework wajib dipilih',
            'nama_kategori.required' => 'Nama kategori wajib diisi',
            'bobot.required' => 'Bobot wajib diisi',
            'bobot.numeric' => 'Bobot harus berupa angka',
        ]);

        MasterKategoriModel::create([
            'framework_id' => $request->framework_id,
            'nama_kategori' => $request->nama_kategori,
            'bobot' => $request->bobot,
        ]);

        return redirect()->route('master_kategori.index');
    }

    public function edit($id)
    {
        $data = MasterKategoriModel::findOrFail($id);

        $frameworks = MasterFrameworkModel::all();

        return view(
            'content.master_kategori.edit',
            compact('data', 'frameworks')
        );
    }

    public function update(Request $request, $id)
    {
        $data = MasterKategoriModel::findOrFail($id);

        $data->update([
            'framework_id' => $request->framework_id,
            'nama_kategori' => $request->nama_kategori,
            'bobot' => $request->bobot,
        ]);

        return redirect()->route('master_kategori.index');
    }

    public function destroy($id)
    {
        MasterKategoriModel::destroy($id);

        return redirect()->route('master_kategori.index');
    }
}