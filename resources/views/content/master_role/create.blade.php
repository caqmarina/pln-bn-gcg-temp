@extends('layouts/contentNavbarLayout')

@section('title', 'Create Master Role')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">

        <div class="card-header">

            <h5>
                Create Master Role
            </h5>

        </div>

        <div class="card-body">

            <form action="{{ route('master_role.store') }}"
                method="POST">

                @csrf

                <div class="mb-4">

                    <label class="form-label">
                        Nama Role
                    </label>

                    <input type="text"
                        name="nama_role"
                        class="form-control"
                        placeholder="Nama Role">

                </div>

                <button type="submit"
                    class="btn btn-primary">

                    Save

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