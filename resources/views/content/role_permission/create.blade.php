@extends('layouts/contentNavbarLayout')

@section('title', 'Role Permission')

@section('content')

<div class="card">

    <div class="card-header">

        <h5>
            Role Permission
        </h5>

    </div>

    <div class="card-body">

        <form action="{{ route('role_permission.store') }}"
            method="POST">

            @csrf

            <div class="mb-4">

                <label class="form-label">
                    Role
                </label>

                <select name="role_id"
                    class="form-control"
                    required>

                    <option value="">
                        -- Pilih Role --
                    </option>

                    @foreach($roles as $role)

                    <option value="{{ $role->id }}">

                        {{ $role->nama_role }}

                    </option>

                    @endforeach

                </select>

            </div>

            <table class="table table-bordered">

                <thead>

                    <tr>

                        <th>Module</th>

                        <th>Create</th>

                        <th>Read</th>

                        <th>Update</th>

                        <th>Delete</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($modules as $module)

                    <tr>

                        <td>
                            {{ $module->nama_module }}
                        </td>

                        <td>
                            <input type="checkbox"
                                name="permissions[{{ $module->id }}][create]">
                        </td>

                        <td>
                            <input type="checkbox"
                                name="permissions[{{ $module->id }}][read]">
                        </td>

                        <td>
                            <input type="checkbox"
                                name="permissions[{{ $module->id }}][update]">
                        </td>

                        <td>
                            <input type="checkbox"
                                name="permissions[{{ $module->id }}][delete]">
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

            <button type="submit"
                class="btn btn-primary">

                Save

            </button>

        </form>

    </div>

</div>

@endsection