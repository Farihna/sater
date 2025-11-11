@extends('landing.layouts.app')

@section('content')
<!-- Featured Section Begin -->
<section class="featured spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2>Produk Unggulan</h2>
                </div>
                <div class="featured__controls">
                    <ul>
                        <li class="active" data-filter="*">All</li>
                        <li data-filter=".sapi">Sapi</li>
                        <li data-filter=".pakan">Pakan Sapi</li>
                        <li data-filter=".peralatan">Kebutuhan Sapi</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row featured__filter">
            @foreach ($products as $product)

            <div class="col-lg-3 col-md-4 col-sm-6 mix {{ $product->category->nama }}">
                <div class="featured__item">
                    <div class="featured__item__pic set-bg" data-setbg="{{ asset('/storage/'.($product->image_url ?: 'products/no_image.jpg')) }}">
                        <ul class="featured__item__pic__hover">
                            <li><a href="#"><i class="fa fa-heart"></i></a></li>
                            <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                            <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                        </ul>
                    </div>
                    <div class="featured__item__text">
                        <h6><a href="#">{{ $product->nama }}</a></h6>
                        <h5>Rp{{ number_format($product->harga, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- Featured Section End -->
@endsection