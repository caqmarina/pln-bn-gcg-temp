<!DOCTYPE html>
<html>

<head>

    <title>
        Login
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body>

<div class="container">

    <div class="row justify-content-center mt-5">

        <div class="col-md-4">

            <div class="card">

                <div class="card-header">

                    <h4>
                        Login System
                    </h4>

                </div>

                <div class="card-body">

                    @if(session('error'))

                    <div class="alert alert-danger">

                        {{ session('error') }}

                    </div>

                    @endif

                    <form action="{{ route('login.process') }}"
                        method="POST">

                        @csrf

                        <div class="mb-3">

                            <label>Email</label>

                            <input type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>

                        <div class="mb-3">

                            <label>Password</label>

                            <input type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>

                        <button type="submit"
                            class="btn btn-primary w-100">

                            Login

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>
