<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sater - Home</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('store/css/style.css') }}" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #1a2035;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }
        .login-wrapper {
            min-height: 100vh;
        }
        .form-control.bg-secondary {
            background-color: #33334d !important;
            color: #fff !important;
            border: 1px solid #555577 !important;
        }
        .alert.alert-danger {
            background-color: #330000;
            border-color: #550000;
            color: #ff9999;
        }
        .alert .btn-close-white {
            filter: invert(1);
        }
    </style>
</head>
<body>
    <div class="login-wrapper d-flex align-items-center justify-content-center vh-100">
        <div class="card bg-dark shadow-lg border-secondary" style="max-width: 400px; width: 90%;">
            <div class="card-header text-center border-0 bg-dark pt-4 pb-3">
                <img src="{{ asset('admin/img/logo-sater-light.png') }}" alt="Sater" class="mb-2" style="height: 60px;">
                <h5 class="fw-bold mb-0 text-white mt-2">LOGIN ADMINISTRATOR</h5>
                <small class="text-secondary">Akses Terbatas Sistem Inventaris</small>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <strong>Gagal!</strong> Periksa kembali Kredensial Admin Anda.
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif
                <form id="loginForm" method="POST" action="{{ route('admin.login.submit') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="loginId" class="form-label text-light">Email / Username</label>
                        <input type="text" class="form-control bg-secondary text-white border-dark" id="loginId"
                            name="login_id" required>
                    </div>
                    <div class="mb-4">
                        <label for="loginPassword" class="form-label text-light">Password</label>
                        <input type="password" class="form-control bg-secondary text-white border-dark"
                            id="loginPassword" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-light w-100 fw-bold py-2">
                        MASUK KE DASHBOARD
                    </button>
                </form>
                <div class="text-center mt-3">
                    <a href="{{ route('index') }}"><i class="bi bi-arrow-left"></i> Pergi Ke Landing</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>