@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Arahan Detail')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">

        <div class="card-header">
            <h4>Edit Arahan Detail</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('arahan_detail.update', $data->id) }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="mb-4">

                    <label class="form-label">
                        Aspek
                    </label>

                    <input type="text"
                        name="aspek"
                        class="form-control"
                        value="{{ $data->aspek }}">

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Arahan
                    </label>

                    <textarea
                        name="arahan"
                        class="form-control"
                        rows="4">{{ $data->arahan }}</textarea>

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Tindak Lanjut
                    </label>

                    <textarea
                        name="tindak_lanjut"
                        class="form-control"
                        rows="4">{{ $data->tindak_lanjut }}</textarea>

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Status
                    </label>

                    <select name="status"
                        class="form-control">

                        <option value="Open"
                            {{ $data->status == 'Open' ? 'selected' : '' }}>
                            Open
                        </option>

                        <option value="Progress"
                            {{ $data->status == 'Progress' ? 'selected' : '' }}>
                            Progress
                        </option>

                        <option value="Selesai"
                            {{ $data->status == 'Selesai' ? 'selected' : '' }}>
                            Selesai
                        </option>

                        <option value="Selesai Berkelanjutan"
                            {{ $data->status == 'Selesai Berkelanjutan' ? 'selected' : '' }}>
                            Selesai Berkelanjutan
                        </option>

                    </select>

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Upload Eviden
                    </label>

                    <input type="file"
                        name="eviden"
                        class="form-control">

                </div>

                @if($data->eviden)

                <div class="mb-4">

                    <a href="{{ asset('uploads/eviden_arahan/'.$data->eviden) }}"
                        target="_blank">

                        Lihat Eviden Saat Ini

                    </a>

                </div>

                @endif

                <button type="submit"
                    class="btn btn-primary">

                    Update

                </button>

                <a href="{{ route('arahan_detail.index', $data->arahan_id) }}"
                    class="btn btn-secondary">

                    Cancel

                </a>

            </form>

        </div>

    </div>

</div>

@endsection