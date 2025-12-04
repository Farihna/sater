@extends('landing.layouts.app')

@section('title', 'Login')

@section('content')
    <section id="login" class="page-section">
        <div class="container">
            <div class="auth-container">
                <h3 class="auth-title">Masuk ke Sater</h3>
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Login Gagal!</strong> Harap periksa kembali Email/Username dan Password Anda.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form id="loginForm" method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="loginId" class="form-label">Email / Username</label>
                        <input type="text" class="form-control" id="loginId" name="login_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="loginPassword" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                    <div class="text-center mt-3">
                        <p>Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
                        <p><a href="{{ route('partner.register') }}">Daftar Menjadi Mitra</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection