@extends('admin.layouts.app')
@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Edit Produk</h3>
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
                    <a href="{{ route('admin.products.index')}}">Products</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Edit</a>
                </li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label>Kategori</label>
                            <select name="category_id" id="category" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Nama Produk</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $product->nama) }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" class="form-control"
                                required>{{ old('deskripsi', $product->deskripsi) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label>Harga</label>
                            <input type="number" name="harga" class="form-control"
                                value="{{ old('harga', $product->harga) }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Gambar Saat Ini</label><br />
                            @if($product->image_url)
                                <img src="{{ asset('storage/' . $product->image_url) }}" alt="" style="max-width:200px;">
                            @else
                                <p>Tidak ada gambar.</p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label>Ganti Gambar (opsional)</label>
                            <input type="file" name="image_url" class="form-control" accept="image/*">
                        </div>

                        @if($product->category_id == 1)
                            <div class="mb-3"><label>Berat (kg)</label>
                                <input type="number" step="0.01" name="berat" class="form-control"
                                    value="{{ old('berat', $product->detailSapi->berat) }}" required>
                            </div>
                            <div class="mb-3"><label>Usia</label>
                                <input type="text" name="usia" class="form-control"
                                    value="{{ old('usia', $product->detailSapi->usia) }}" required>
                            </div>
                            <div class="mb-3"><label>Gender</label>
                                <select name="gender" class="form-select" required>
                                    <option value="jantan" {{ old('gender', $product->detailSapi->gender) == 'jantan' ? 'selected' : '' }}>Jantan</option>
                                    <option value="betina" {{ old('gender', $product->detailSapi->gender) == 'betina' ? 'selected' : '' }}>Betina</option>
                                </select>
                            </div>
                            <div class="mb-3"><label>Sertifikat Kesehatan</label>
                                <input type="text" name="sertifikat_kesehatan" class="form-control"
                                    value="{{ old('sertifikat_kesehatan', $product->detailSapi->sertifikat_kesehatan) }}"
                                    required>
                            </div>

                        @elseif($product->category_id == 2)
                            <div class="mb-3"><label>Berat (kg)</label>
                                <input type="number" step="0.01" name="berat" class="form-control"
                                    value="{{ old('berat', $product->detailPakan->berat) }}" required>
                            </div>
                            <div class="mb-3"><label>Jenis Pakan</label>
                                <input type="text" name="jenis_pakan" class="form-control"
                                    value="{{ old('jenis_pakan', $product->detailPakan->jenis_pakan) }}" required>
                            </div>
                        @endif


                        <button type="submit" class="btn btn-primary rounded-3">Edit</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-danger rounded-3">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection