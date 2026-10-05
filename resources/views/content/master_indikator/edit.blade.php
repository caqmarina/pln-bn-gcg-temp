@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Master Indikator')

@section('page-script')
@vite('resources/assets/js/form-basic-inputs.js')
@endsection

@section('content')

<div class="row g-6">

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card">

            <h5 class="card-header">
                Edit Master Indikator
            </h5>

            <div class="card-body">

                <form action="{{ route('master_indikator.update', $data->id) }}"
                    method="POST">

                    @csrf
                    @method('PUT')

                    <div class="mb-4">

                        <label class="form-label">
                            Kategori
                        </label>

                        <select name="kategori_id"
                            class="form-control">

                            @foreach($kategoris as $row)

                            <option value="{{ $row->id }}"
                                {{ $data->kategori_id == $row->id ? 'selected' : '' }}>

                                {{ $row->nama_kategori }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="mb-4">

                        <label class="form-label">
                            Kode Indikator
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="kode_indikator"
                            value="{{ $data->kode_indikator }}"
                            placeholder="Kode Indikator"
                        />

                    </div>

                    <div class="mb-4">

                        <label class="form-label">
                            Indikator
                        </label>

                        <textarea
                            class="form-control"
                            name="indikator"
                            rows="4"
                        >{{ $data->indikator }}</textarea>

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

                    <a href="{{ route('master_indikator.index') }}"
                        class="btn btn-secondary">
                        Cancel
                    </a>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection