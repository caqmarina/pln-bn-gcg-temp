@extends('layouts/contentNavbarLayout')

@section('title', 'Role Permission')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Role Permission
        </h5>

        <a href="{{ route('role_permission.create') }}"
            class="btn btn-primary">

            Setting Permission

        </a>

    </div>

    <div class="table-responsive">

        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>Role</th>
                    <th>Module</th>
                    <th>Create</th>
                    <th>Read</th>
                    <th>Update</th>
                    <th>Delete</th>

                </tr>

            </thead>

            <tbody>

                @foreach($data as $row)

                <tr>

                    <td>
                        {{ $row->role->nama_role }}
                    </td>

                    <td>
                        {{ $row->module->nama_module }}
                    </td>

                    <td>
                        {!! $row->can_create
                            ? '<span class="badge bg-success">YES</span>'
                            : '<span class="badge bg-danger">NO</span>' !!}
                    </td>

                    <td>
                        {!! $row->can_read
                            ? '<span class="badge bg-success">YES</span>'
                            : '<span class="badge bg-danger">NO</span>' !!}
                    </td>

                    <td>
                        {!! $row->can_update
                            ? '<span class="badge bg-success">YES</span>'
                            : '<span class="badge bg-danger">NO</span>' !!}
                    </td>

                    <td>
                        {!! $row->can_delete
                            ? '<span class="badge bg-success">YES</span>'
                            : '<span class="badge bg-danger">NO</span>' !!}
                    </td>
                    <td>

                        <a href="{{ route('role_permission.edit', $row->id) }}"
                            class="btn btn-warning btn-sm">

                            Edit

                        </a>

                        <form action="{{ route('role_permission.destroy', $row->id) }}"
                            method="POST"
                            style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin hapus permission?')">

                                Delete

                            </button>

                        </form>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection