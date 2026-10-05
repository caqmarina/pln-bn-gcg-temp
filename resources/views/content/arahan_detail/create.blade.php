@extends('layouts/contentNavbarLayout')

@section('title', 'Create Arahan Detail')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">

        <div class="card-header">
            <h4>Create Arahan Detail</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('arahan_detail.store') }}" method="POST">

                @csrf

                <input type="hidden"
                    name="arahan_id"
                    value="{{ $arahan->id }}">

                <div class="mb-4">

                    <label class="form-label">
                        ID Arahan
                    </label>

                    <input type="text"
                        class="form-control"
                        value="{{ $arahan->id }}"
                        readonly>

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Judul Arahan
                    </label>

                    <input type="text"
                        class="form-control"
                        value="{{ $arahan->judul_arahan }}"
                        readonly>

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Aspek
                    </label>

                    <input type="text"
                        name="aspek"
                        class="form-control"
                        placeholder="Masukkan aspek"
                        required>

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Arahan
                    </label>

                    <textarea
                        name="arahan"
                        class="form-control"
                        rows="4"
                        required></textarea>

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Tindak Lanjut
                    </label>

                    <textarea
                        name="tindak_lanjut"
                        class="form-control"
                        rows="4"></textarea>

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Status
                    </label>

                    <select name="status"
                        class="form-control">

                        <option value="Open">
                            Open
                        </option>

                        <option value="Progress">
                            Progress
                        </option>

                        <option value="Selesai">
                            Selesai
                        </option>

                        <option value="Selesai Berkelanjutan">
                            Selesai Berkelanjutan
                        </option>

                    </select>

                </div>

                <button type="submit"
                    class="btn btn-primary">
                    Save
                </button>

                <a href="{{ route('arahan_detail.index', $arahan->id) }}"
                    class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

@endsection