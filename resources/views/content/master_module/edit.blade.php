@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Master Module')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">

        <div class="card-header">

            <h5>
                Edit Master Module
            </h5>

        </div>

        <div class="card-body">

            <form action="{{ route('master_module.update', $data->id) }}"
                method="POST">

                @csrf
                @method('PUT')

                <div class="mb-4">

                    <label class="form-label">
                        Nama Module
                    </label>

                    <input type="text"
                        name="nama_module"
                        class="form-control"
                        value="{{ $data->nama_module }}">

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Slug
                    </label>

                    <input type="text"
                        name="slug"
                        class="form-control"
                        value="{{ $data->slug }}">

                </div>

                <button type="submit"
                    class="btn btn-primary">

                    Update

                </button>

            </form>

        </div>

    </div>

</div>

@endsection