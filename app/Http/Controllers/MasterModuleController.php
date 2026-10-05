<?php

namespace App\Http\Controllers;

use App\Models\MasterModuleModel;
use Illuminate\Http\Request;

class MasterModuleController extends Controller
{
    public function index()
    {
        $data = MasterModuleModel::all();

        return view(
            'content.master_module.index',
            compact('data')
        );
    }

    public function create()
    {
        return view('content.master_module.create');
    }

    public function store(Request $request)
    {
        MasterModuleModel::create([

            'nama_module' => $request->nama_module,

            'slug' => $request->slug,

        ]);

        return redirect()->route('master_module.index');
    }

    public function edit($id)
    {
        $data = MasterModuleModel::findOrFail($id);

        return view(
            'content.master_module.edit',
            compact('data')
        );
    }

    public function update(Request $request, $id)
    {
        $data = MasterModuleModel::findOrFail($id);

        $data->update([

            'nama_module' => $request->nama_module,

            'slug' => $request->slug,

        ]);

        return redirect()->route('master_module.index');
    }

    public function destroy($id)
    {
        MasterModuleModel::destroy($id);

        return redirect()->route('master_module.index');
    }
}