@extends('layouts/contentNavbarLayout')

@section('title', 'Create Arahan')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">

        <div class="card-header">
            <h4>Create Arahan</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('arahan.store') }}" method="POST">

                @csrf

                <div class="mb-4">

                    <label class="form-label">
                        Judul Arahan
                    </label>

                    <input type="text"
                        name="judul_arahan"
                        class="form-control"
                        required>

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Tanggal Arahan
                    </label>

                    <input type="date"
                        name="tanggal_arahan"
                        class="form-control"
                        required>

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Progress
                    </label>

                    <input type="number"
                        class="form-control"
                        value="0"
                        readonly>

                    <small class="text-muted">
                        Progress dihitung otomatis dari penyelesaian detail arahan
                    </small>

                </div>

                <button type="submit"
                    class="btn btn-primary">
                    Save
                </button>

                <a href="{{ route('arahan.index') }}"
                    class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

@endsection