@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Employee')

@section('page-script')
@vite('resources/assets/js/form-basic-inputs.js')
@endsection

@section('content')

<div class="row g-6">

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card">
            <h5 class="card-header">Edit Employee</h5>

            <div class="card-body">

                <form action="{{ route('employee.update', $data->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label">Nama</label>
                        <input 
                            type="text"
                            class="form-control"
                            name="nama"
                            value="{{ $data->nama }}"
                            placeholder="Nama"
                        />
                    </div>

                    <div class="mb-4">
                        <label class="form-label">NIP</label>
                        <input 
                            type="text"
                            class="form-control"
                            name="nip"
                            value="{{ $data->nip }}"
                            placeholder="NIP"
                        />
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Direktorat</label>
                        <input 
                            type="text"
                            class="form-control"
                            name="direktorat"
                            value="{{ $data->direktorat }}"
                            placeholder="Direktorat"
                        />
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Bidang</label>
                        <input 
                            type="text"
                            class="form-control"
                            name="bidang"
                            value="{{ $data->bidang }}"
                            placeholder="Bidang"
                        />
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Email</label>
                        <input 
                            type="email"
                            class="form-control"
                            name="email"
                            value="{{ $data->email }}"
                            placeholder="email@email.com"
                        />
                    </div>

                    <div class="mb-4"> 
                        <label class="form-label"> Role </label> 
                        <select name="role_id" class="form-control"> @foreach($roles as $row) 
                            <option value="{{ $row->id }}" {{ $data->role_id == $row->id ? 'selected' : '' }}> {{ $row->nama_role }} </option> 
                        @endforeach 
                        </select> 
                    </div>

                    <div class="mb-4"> 
                        <label class="form-label"> Password Baru </label> 
                        <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak diubah"> 
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>

                    <a href="{{ route('employee.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>

                </form>

            </div>
        </div>

    </div>

</div>

@endsection