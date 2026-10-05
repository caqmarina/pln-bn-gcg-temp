@extends('layouts/contentNavbarLayout')

@section('title', 'Master Module')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Master Module
        </h5>

        <a href="{{ route('master_module.create') }}"
            class="btn btn-primary">

            Tambah Module

        </a>

    </div>

    <div class="table-responsive text-nowrap">

        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>No</th>
                    <th>Nama Module</th>
                    <th>Slug</th>
                    <th width="180">Action</th>

                </tr>

            </thead>

            <tbody>

                @foreach($data as $key => $row)

                <tr>

                    <td>{{ $key + 1 }}</td>

                    <td>{{ $row->nama_module }}</td>

                    <td>
                        <span class="badge bg-label-info">
                            {{ $row->slug }}
                        </span>
                    </td>

                    <td>

                        <a href="{{ route('master_module.edit', $row->id) }}"
                            class="btn btn-warning btn-sm">

                            Edit

                        </a>

                        <form action="{{ route('master_module.destroy', $row->id) }}"
                            method="POST"
                            style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="btn btn-danger btn-sm">

                                Delete

                            </button>

                        </form>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection