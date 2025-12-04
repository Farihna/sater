@extends('landing.layouts.app')
@section('title', 'Details '. $product->nama)
@section('content')
<section id="product-detail" class="page-section">
    <div class="container py-5">
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary mb-4">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <div class="row">
            <div class="col-md-6">
                <img src="{{ asset('storage/' . $product->image_url) }}" id="mainImage" class="product-detail-img mainImage w-100" style="height:400px;" alt="{{ $product->name }}">
                <div class="product-gallery">
                    <img src="{{ asset('storage/' . $product->image_url) }}" class="gallery-thumb active">
                    <img src="{{ asset('storage/' . $product->image_url) }}" class="gallery-thumb">
                    <img src="{{ asset('storage/' . $product->image_url) }}" class="gallery-thumb">
                </div>
            </div>
            <div class="col-md-6">
                <h2>{{ $product->nama }}</h2>
                <div class="mb-3">
                    <span class="category-badge category-{{ $product->category->nama }}">{{ $product->category->nama }}</span>
                    <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}">{{ $product->status }}</span>
                </div>
                <h3 class="text-success mb-3">Rp {{ number_format($product->harga, 0, ',', '.') }}</h3>
                <p class="mb-4">{{ $product->deskripsi }}</p>

                <div class="mb-4">
                    <h5>Detail Produk</h5>
                    <ul class="list-unstyled">
                        <li><strong>Kategori:</strong> {{ $product->category->nama }}</li>
                        <li><strong>Stok:</strong> {{ $product->stok }} units available</li>
                        <li><strong>Status:</strong> {{ $product->status }}</li>
                    </ul>
                </div>

                @if(!($product->category->nama == 'sapi'))
                <form class="add-to-cart-form" id="mainCartForm" method="POST" action="{{ route('cart.add', $product->id) }}">
                    @csrf
                    <div class="d-flex gap-3 mb-4">
                        <div class="quantity-selector">
                            <button type="button" class="quantity-btn">-</button>
                            <input type="number" name="quantity" class="form-control text-center" id="productQuantity" value="1" min="1" max="{{ $product->stok }}" style="width: 60px;">
                            <button type="button" class="quantity-btn">+</button>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-cart-plus"></i> Masukkan Keranjang
                        </button>
                    </div>
                </form>
                @endif

                @if($product->category->nama == 'sapi')
                <a href="https://wa.me/628813860081" target="_blank" class="btn btn-success btn-lg w-100 my-2">
                    <i class="bi bi-whatsapp fs-5"></i> Beli Sekarang
                </a>    
                @else
                <button id="buyNowBtn" class="btn btn-success btn-lg w-100 my-2"> 
                    <i class="bi bi-wallet2"></i> Beli Sekarang
                </button>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection