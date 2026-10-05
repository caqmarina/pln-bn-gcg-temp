@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Arahan')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">

        <div class="card-header">
            <h4>Edit Arahan</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('arahan.update', $data->id) }}"
                method="POST">

                @csrf
                @method('PUT')

                <div class="mb-4">

                    <label class="form-label">
                        Judul Arahan
                    </label>

                    <input type="text"
                        name="judul_arahan"
                        class="form-control"
                        value="{{ $data->judul_arahan }}"
                        required>

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Tanggal Arahan
                    </label>

                    <input type="date"
                        name="tanggal_arahan"
                        class="form-control"
                        value="{{ $data->tanggal_arahan }}"
                        required>

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Progress
                    </label>

                    <input type="number"
                        class="form-control"
                        value="{{ $data->progress }}"
                        readonly>

                </div>

                <button type="submit"
                    class="btn btn-primary">
                    Update
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