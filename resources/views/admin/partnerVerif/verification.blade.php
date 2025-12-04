@extends('admin.layouts.app')
@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Detail Verifikasi Mitra</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.partner.verification.index') }}">Verifikasi Mitra</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Detail</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Informasi Pendaftar: {{ $partner->user->username ?? 'N/A' }}</h4>
                    </div>
                    <div class="card-body">
                        @if (isset($partner) && $partner)
                            <div class="row">
                                <div class="col-md-7">
                                    <h6 class="fw-bold text-primary mb-3">Data Akun & Bisnis</h6>
                                    <dl class="row small">
                                        <dt class="col-sm-4 text-muted">ID Mitra:</dt>
                                        <dd class="col-sm-8">{{ $partner->id }}</dd>

                                        <dt class="col-sm-4 text-muted">Nama Kontak:</dt>
                                        <dd class="col-sm-8">{{ $partner->user->username ?? 'N/A' }}</dd>

                                        <dt class="col-sm-4 text-muted">Nama Bisnis:</dt>
                                        <dd class="col-sm-8">{{ $partner->company_name }}</dd>

                                        <dt class="col-sm-4 text-muted">Email:</dt>
                                        <dd class="col-sm-8">{{ $partner->user->email ?? 'N/A' }}</dd>

                                        <dt class="col-sm-4 text-muted">No. Telepon Bisnis:</dt>
                                        <dd class="col-sm-8">{{ $partner->phone }}</dd>

                                        <dt class="col-sm-4 text-muted">Alamat:</dt>
                                        <dd class="col-sm-8">{{ $partner->address }}</dd>
                                    </dl>

                                    <hr class="my-3">

                                    <h6 class="fw-bold text-primary mb-3">Data Pencairan Dana (Payout)</h6>
                                    <dl class="row small">
                                        <dt class="col-sm-4 text-muted">Nama Bank:</dt>
                                        <dd class="col-sm-8">{{ $partner->bank_name }}</dd>
                                        <dt class="col-sm-4 text-muted">No. Rekening:</dt>
                                        <dd class="col-sm-8">{{ $partner->account_number }}</dd>
                                        <dt class="col-sm-4 text-muted">Pemilik Rekening:</dt>
                                        <dd class="col-sm-8">{{ $partner->account_holder_name }}</dd>
                                        <dt class="col-sm-4 text-muted">NPWP:</dt>
                                        <dd class="col-sm-8">{{ $partner->npwp ?? 'Tidak Ada' }}</dd>
                                    </dl>

                                </div>

                                <div class="col-md-5 border-start">
                                    <h6 class="fw-bold text-danger mb-3">Verifikasi Dokumen</h6>

                                    <p class="mb-1 small text-muted">Nomor KTP (NIK):</p>
                                    <strong class="text-dark">{{ $partner->nik }}</strong>

                                    <p class="mt-3 mb-1 small text-muted">Foto KTP:</p>
                                    <div class="text-center border p-2 rounded mb-3">
                                        <img src="{{ route('ktp.show', $partner->user->id) }}"
                                            class="img-fluid rounded" alt="Dokumen KTP">
                                    </div>

                                    <hr class="my-3">

                                    <h6 class="fw-bold mb-3">Keputusan Admin</h6>
                                    <form method="POST" action="{{ route('admin.partner.verification.decide', $partner->id) }}">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="rejection_reason" class="form-label small">Alasan Penolakan (Wajib jika
                                                ditolak)</label>
                                            <textarea class="form-control @error('reason') is-invalid @enderror"
                                                id="rejection_reason" name="reason" rows="2"
                                                placeholder="Cth: Foto KTP tidak jelas, NIK tidak valid..."></textarea>
                                            @error('reason')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button type="submit" name="decision" value="approve" class="btn btn-success fw-bold">
                                                <i class="fa fa-check"></i> SETUJUI (Verify)
                                            </button>
                                            <button type="submit" name="decision" value="reject" class="btn btn-danger">
                                                <i class="fa fa-times"></i> TOLAK (Reject)
                                            </button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        @else
                            <div class="alert alert-danger text-center">Data Mitra tidak ditemukan atau sudah diverifikasi.
                            </div>
                        @endif
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('admin.partner.verification.index') }}" class="btn btn-secondary">
                            <i class="icon-arrow-left"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection