@extends('landing.layouts.app')
@section('title', 'Products')
@section('content')
<section id="products" class="page-section">
    <div class="container py-5">
        <h2 class="section-title">Our Products</h2>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" id="productSearch" placeholder="Search products...">
                    <button class="btn btn-outline-primary" type="button">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <select class="form-select" id="categoryFilter">
                    <option value="">Kategori</option>
                    <option value="cattle">Sapi</option>
                    <option value="feed">Pakan</option>
                    <option value="medicine">Obat obatan</option>
                    <option value="needs">Kebutuhan Lainnya</option>
                </select>
            </div>
        </div>
        
        <div class="row g-3"> 
            @foreach ($products as $product)
            <div class="col-md-4">
                <div class="product-card card h-100"> 
                    <div class="position-relative">
                        <img src="{{ asset('storage/'. $product->image_url) }}" class="card-img-top product-img" alt="{{ $product->name }}">
                        <span class="product-badge category-badge category-{{ $product->category->nama }}">{{ $product->category->nama }}</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->nama }}</h5>
                        <p class="card-text">{{ $product->deskripsi }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h5 text-success mb-0">Rp{{ number_format($product->harga, 0, ',', '.') }}</span>
                            <span class="badge bg-info">In Stock: {{ $product->stok }}</span>
                        </div>
                        @if($product->category->nama == 'sapi')
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('products.details', $product) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="https://wa.me/628813860081" target="blank" class="py-1 px-3 rounded-2 bg-success text-white">
                                <i class="bi bi-whatsapp fs-5"></i>
                            </a>
                        </div>
                        @else
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('products.details', $product) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <form class="add-to-cart-form" method="POST" action="{{ route('cart.add', $product->id) }}">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="bi bi-cart-plus"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection