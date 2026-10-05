@extends('layouts/contentNavbarLayout')

@section('title', 'Create Assessment')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">

        <div class="card-header">
            <h4>Create Assessment</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('assessment.store') }}" method="POST">

                @csrf

                <div class="mb-4">

                    <label class="form-label">
                        Jenis Asesmen
                    </label>

                    <select name="jenis_asesmen"
                        class="form-control">

                        <option value="Self Assessment">
                            Self Assessment
                        </option>

                        <option value="Asesor">
                            Asesor
                        </option>

                    </select>

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Tahun Asesmen
                    </label>

                    <input type="number"
                        name="tahun_asesmen"
                        class="form-control">

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Tahun Buku
                    </label>

                    <input type="number"
                        name="tahun_buku"
                        class="form-control">

                </div>

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
                        Skor
                    </label>

                    <input type="number"
                        step="0.01"
                        name="skor"
                        class="form-control"
                        value="0"
                        readonly>

                    <small class="text-muted">
                        Skor otomatis dihitung dari detail asesmen
                    </small>

                </div>

                <button type="submit"
                    class="btn btn-primary">
                    Save
                </button>

                <a href="{{ route('assessment.index') }}"
                    class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

@endsection