@extends('landing.layouts.app')
@section('title', 'Become a Partner')
@section('content')
    <section class="page-section py-5">
        <div class="container">
            <div class="card shadow-lg mx-auto">
                <div class="card-body p-4 p-md-5">
                    <h3 class="auth-title fw-bold text-center mb-4">DAFTAR SEBAGAI MITRA</h3>
                    <p class="text-center text-muted mb-4">Lengkapi data akun dan profil bisnis Anda.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Pendaftaran Gagal!</strong> Harap periksa kembali isian formulir Anda.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('partner.register.submit') }}" enctype="multipart/form-data">
                        @csrf
                        <h6 class="fw-bold text-primary mb-3 mt-3">1. Data Akun Login</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Nama Kontak (Sesuai KTP)</label>
                                <input type="text" name="username" id="username"
                                    class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}"
                                    required>
                                @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" required>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="fw-bold text-primary mb-3">2. Data Bisnis & Verifikasi</h6>

                        <div class="mb-3">
                            <label for="company_name" class="form-label">Nama Bisnis/Peternakan</label>
                            <input type="text" name="company_name" id="company_name"
                                class="form-control @error('company_name') is-invalid @enderror"
                                value="{{ old('company_name') }}" required>
                            @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon Bisnis/Operasional</label>
                            <input type="tel" name="phone" id="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat Lengkap Peternakan/Gudang</label>
                            <textarea name="address" id="address"
                                class="form-control @error('address') is-invalid @enderror" rows="3"
                                required>{{ old('address') }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nik" class="form-label">Nomor KTP (NIK)</label>
                                <input type="text" name="nik" id="nik"
                                    class="form-control @error('nik') is-invalid @enderror"
                                    value="{{ old('nik') }}" required>
                                @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="identity_document" class="form-label">Unggah Foto KTP</label>
                                <input type="file" name="identity_document" id="identity_document"
                                    class="form-control @error('identity_document') is-invalid @enderror" required
                                    accept="image/*">
                                @error('identity_document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="agree_terms"
                                class="form-check-input @error('agree_terms') is-invalid @enderror" id="partnerAgreeTerms"
                                required>
                            <label class="form-check-label" for="partnerAgreeTerms">Saya menyetujui Syarat dan Ketentuan
                                Mitra.</label>
                            @error('agree_terms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mt-4">DAFTAR & KIRIM
                            VERIFIKASI</button>

                        <div class="text-center mt-3">
                            <p>Sudah punya akun? <a href="{{ route('login') }}" class="fw-bold">Masuk Sekarang</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection