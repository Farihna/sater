@extends('landing.layouts.app')
@section('title', 'Partner Dashboard')
@section('content')
    <section id="partner" class="page-section">
        <div class="container py-5">
            <h2 class="section-title">Dashboard</h2>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Status</h5>
                            <div class="row text-center">
                                <div class="col-4">
                                    <h4 id="activeProducts">{{ $products->where('status', 'active')->count() }}</h4>
                                    <p class="small text-muted">Aktif</p>
                                </div>
                                <div class="col-4">
                                    <h4 id="pendingProducts">{{ $products->where('status', 'pending')->count() }}</h4>
                                    <p class="small text-muted">Pending</p>
                                </div>
                                <div class="col-4">
                                    <h4 id="outOfStock">{{ $products->where('stok', 0)->count() }}</h4>
                                    <p class="small text-muted">Habis</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Aksi</h5>
                            <a href="{{ route('partner.products.create') }}" class="btn btn-primary me-2">
                                <i class="bi bi-plus-circle"></i> Tambah Produk
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-container">
                <h5 class="mb-3">Produk Saya</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="text-center">
                            <tr>
                                <th>NO</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @php $i=1; @endphp
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $product->nama }}</td>
                                    <td>
                                        <span class="category-badge category-{{ strtolower($product->category->nama) }}">
                                            {{ strtolower($product->category->nama) }}
                                        </span>
                                    </td>
                                    <td>{{ 'Rp' . number_format($product->harga, 0, ',', '.') }}</td>
                                    <td>{{ $product->stok }}</td>
                                    <td>
                                        @if($product->status === 'active')
                                            <span class="badge bg-success">active</span>
                                        @elseif($product->status === 'pending')
                                            <span class="badge bg-warning text-dark">pending</span>
                                        @elseif($product->status === 'inactive')
                                            <span class="badge bg-secondary">inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('partner.products.edit', $product) }}" class="action-btn edit-btn"><i class="bi bi-pencil-fill"></i></a>
                                        <form action="{{ route('partner.products.destroy', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this product?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bi bi-box-seam me-2"></i> Belum ada produk terdaftar. Silakan tambahkan produk baru!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection