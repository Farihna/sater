@extends('landing.layouts.app')
@section('title', 'Register')
@section('content')
<section id="register" class="page-section">
    <div class="container">
        <div class="auth-container">
            <h3 class="auth-title">Daftar</h3>
            <form id="registerForm" method="POST" action="{{ route('register.submit') }}">
                @csrf   
                <div class="mb-3">
                    <label for="registerName" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="registerName" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="registerEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="registerEmail" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="registerPhone" class="form-label">Nomor Telepon</label>
                    <input type="tel" class="form-control" id="registerPhone" name="phone" required>
                </div>
                <div class="mb-3">
                    <label for="registerPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="registerPassword" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="agreeTerms" required>
                    <label class="form-check-label" for="agreeTerms">Saya menyetujui Syarat dan Ketentuan</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Daftar</button>
                <div class="text-center mt-3">
                    <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection