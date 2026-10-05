@extends('layouts/contentNavbarLayout')

@section('title', 'Arahan')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Arahan
        </h5>

        <a href="{{ route('arahan.create') }}"
            class="btn btn-primary">
            Tambah Arahan
        </a>

    </div>


    <div class="table-responsive text-nowrap">

        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>No</th>
                    <th>Judul Arahan</th>
                    <th>Tanggal Arahan</th>
                    <th>Progress</th>
                    <th width="250">Action</th>

                </tr>

            </thead>

            <tbody>

                @forelse($data as $row)

                <tr>

                    <td>
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        {{ $row->judul_arahan }}
                    </td>

                    <td>
                        {{ $row->tanggal_arahan }}
                    </td>

                    <td>

                        @if($row->progress == 100)

                            <span class="badge bg-success">
                                {{ number_format($row->progress,0) }}%
                            </span>

                        @elseif($row->progress > 0)

                            <span class="badge bg-warning">
                                {{ number_format($row->progress,0) }}%
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                0%
                            </span>

                        @endif

                    </td>

                    <td>

                        <a href="{{ route('arahan_detail.index', $row->id) }}"
                            class="btn btn-info btn-sm">
                            Detail
                        </a>

                        <a href="{{ route('arahan.edit', $row->id) }}"
                            class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('arahan.destroy', $row->id) }}"
                            method="POST"
                            style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin hapus data?')">

                                Delete

                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5"
                        class="text-center">

                        Data arahan belum tersedia

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection