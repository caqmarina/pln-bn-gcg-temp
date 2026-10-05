<?php

namespace App\Http\Controllers;

use App\Models\MasterRoleModel;             
use Illuminate\Http\Request;

class MasterRoleController extends Controller
{
    public function index()
    {
        $data = MasterRoleModel::all();

        return view(
            'content.master_role.index',
            compact('data')
        );
    }

    public function create()
    {
        return view('content.master_role.create');
    }

    public function store(Request $request)
    {
        MasterRoleModel::create([

            'nama_role' => $request->nama_role

        ]);

        return redirect()->route('master_role.index');
    }

    public function edit($id)
    {
        $data = MasterRoleModel::findOrFail($id);

        return view(
            'content.master_role.edit',
            compact('data')
        );
    }

    public function update(Request $request, $id)
    {
        $data = MasterRoleModel::findOrFail($id);

        $data->update([

            'nama_role' => $request->nama_role

        ]);

        return redirect()->route('master_role.index');
    }

    public function destroy($id)
    {
        MasterRoleModel::destroy($id);

        return redirect()->route('master_role.index');
    }
}