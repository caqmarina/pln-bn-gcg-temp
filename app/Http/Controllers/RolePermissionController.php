<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MasterRolePermissionModel;
use App\Models\MasterRoleModel;
use App\Models\MasterModuleModel;

class RolePermissionController extends Controller
{
    public function index()
    {
        $data = MasterRolePermissionModel::with([
            'role',
            'module'
        ])->get();

        return view(
            'content.role_permission.index',
            compact('data')
        );
    }

    public function create()
    {
        $roles = MasterRoleModel::all();

        $modules = MasterModuleModel::all();

        return view(
            'content.role_permission.create',
            compact(
                'roles',
                'modules'
            )
        );
    }

    public function store(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | VALIDASI
    |--------------------------------------------------------------------------
    */

    $request->validate([

        'role_id' => 'required',

    ]);

    /*
    |--------------------------------------------------------------------------
    | HAPUS PERMISSION LAMA
    |--------------------------------------------------------------------------
    */

    MasterRolePermissionModel::where(
        'role_id',
        $request->role_id
    )->delete();

    /*
    |--------------------------------------------------------------------------
    | SIMPAN PERMISSION BARU
    |--------------------------------------------------------------------------
    */

    if($request->permissions){

        foreach($request->permissions as $moduleId => $permission){

            MasterRolePermissionModel::create([

                'role_id' => $request->role_id,

                'module_id' => $moduleId,

                'can_create' => isset($permission['create']) ? 1 : 0,

                'can_read' => isset($permission['read']) ? 1 : 0,

                'can_update' => isset($permission['update']) ? 1 : 0,

                'can_delete' => isset($permission['delete']) ? 1 : 0,

            ]);
        }
    }

        return redirect()->route(
            'role_permission.index'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $data = MasterRolePermissionModel::findOrFail($id);

        $roles = MasterRoleModel::all();

        $modules = MasterModuleModel::all();

        return view(
            'content.role_permission.edit',
            compact(
                'data',
                'roles',
                'modules'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $data = MasterRolePermissionModel::findOrFail($id);

        $data->update([

            'role_id' => $request->role_id,

            'module_id' => $request->module_id,

            'can_create' => $request->can_create ? 1 : 0,

            'can_read' => $request->can_read ? 1 : 0,

            'can_update' => $request->can_update ? 1 : 0,

            'can_delete' => $request->can_delete ? 1 : 0,
        ]);

        return redirect()->route(
            'role_permission.index'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        MasterRolePermissionModel::destroy($id);

        return redirect()->route(
            'role_permission.index'
        );
    }
}