@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Master Role')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">

        <div class="card-header">

            <h5>
                Edit Master Role
            </h5>

        </div>

        <div class="card-body">

            <form action="{{ route('master_role.update', $data->id) }}"
                method="POST">

                @csrf
                @method('PUT')

                <div class="mb-4">

                    <label class="form-label">
                        Nama Role
                    </label>

                    <input type="text"
                        name="nama_role"
                        class="form-control"
                        value="{{ $data->nama_role }}">

                </div>

                <button type="submit"
                    class="btn btn-primary">

                    Update

                </button>

                <a href="{{ route('master_role.index') }}"
                    class="btn btn-secondary">

                    Cancel

                </a>

            </form>

        </div>

    </div>

</div>

@endsection