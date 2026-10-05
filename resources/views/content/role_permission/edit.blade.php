@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Role Permission')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">

        <div class="card-header">
            <h5>Edit Role Permission</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('role_permission.update', $data->id) }}"
                method="POST">

                @csrf
                @method('PUT')

                <div class="mb-4">

                    <label class="form-label">
                        Role
                    </label>

                    <select name="role_id"
                        class="form-control">

                        @foreach($roles as $row)

                        <option value="{{ $row->id }}"
                            {{ $data->role_id == $row->id ? 'selected' : '' }}>

                            {{ $row->nama_role }}

                        </option>

                        @endforeach

                    </select>

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Module
                    </label>

                    <select name="module_id"
                        class="form-control">

                        @foreach($modules as $row)

                        <option value="{{ $row->id }}"
                            {{ $data->module_id == $row->id ? 'selected' : '' }}>

                            {{ $row->nama_module }}

                        </option>

                        @endforeach

                    </select>

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Permission
                    </label>

                    <div>

                        <input type="checkbox"
                            name="can_create"
                            value="1"
                            {{ $data->can_create ? 'checked' : '' }}>

                        Create

                    </div>

                    <div>

                        <input type="checkbox"
                            name="can_read"
                            value="1"
                            {{ $data->can_read ? 'checked' : '' }}>

                        Read

                    </div>

                    <div>

                        <input type="checkbox"
                            name="can_update"
                            value="1"
                            {{ $data->can_update ? 'checked' : '' }}>

                        Update

                    </div>

                    <div>

                        <input type="checkbox"
                            name="can_delete"
                            value="1"
                            {{ $data->can_delete ? 'checked' : '' }}>

                        Delete

                    </div>

                </div>

                <button type="submit"
                    class="btn btn-primary">

                    Update

                </button>

                <a href="{{ route('role_permission.index') }}"
                    class="btn btn-secondary">

                    Cancel

                </a>

            </form>

        </div>

    </div>

</div>

@endsection