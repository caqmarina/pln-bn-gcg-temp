<?php

namespace App\Http\Controllers;

use App\Models\employeeModel;
use App\Models\MasterRoleModel;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $data = employeeModel::all();

        return view(
            'content.employee.index',
            compact('data')
        );
    }

    public function create()
    {
        $roles = MasterRoleModel::all();

        return view(
            'content.employee.create',
            compact('roles')
        );
    }

    public function store(Request $request)
    {
        employeeModel::create([

            'nama' => $request->nama,

            'nip' => $request->nip,

            'direktorat' => $request->direktorat,

            'bidang' => $request->bidang,

            'email' => $request->email,

            'role_id' => $request->role_id,

            'password' => Hash::make(
                $request->password
            ),
        ]);

        return redirect()->route(
            'employee.index'
        );
    }

    public function edit($id)
    {
        $data = employeeModel::findOrFail($id);

        $roles = MasterRoleModel::all();

        return view(
            'content.employee.edit',
            compact(
                'data',
                'roles'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $data = employeeModel::findOrFail($id);

        $updateData = [

            'nama' => $request->nama,

            'nip' => $request->nip,

            'direktorat' => $request->direktorat,

            'bidang' => $request->bidang,

            'email' => $request->email,

            'role_id' => $request->role_id,
        ];

        /*
        |--------------------------------------------------------------------------
        | UPDATE PASSWORD JIKA DIISI
        |--------------------------------------------------------------------------
        */

        if($request->password){

            $updateData['password'] = Hash::make(
                $request->password
            );
        }

        $data->update($updateData);

        return redirect()->route(
            'employee.index'
        );
    }

    public function destroy($id)
    {
        employeeModel::destroy($id);

        return redirect()->route(
            'employee.index'
        );
    }
}

