@extends('layouts/contentNavbarLayout')

@section('title', 'Create Master Module')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">

        <div class="card-header">

            <h5>
                Create Master Module
            </h5>

        </div>

        <div class="card-body">

            <form action="{{ route('master_module.store') }}"
                method="POST">

                @csrf

                <div class="mb-4">

                    <label class="form-label">
                        Nama Module
                    </label>

                    <input type="text"
                        name="nama_module"
                        class="form-control">

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Slug
                    </label>

                    <input type="text"
                        name="slug"
                        class="form-control"
                        placeholder="contoh: assessment">

                </div>

                <button type="submit"
                    class="btn btn-primary">

                    Save

                </button>

            </form>

        </div>

    </div>

</div>

@endsection