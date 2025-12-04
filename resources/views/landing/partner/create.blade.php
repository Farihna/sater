@extends('landing.layouts.app')
@section('title', 'Create Product')
@section('content')
    <div class="page-inner ">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h3 class="section-title mb-2">Tambah Produk</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('partner.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label fw-bold">Kategori</label>
                                    <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
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
                                    <label for="nama" class="form-label fw-bold">Nama Produk</label>
                                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                                        value="{{ old('nama') }}" required>
                                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                                    <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror"
                                        required>{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="harga" class="form-label fw-bold">Harga (Rp)</label>
                                    <input type="number" name="harga" id="harga" step="0.01" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga') }}" required>
                                    @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label for="stok" class="form-label fw-bold">Stok Tersedia</label>
                                    <input type="number" name="stok" id="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', 1) }}" required>
                                    @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="image_url" class="form-label fw-bold">Gambar Produk</label>
                                    <input type="file" name="image_url" id="image_url" class="form-control" accept="image/*">
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label fw-bold">Status Produk</label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Tersedia</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Tersedia</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    </select>
                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <div id="detail-fields"></div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('partner.products.index') }}" class="btn btn-danger rounded-3 me-2">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-3">Simpan Produk</button>
                        </div>

                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const categorySelect = document.getElementById('category_id');
                            const detailContainer = document.getElementById('detail-fields');
                            function createInput(label, name, type = 'text', required = false, value = '', step = '') {
                                const old_value = '{{ old(' + name + ', "") }}'; 
                                const is_required = required ? 'required' : '';
                                const step_attr = step ? `step="${step}"` : '';
                                return `
                                    <div class="mb-3">
                                        <label class="form-label">${label}</label>
                                        <input type="${type}" name="${name}" ${step_attr} class="form-control" value="${old_value}" ${is_required}>
                                    </div>
                                `;
                            }

                            function createSelect(label, name, options, required = false, oldValue = '') {
                                let optionHtml = options.map(opt => 
                                    `<option value="${opt.value}" ${opt.value === oldValue ? 'selected' : ''}>${opt.text}</option>`
                                ).join('');
                                return `
                                    <div class="mb-3">
                                        <label class="form-label">${label}</label>
                                        <select name="${name}" class="form-select" ${required ? 'required' : ''}>
                                            ${optionHtml}
                                        </select>
                                    </div>
                                `;
                            }
                            
                            function renderDetailFields(categoryId) {
                                let html = '';
                                detailContainer.innerHTML = ''; 
                                const old_berat = "{{ old('berat') }}";
                                const old_usia = "{{ old('usia') }}";
                                const old_gender = "{{ old('gender') }}";
                                const old_sertifikat = "{{ old('sertifikat_kesehatan') }}";
                                const old_jenisPakan = "{{ old('jenis_pakan') }}";

                                if (categoryId == 1) {
                                    html = `
                                        <hr class="mt-4 mb-4">
                                        <h6 class="fw-bold mb-3">Detail Spesifik Sapi</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                ${createInput('Berat (kg)', 'berat', 'number', true, old_berat, '0.01')}
                                                ${createInput('Usia (Misal: 12 Bulan)', 'usia', 'text', true, old_usia)}
                                            </div>
                                            <div class="col-md-6">
                                                ${createSelect('Gender', 'gender', [
                                                    { value: '', text: '-- Pilih Gender --' },
                                                    { value: 'jantan', text: 'Jantan' },
                                                    { value: 'betina', text: 'Betina' }
                                                ], true, old_gender)}
                                                ${createInput('Sertifikat Kesehatan (jika ada)', 'sertifikat_kesehatan', 'text', false, old_sertifikat)}
                                            </div>
                                        </div>
                                        <hr class="mt-4">
                                    `;
                                } else if (categoryId == 2) {
                                    html = `
                                        <hr class="mt-4 mb-4">
                                        <h6 class="fw-bold mb-3">Detail Spesifik Pakan</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                ${createInput('Berat Kemasan (kg)', 'berat', 'number', true, old_berat, '0.01')}
                                            </div>
                                            <div class="col-md-6">
                                                ${createInput('Jenis Pakan', 'jenis_pakan', 'text', true, old_jenisPakan)}
                                            </div>
                                        </div>
                                        <hr class="mt-4">
                                    `;
                                }
                                detailContainer.innerHTML = html;
                            }
                            categorySelect.addEventListener('change', function () {
                                renderDetailFields(this.value);
                            });
                            const initialCategoryId = categorySelect.value;
                            if (initialCategoryId) {
                                renderDetailFields(initialCategoryId);
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection