<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('admin/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/css/kaiadmin.min.css') }}" />
</head>

<body>
    <div class="container-fluid full-height-center d-flex justify-content-center align-items-center">
        <div class="row">
            <div class="d-flex justify-content-center align-items-center py-lg-5">
                <div class=""><img src="{{ asset('admin/img/sater-logo.png') }}" alt="Sater" height="50"></div>
                <div class="card-title fs-3 d-flex justify-content-center ms-3 mt-3">Sater.id</div>
            </div>
            <div class="card p-0 bg-white rounded-4">
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 px-3">
                            <div class="card-title mb-3 fs-3">Sign in to dashboard</div>
                            <form method="POST" action="{{ route('login.submit') }}">
                                @csrf
                                <div class="form-group p-2">
                                    <label for="username">Username</label>
                                    <input name="username" type="text" class="form-control" id="username" placeholder="Enter Username" value="{{ old('username') }}" required />
                                    @error('username')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group p-2">
                                    <label for="password">Password</label>
                                    <input name="password" type="password" class="form-control" id="password" placeholder="Password" required />
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                                    <label class="form-check-label" for="flexCheckDefault">Remember me</label>
                                </div>
                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif

                                <div class="card-action p-2">
                                    <button type="submit" class="btn btn-black col-md-12 rounded-3">Log In</button>
                                </div>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</body>

</html>