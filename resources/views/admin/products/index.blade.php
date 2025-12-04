@extends('admin.layouts.app')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Daftar Produk</h3>
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
                    <a href="#">Products</a>
                </li>
            </ul>
        </div>

        <div class="form-group">
            <div class="selectgroup" id="category-selector">
                <label class="selectgroup-item">
                    <input type="radio" name="category_filter" value="sapi" class="selectgroup-input category-radio"
                        checked />
                    <span class="selectgroup-button">Sapi</span>
                </label>
                <label class="selectgroup-item">
                    <input type="radio" name="category_filter" value="pakan" class="selectgroup-input category-radio" />
                    <span class="selectgroup-button">Pakan Sapi</span>
                </label>
                <label class="selectgroup-item">
                    <input type="radio" name="category_filter" value="obat" class="selectgroup-input category-radio" />
                    <span class="selectgroup-button">Obat Sapi</span>
                </label>
                <label class="selectgroup-item">
                    <input type="radio" name="category_filter" value="kebutuhan" class="selectgroup-input category-radio" />
                    <span class="selectgroup-button">Kebutuhan Sapi</span>
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title" id="card-title"></h4>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-round ms-auto">
                            <i class="fa fa-plus"></i>
                            Tambah
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive rounded-1">
                        <table id="add-row" class="display table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Gambar</th>
                                    <th>Detail</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th style="width: 10%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="productBody">
                                <!-- Data produk akan dimuat di sini melalui JavaScript -->
                            </tbody>
                        </table>
                        <script>
                            // Variabel Laravel yang HARUS diproses oleh Blade sebelum JS dieksekusi
                            const FILTER_ROUTE = '{{ route('admin.products.filter') }}';
                            const CSRF_TOKEN = '{{ csrf_token() }}';
                            const BASE_URL = '/dashboard/products/';
                        </script>
                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                const radios = document.querySelectorAll('.category-radio');
                                const productBody = document.querySelector('#productBody');
                                const cardTitle = document.getElementById('card-title');

                                // Pastikan elemen global seperti FILTER_ROUTE dan CSRF_TOKEN sudah dideklarasikan di luar script.

                                // Fungsi untuk memuat produk berdasarkan kategori
                                const loadProducts = (category) => {
                                    console.log('Kategori dipilih:', category);

                                    // 1. Loading State
                                    productBody.innerHTML = `<tr><td colspan="6" class="text-center">Loading...</td></tr>`;
                                    cardTitle.innerText = `Daftar Produk - ${category.toUpperCase()}`;

                                    // 2. Fetch Data (Menggunakan variabel global FILTER_ROUTE)
                                    fetch(`${FILTER_ROUTE}?category=${category}`)
                                        .then(response => {
                                            if (!response.ok) {
                                                throw new Error(`HTTP error! status: ${response.status}`);
                                            }
                                            return response.json();
                                        })
                                        .then(data => {
                                            console.log('Data diterima:', data);

                                            if (data.products && data.products.length > 0) {
                                                let html = '';

                                                data.products.forEach((p, index) => {
                                                    const imageUrl = p.image_url ? `/storage/${p.image_url}` : '/storage/products/no_image.jpg';

                                                    let detail = '';
                                                    if (p.detail_sapi) {
                                                        detail = `
                                                            <small>Berat: ${p.detail_sapi.berat || 'N/A'} kg</small><br>
                                                            <small>Usia: ${p.detail_sapi.usia || 'N/A'}</small><br>
                                                            <small>Gender: ${p.detail_sapi.gender || 'N/A'}</small><br>
                                                            <small>Sertifikat: ${p.detail_sapi.sertifikat_kesehatan || 'Tidak Ada'}</small>
                                                        `;
                                                    } else if (p.detail_pakan) {
                                                        detail = `
                                                            <small>Jenis Pakan: ${p.detail_pakan.jenis_pakan || 'N/A'}</small><br>
                                                            <small>Berat: ${p.detail_pakan.berat || 'N/A'} kg</small>
                                                        `;
                                                    } else if (p.detail_obat) {
                                                        detail = `
                                                            <small>Jenis: ${p.detail_obat.jenis_obat || 'N/A'}</small><br>
                                                            <small>Bahan: ${p.detail_obat.bahan || 'N/A'}</small>
                                                        `;
                                                    } else if (p.detail_peralatan) {
                                                        detail = `
                                                            <small>Jenis: ${p.detail_peralatan.jenis_peralatan || 'N/A'}</small><br>
                                                            <small>Bahan: ${p.detail_peralatan.bahan || 'N/A'}</small>
                                                        `;
                                                    }

                                                    // Render Baris Tabel
                                                    html += `
                                                        <tr>
                                                            <td>${index + 1}</td>
                                                            <td><img src="${imageUrl}" alt="${p.nama}" width="50" class="img-thumbnail"></td>
                                                            <td>
                                                                <b>${p.nama}</b><br>
                                                                ${detail}<br>
                                                                <small>${p.deskripsi.substring(0, 50)}...</small>
                                                            </td>
                                                            <td>Rp ${Number(p.harga).toLocaleString('id-ID')}</td>
                                                            <td>${p.stok}</td>
                                                            <td>
                                                                <a href="${BASE_URL}${p.id}/edit" class="btn btn-sm btn-info me-1"><i class="fa fa-edit"></i></a>

                                                                <form action="${BASE_URL}${p.id}" method="POST" style="display:inline;">
                                                                    <input type="hidden" name="_token" value="${CSRF_TOKEN}"> 
                                                                    <input type="hidden" name="_method" value="DELETE"> 

                                                                    <button type="submit" class="btn btn-sm text-danger" onclick="return confirm('Anda yakin ingin menghapus produk ini?')">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    `;
                                                });

                                                productBody.innerHTML = html;

                                            } else {
                                                // Tidak ada data
                                                productBody.innerHTML = `<tr><td colspan="6" class="text-center">Tidak ada data produk di kategori ini.</td></tr>`;
                                            }
                                        })
                                        .catch(err => {
                                            console.error("Fetch Error:", err);
                                            productBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Gagal memuat data. Periksa koneksi atau server.</td></tr>`;
                                        });
                                };

                                // Pasang event listener
                                radios.forEach(radio => {
                                    radio.addEventListener('change', function () {
                                        loadProducts(this.value);
                                    });
                                });

                                // 🔹 Load default kategori saat halaman dimuat
                                const checkedRadio = document.querySelector('.category-radio:checked');
                                if (checkedRadio) {
                                    loadProducts(checkedRadio.value);
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection