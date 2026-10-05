@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Master Kategori')

@section('page-script')
@vite('resources/assets/js/form-basic-inputs.js')
@endsection

@section('content')

<div class="row g-6">

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card">

            <h5 class="card-header">
                Edit Master Kategori
            </h5>

            <div class="card-body">

                <form action="{{ route('master_kategori.update', $data->id) }}"
                    method="POST">

                    @csrf
                    @method('PUT')

                    <div class="mb-4">

                        <label class="form-label">
                            Framework
                        </label>

                        <select name="framework_id"
                            class="form-control">

                            @foreach($frameworks as $row)

                            <option value="{{ $row->id }}"
                                {{ $data->framework_id == $row->id ? 'selected' : '' }}>

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
                            value="{{ $data->nama_kategori }}"
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
                            class="form-control"
                            name="bobot"
                            value="{{ $data->bobot }}"
                        />

                    </div>

                    <button type="submit"
                        class="btn btn-primary">
                        Update
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