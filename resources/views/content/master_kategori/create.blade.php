@extends('layouts/contentNavbarLayout')

@section('title', 'Create Master Kategori')

@section('page-script')
@vite('resources/assets/js/form-basic-inputs.js')
@endsection

@section('content')

<div class="row g-6">

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card">

            <h5 class="card-header">
                Create Master Kategori
            </h5>

            <div class="card-body">

                <form action="{{ route('master_kategori.store') }}" method="POST">

                    @csrf

                    <div class="mb-4">

                        <label class="form-label">
                            Framework
                        </label>

                        <select name="framework_id"
                            class="form-control">

                            @foreach($frameworks as $row)

                            <option value="{{ $row->id }}">
                                {{ $row->nama_framework }}
                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="mb-4">

                        <label class="form-label">
                            Nama Kategori
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="nama_kategori"
                            placeholder="Nama Kategori"
                        />

                    </div>

                    <div class="mb-4">

                        <label class="form-label">
                            Bobot
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            class="form-control @error('bobot') is-invalid @enderror"
                            name="bobot"
                            placeholder="0"
                            value="{{ old('bobot') }}"
                        />

                        @error('bobot')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>

                    <a href="{{ route('master_kategori.index') }}"
                        class="btn btn-secondary">
                        Cancel
                    </a>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection