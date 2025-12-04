@extends('landing.layouts.app')
@section('title', 'My Orders')
@section('content')
<section id="my-orders" class="page-section">
    <div class="container py-5">
        <h2 class="section-title">My Orders</h2>
        <div class="orders-history">
            {{-- Pesanan 1: Delivered --}}
            <div class="card mb-3 border-success shadow-sm">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <h6 class="fw-bold mb-0">Order #ORD-2023-1234</h6>
                            <small class="text-muted">Dec 15, 2023</small>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-0 text-truncate" style="max-width: 100%;">Holstein Dairy Cow, Premium Alfalfa Hay</p>
                            <small class="text-muted">2 items</small>
                        </div>
                        <div class="col-md-2">
                            <strong class="text-success">$2,545.00</strong>
                        </div>
                        <div class="col-md-2 text-center">
                            <span class="badge rounded-pill bg-success p-2">Delivered</span>
                        </div>
                        <div class="col-md-1 text-end">
                            <a href="#" class="btn btn-sm btn-primary">View</a>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Pesanan 2: Shipped --}}
            <div class="card mb-3 border-info shadow-sm">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <h6 class="fw-bold mb-0">Order #ORD-2023-1235</h6>
                            <small class="text-muted">Dec 18, 2023</small>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-0 text-truncate" style="max-width: 100%;">Cattle Vitamin Supplement, Anti-parasitic Treatment</p>
                            <small class="text-muted">2 items</small>
                        </div>
                        <div class="col-md-2">
                            <strong>$63.00</strong>
                        </div>
                        <div class="col-md-2 text-center">
                            <span class="badge rounded-pill bg-info text-dark p-2">Shipped</span>
                        </div>
                        <div class="col-md-1 text-end">
                            <a href="#" class="btn btn-sm btn-info">Track</a>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Pesanan 3: Processing --}}
            <div class="card mb-3 border-warning shadow-sm">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <h6 class="fw-bold mb-0">Order #ORD-2023-1236</h6>
                            <small class="text-muted">Dec 20, 2023</small>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-0 text-truncate" style="max-width: 100%;">Angus Beef Cattle</p>
                            <small class="text-muted">1 item</small>
                        </div>
                        <div class="col-md-2">
                            <strong>$3,200.00</strong>
                        </div>
                        <div class="col-md-2 text-center">
                            <span class="badge rounded-pill bg-warning text-dark p-2">Processing</span>
                        </div>
                        <div class="col-md-1 text-end">
                            <a href="#" class="btn btn-sm btn-warning">View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection