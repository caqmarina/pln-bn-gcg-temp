<?php

namespace App\Http\Controllers;

use App\Models\MasterFrameworkModel;
use Illuminate\Http\Request;

class MasterFrameworkController extends Controller
{
    public function index()
    {
        $data = MasterFrameworkModel::all();

        return view('content.master_framework.index', compact('data'));
    }

    public function create()
    {
        return view('content.master_framework.create');
    }

    public function store(Request $request)
    {
        MasterFrameworkModel::create([
            'nama_framework' => $request->nama_framework,
            'kode_framework' => $request->kode_framework,
            'versi' => $request->versi,
            'tahun_berlaku' => $request->tahun_berlaku,
            'status' => $request->status,
        ]);

        return redirect()->route('master_framework.index');
    }

    public function edit($id)
    {
        $data = MasterFrameworkModel::findOrFail($id);

        return view('content.master_framework.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = MasterFrameworkModel::findOrFail($id);

        $data->update([
            'nama_framework' => $request->nama_framework,
            'kode_framework' => $request->kode_framework,
            'versi' => $request->versi,
            'tahun_berlaku' => $request->tahun_berlaku,
            'status' => $request->status,
        ]);

        return redirect()->route('master_framework.index');
    }

    public function destroy($id)
    {
        MasterFrameworkModel::destroy($id);

        return redirect()->route('master_framework.index');
    }
}