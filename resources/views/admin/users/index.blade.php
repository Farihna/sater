@extends('admin.layouts.app')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Pengguna</h3>
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
                <a href="#">Pengguna</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Daftar Pengguna</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="partner-verification-table" class="table table-striped table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>NO</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>No HP</th>
                                    <th>Role</th>
                                    <th style="width: 10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach ($users as $user)
                                <tr>
                                    <td  class="text-center">{{ $i++ }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td class="text-center">{{ $user->phone }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'mitra' ? 'warning' : 'success') }}">{{ $user->role }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-button-action">
                                            <a href="" class="btn btn-sm btn-dark"><i class="fa fa-edit"></i></a>
                                            <button type="submit" class="btn btn-sm text-danger" onclick="return confirm('Anda yakin ingin menghapus produk ini?')">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
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