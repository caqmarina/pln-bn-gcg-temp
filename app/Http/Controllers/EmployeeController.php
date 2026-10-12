<?php

namespace App\Http\Controllers;

use App\Models\employeeModel;
use App\Models\MasterRoleModel;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:255', 'unique:employees,nip'],
            'Jabatan' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'direktorat' => ['required', 'string', 'max:255'],
            'bidang' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:employees,email'],
            'role_id' => ['required', 'exists:master_roles,id'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        employeeModel::create([

            'nama' => $validated['nama'],
            'nip' => $validated['nip'],
            'Jabatan' => $validated['Jabatan'],
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'direktorat' => $validated['direktorat'],
            'bidang' => $validated['bidang'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],

            'password' => Hash::make(
                $validated['password']
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

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:255', Rule::unique('employees', 'nip')->ignore($data->id)],
            'Jabatan' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'direktorat' => ['required', 'string', 'max:255'],
            'bidang' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('employees', 'email')->ignore($data->id)],
            'role_id' => ['required', 'exists:master_roles,id'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $updateData = [

            'nama' => $validated['nama'],
            'nip' => $validated['nip'],
            'Jabatan' => $validated['Jabatan'],
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'direktorat' => $validated['direktorat'],
            'bidang' => $validated['bidang'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
        ];

        /*
        |--------------------------------------------------------------------------
        | UPDATE PASSWORD JIKA DIISI
        |--------------------------------------------------------------------------
        */

        if($validated['password'] ?? null){

            $updateData['password'] = Hash::make(
                $validated['password']
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

