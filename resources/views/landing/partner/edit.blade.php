@extends('landing.layouts.app')
@section('title', 'Edit Product - ' . $product->nama)
@section('content')
<div class="page-inner ">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="section-title mb-2">Edit Produk : {{ $product->nama }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('partner.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') 
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-bold">Kategori</label>
                                <select name="category_id" id="category_id" class="form-select" disabled>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="category_id" value="{{ $product->category_id }}">
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label fw-bold">Nama Produk</label>
                                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama', $product->nama) }}" required>
                                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror"
                                    required>{{ old('deskripsi', $product->deskripsi) }}</textarea>
                                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="harga" class="form-label fw-bold">Harga (Rp)</label>
                                <input type="number" name="harga" id="harga" step="0.01" class="form-control @error('harga') is-invalid @enderror" 
                                    value="{{ old('harga', $product->harga) }}" required>
                                @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="stok" class="form-label fw-bold">Stok Tersedia</label>
                                <input type="number" name="stok" id="stok" class="form-control @error('stok') is-invalid @enderror" 
                                    value="{{ old('stok', $product->stok) }}" required>
                                @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="image_url" class="form-label fw-bold">Gambar Produk Saat Ini</label>
                                @if ($product->image_url)
                                    <div class="mb-2"><img src="{{ asset('storage/' . $product->image_url) }}" width="100"></div>
                                @endif
                                <input type="file" name="image_url" id="image_url" class="form-control" accept="image/*">
                                <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label fw-bold">Status Produk</label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Tidak Tersedia</option>
                                    <option value="pending" {{ old('status', $product->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    @if($product->category_id == 1)
                    <hr class="mt-4 mb-4">
                    <h6 class="fw-bold mb-3">Detail Spesifik Sapi</h6>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3"><label>Berat (kg)</label>
                                        <input type="number" step="0.01" name="berat" class="form-control"
                                            value="{{ old('berat', optional($product->detailSapi)->berat) }}" required>
                                        @error('berat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="mb-3"><label>Usia</label>
                                        <input type="text" name="usia" class="form-control"
                                            value="{{ old('usia', optional($product->detailSapi)->usia) }}" required>
                                        @error('usia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3"><label>Gender</label>
                                        <select name="gender" class="form-select" required>
                                            <option value="jantan" {{ old('gender', optional($product->detailSapi)->gender) == 'jantan' ? 'selected' : '' }}>Jantan</option>
                                            <option value="betina" {{ old('gender', optional($product->detailSapi)->gender) == 'betina' ? 'selected' : '' }}>Betina</option>
                                        </select>
                                        @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="mb-3"><label>Sertifikat Kesehatan</label>
                                        <input type="text" name="sertifikat_kesehatan" class="form-control"
                                            value="{{ old('sertifikat_kesehatan', optional($product->detailSapi)->sertifikat_kesehatan) }}">
                                        @error('sertifikat_kesehatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($product->category_id == 2)
                    <hr class="mt-4 mb-4">
                    <h6 class="fw-bold mb-3">Detail Spesifik Pakan</h6>
                    <div class="row"></div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3"><label>Berat Kemasan (kg)</label>
                                            <input type="number" step="0.01" name="berat" class="form-control"
                                                value="{{ old('berat', optional($product->detailPakan)->berat) }}" required>
                                            @error('berat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3"><label>Jenis Pakan</label>
                                            <input type="text" name="jenis_pakan" class="form-control"
                                                value="{{ old('jenis_pakan', optional($product->detailPakan)->jenis_pakan) }}" required>
                                            @error('jenis_pakan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="mt-4">
                        @endif
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('partner.products.index') }}" class="btn btn-danger rounded-3 me-2">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-3">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection