@extends('admin.layouts.app')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Tambah Produk</h3>
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
                    <a href="{{ route('admin.products.index') }}">Products</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Tambah</a>
                </li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3 form-group form-group-default">
                            <label>Kategori</label>
                            <select name="category_id" id="category_id"
                                class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label>Nama Produk</label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}" required>
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label>Harga</label>
                            <input type="number" name="harga" step="0.01" class="form-control" value="{{ old('harga') }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Stok</label>
                            <input type="number" name="stok" class="form-control" value="{{ old('stok', 1) }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Gambar</label>
                            <input type="file" name="image_url" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Tersedia</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Tersedia</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>

                        <div id="detail-fields"></div>

                        <button type="submit" class="btn btn-primary rounded-3">Simpan</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-danger rounded-3">Batal</a>
                    </form>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const categorySelect = document.getElementById('category_id');
                            const detailContainer = document.getElementById('detail-fields');

                            function renderDetailFields(categoryId) {
                                let html = '';

                                if (categoryId == 1) {
                                    html = `s
                                            <div class="mb-3"><label>Berat (kg)</label>
                                                <input type="number" step="0.01" name="berat" class="form-control" required>
                                            </div>
                                            <div class="mb-3"><label>Usia</label>
                                                <input type="text" name="usia" class="form-control" required>
                                            </div>
                                            <div class="mb-3"><label>Gender</label>
                                                <select name="gender" class="form-select" required>
                                                    <option value="">-- Pilih Gender --</option>
                                                    <option value="jantan">Jantan</option>
                                                    <option value="betina">Betina</option>
                                                </select>
                                            </div>
                                            <div class="mb-3"><label>Sertifikat Kesehatan</label>
                                                <input type="text" name="sertifikat_kesehatan" class="form-control" required>
                                            </div>
                                        `;
                                } else if (categoryId == 2) {
                                    html = `
                                        <div class="mb-3"><label>Berat (kg)</label>
                                            <input type="number" name="berat" step="0.01" class="form-control" required>
                                        </div>
                                        <div class="mb-3"><label>Jenis Pakan</label>
                                            <input type="text" name="jenis_pakan" class="form-control" required>
                                        </div>
                                    `;
                                }

                                detailContainer.innerHTML = html;
                            }
                            renderDetailFields(categorySelect.value);
                            categorySelect.addEventListener('change', function () {
                                renderDetailFields(this.value);
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection