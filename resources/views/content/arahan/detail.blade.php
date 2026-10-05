@extends('layouts/contentNavbarLayout')

@section('title', 'Assessment Detail')

@section('content')

<div class="card">

    <div class="card-header">
        <h4>
            Detail Assessment
        </h4>
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th width="250">Jenis Asesmen</th>
                <td>{{ $assessment->jenis_asesmen }}</td>
            </tr>

            <tr>
                <th>Tahun Asesmen</th>
                <td>{{ $assessment->tahun_asesmen }}</td>
            </tr>

            <tr>
                <th>Tahun Buku</th>
                <td>{{ $assessment->tahun_buku }}</td>
            </tr>

            <tr>
                <th>Framework</th>
                <td>{{ $assessment->framework->nama_framework }}</td>
            </tr>

            <tr>
                <th>Skor</th>
                <td>{{ $assessment->skor }}</td>
            </tr>

        </table>

        <a href="{{ route('assessment.index') }}"
            class="btn btn-secondary">
            Back
        </a>

    </div>

</div>

@endsection