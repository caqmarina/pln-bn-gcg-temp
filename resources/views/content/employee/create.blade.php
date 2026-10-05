@extends('layouts/contentNavbarLayout')

@section('title', 'Basic Inputs - Forms')

@section('page-script')
@vite('resources/assets/js/form-basic-inputs.js')
@endsection

@section('content')
<div class="row g-6">
    
    <!-- Form controls -->
    <div class="container-xxl flex-grow-1 container-p-y">
        
        <div class="card">
            <h5 class="card-header">Employee Input</h5>
            <div class="card-body">
                <form action="{{ route('employee.store') }}" method="POST">
                    @csrf
                <div class="mb-5">
                    <label for="exampleFormControlInput1" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" />
                </div>
                <div class="mb-4">
                    <label for="exampleFormControlReadOnlyInput1" class="form-label">NIP</label>
                    <input class="form-control" type="text" id="nip" name="nip" placeholder="NIP"  />
                </div>
                <div class="mb-4">
                    <label for="exampleFormControlReadOnlyInput1" class="form-label">Direktorat</label>
                    <input class="form-control" type="text" id="direktorat" name="direktorat" placeholder="Direktorat"/>
                </div>
                <div class="mb-4">
                    <label for="exampleFormControlReadOnlyInputPlain1" class="form-label">Bidang</label>
                    <input class="form-control" type="text" id="bidang" name="bidang" placeholder="Bidang"/>
                </div>
                <div class="mb-4">
                    <label for="exampleFormControlReadOnlyInputPlain1" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="email@email.com" />
                    
                </div>
                <div class="mb-4"> 
                    <label class="form-label"> Role </label> 
                    <select name="role_id" class="form-control"> @foreach($roles as $row) <option value="{{ $row->id }}"> {{ $row->nama_role }} </option> 
                    @endforeach </select> 
                </div>
                <div class="mb-4"> 
                    <label class="form-label"> Password </label> 
                    <input type="password" class="form-control" name="password" placeholder="Password"> 
                </div>
                
                <button type="submit" class="btn btn-primary">
                    Save
                </button>

                <a href="{{ route('employee.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </div>
    </div>

    
</div>
@endsection
