@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Master Framework')

@section('page-script')
@vite('resources/assets/js/form-basic-inputs.js')
@endsection

@section('content')

<div class="row g-6">

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card">

            <h5 class="card-header">
                Edit Master Framework
            </h5>

            <div class="card-body">

                <form action="{{ route('master_framework.update', $data->id) }}"
                    method="POST">

                    @csrf
                    @method('PUT')

                    <div class="mb-4">

                        <label class="form-label">
                            Nama Framework
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="nama_framework"
                            value="{{ $data->nama_framework }}"
                            placeholder="Nama Framework"
                        />

                    </div>

                    <div class="mb-4">

                        <label class="form-label">
                            Kode Framework
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="kode_framework"
                            value="{{ $data->kode_framework }}"
                            placeholder="Kode Framework"
                        />

                    </div>

                    <div class="mb-4">

                        <label class="form-label">
                            Versi
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="versi"
                            value="{{ $data->versi }}"
                            placeholder="Versi"
                        />

                    </div>

                    <div class="mb-4">

                        <label class="form-label">
                            Tahun Berlaku
                        </label>

                        <input
                            type="number"
                            class="form-control"
                            name="tahun_berlaku"
                            value="{{ $data->tahun_berlaku }}"
                        />

                    </div>

                    <div class="mb-4">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                            class="form-control">

                            <option value="Active"
                                {{ $data->status == 'Active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="Inactive"
                                {{ $data->status == 'Inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>

                    <button type="submit"
                        class="btn btn-primary">
                        Update
                    </button>

                    <a href="{{ route('master_framework.index') }}"
                        class="btn btn-secondary">
                        Cancel
                    </a>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection