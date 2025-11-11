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
                    <input type="radio" name="category_filter" value="pakan"
                        class="selectgroup-input category-radio" />
                    <span class="selectgroup-button">Pakan Sapi</span>
                </label>
                <label class="selectgroup-item">
                    <input type="radio" name="category_filter" value="peralatan"
                        class="selectgroup-input category-radio" />
                    <span class="selectgroup-button">Peralatan Sapi</span>
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
                                <!-- @php $i = 1; @endphp
                                
                                @foreach ($products as $product)

                                    @if($product->category->nama === 'sapi' && $product->detailSapi)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>
                                            @if ($product->image_url)
                                                <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->nama }}"
                                                    width="50">
                                            @endif
                                        </td>
                                        <td>{{ $product->nama }}</td>
                                        <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                                        <td>
                                            {{ $product->stok }}
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.products.edit', $product->id) }}"><i
                                                    class="fa fa-edit"></i></a>
                                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure to delete this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm text-danger"><i
                                                        class="fa fa-times"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach -->
                            </tbody>
                        </table>
                        <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const radios = document.querySelectorAll('.category-radio');
                            const productBody = document.querySelector('#productBody');
                            const cardTitle = document.getElementById('card-title');

                            // Fungsi untuk memuat produk berdasarkan kategori
                            const loadProducts = (category) => {
                                console.log('Kategori dipilih:', category);
                                productBody.innerHTML = `<tr><td colspan="6" class="text-center">Loading...</td></tr>`;
                                cardTitle.innerText = `Daftar Produk - ${category.toUpperCase()}`;

                                fetch(`{{ route('admin.products.filter') }}?category=${category}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log(data);
                                        if (data.products && data.products.length > 0) {
                                            let html = '';
                                            data.products.forEach((p, index) => {
                                                const imageUrl = p.image_url ? `/storage/${p.image_url}` : '/storage/products/no_image.jpg';

                                                let detail = '';
                                                if (p.detail_sapi) {
                                                    detail = `
                                                        <small>Berat: ${p.detail_sapi.berat} kg</small><br>
                                                        <small>Usia: ${p.detail_sapi.usia}</small><br>
                                                        <small>Gender: ${p.detail_sapi.gender}</small><br>
                                                        <small>Sertifikat: ${p.detail_sapi.sertifikat_kesehatan}</small>
                                                    `;
                                                } else if (p.detail_pakan) {
                                                    detail = `
                                                        <small>Jenis Pakan: ${p.detail_pakan.jenis_pakan}</small><br>
                                                        <small>Berat: ${p.detail_pakan.berat} kg</small>
                                                    `;
                                                } else if (p.detail_peralatan) {
                                                    detail = `
                                                        <small>Jenis: ${p.detail_peralatan.jenis_peralatan}</small><br>
                                                        <small>Bahan: ${p.detail_peralatan.bahan}</small>
                                                    `;
                                                }

                                                html += `
                                                    <tr>
                                                        <td>${index + 1}</td>
                                                        <td><img src="${imageUrl}" alt="${p.nama}" width="50"></td>
                                                        <td><b>${p.nama}</b><br>${detail}<br>${p.deskripsi}</td>
                                                        <td>Rp ${parseInt(p.harga).toLocaleString('id-ID')}</td>
                                                        <td>${p.stok}</td>
                                                        <td>
                                                            <a href="/dashboard/products/${p.id}/edit"><i class="fa fa-edit"></i></a>
                                                            <form action="/dashboard/products/${p.id}" method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm text-danger" onclick="return confirm('Yakin ingin menghapus?')"><i class="fa fa-times"></i></button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                `;
                                            });
                                            productBody.innerHTML = html;
                                        } else {
                                            productBody.innerHTML = `<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>`;
                                        }
                                    })
                                    .catch(err => {
                                        console.error(err);
                                        productBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Gagal memuat data</td></tr>`;
                                    });
                            };

                            // Pasang event listener
                            radios.forEach(radio => {
                                radio.addEventListener('change', function() {
                                    loadProducts(this.value);
                                });
                            });

                            // 🔹 Load default kategori “Sapi” (atau radio yang checked)
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