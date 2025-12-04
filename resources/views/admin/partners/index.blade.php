@extends('admin.layouts.app')
@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Mitra Sater</h3>
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
                <a href="#">User & Mitra</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Mitra</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Daftar Mitra Terverifikasi</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="partner-verification-table" class="table table-striped table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 5%;">NO</th>
                                    <th>Nama Kontak</th>
                                    <th>Nama Bisnis</th>
                                    <th>Alamat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach ($partners as $partner)
                                <tr>
                                    <td class="text-center">{{ $i++ }}</td>
                                    <td>{{ $partner->user->username }}</td>
                                    <td>{{ $partner->company_name }}</td>
                                    <td>{{ $partner->address }}</td>
                                    <td class="text-center">
                                        <a href="" class="btn btn-sm btn-primary"> Kunjungi Toko</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection