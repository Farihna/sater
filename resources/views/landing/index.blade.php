@extends('landing.layouts.app') 
@section('title', 'Marketplace Sapi & Kebutuhan Ternak Terlengkap') 
@section('content')
<section id="landing" class="page-section">
    <div class="hero-section text-white text-center py-5">
        <div class="container">
            <h1 class="display-4 fw-bold mt-4 mb-3">Selamat Datang di Sater!</h1>
            <p class="lead mb-4">Gerbang utama Anda untuk segala kebutuhan sapi dari bibit unggul, pakan berkualitas, obat-obatan, hingga perlengkapan ternak lainnya. Sater menghubungkan peternak dengan mitra terpercaya di seluruh Indonesia.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('products.index') }}" class="btn btn-light btn-lg shadow-sm">
                    <i class="bi bi-shop me-2"></i> Jelajahi Produk
                </a>
                <a href="{{ route('partner.register') }}" class="btn btn-outline-light btn-lg shadow-sm"> {{-- Asumsi ada route untuk pendaftaran partner --}}
                    <i class="bi bi-handshake me-2"></i> Gabung Sebagai Mitra
                </a>
            </div>
        </div>
    </div>
    <div class="container py-5">
        <h2 class="section-title text-center mb-5">Apa Itu Sater?</h2>
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <img src="{{ asset('store/img/cover/cover-sapi.jpeg') }}" class="img-fluid rounded shadow-lg" alt="Ilustrasi Peternak Sapi">
            </div>
            <div class="col-md-6">
                <p class="fs-5">
                    Sater hadir sebagai solusi revolusioner bagi industri peternakan sapi di Indonesia. Kami adalah marketplace inovatif yang tidak hanya menjual sapi, tetapi juga menyediakan ekosistem lengkap untuk memenuhi setiap aspek kebutuhan peternakan Anda.
                </p>
                <p class="fs-5">
                    Dari sapi bibit unggul, pakan ternak bernutrisi, obat-obatan berkualitas, hingga peralatan kandang modern, Sater menyatukan seluruh peternak, pemasok, dan ahli ternak dalam satu platform. Tujuan kami adalah menciptakan konektivitas yang efisien, transparan, dan saling menguntungkan untuk memajukan peternakan sapi di tanah air.
                </p>
            </div>
        </div>
    </div>
    <div class="container">
        <h2 class="section-title text-center mb-5">Mengapa Memilih Sater?</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="feature-card card p-4 text-center h-100 shadow-sm">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-award-fill text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Jaminan Kualitas</h4>
                    <p class="text-muted">Semua sapi, pakan, obat, dan produk di Sater telah melalui seleksi ketat dan memenuhi standar kualitas tinggi. Kami bermitra hanya dengan pemasok terpercaya.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card card p-4 text-center h-100 shadow-sm">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-currency-dollar text-warning" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Harga Kompetitif</h4>
                    <p class="text-muted">Dapatkan penawaran terbaik untuk sapi dan kebutuhan ternak lainnya. Kami berkomitmen memberikan nilai maksimal untuk setiap investasi Anda.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card card p-4 text-center h-100 shadow-sm">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-shield-check text-danger" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Transaksi Aman</h4>
                    <p class="text-muted">Sistem pembayaran yang terintegrasi dan aman menjamin setiap transaksi Anda berjalan lancar dan terhindar dari risiko penipuan.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="partner-section py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center mb-5">Keuntungan Bermitra dengan Sater</h2>
            <div class="row g-4 align-items-center">
                <div class="col-md-6">
                    <div class="partner-card card p-4 h-100 shadow-sm">
                        <h4 class="mb-3 text-primary">Mengapa Gabung Sebagai Mitra?</h4>
                        <p class="lead mb-4">Sater bukan hanya marketplace, tetapi juga jembatan kesuksesan bagi bisnis peternakan Anda. Bergabunglah dengan kami dan rasakan manfaatnya:</p>
                        <ul class="list-unstyled partner-benefits">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Jangkauan Pasar Lebih Luas:</strong> Produk Anda akan dilihat oleh ribuan peternak di seluruh Indonesia.</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Manajemen Stok Mudah:</strong> Kelola inventaris Anda secara efisien dengan sistem kami yang intuitif.</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Proses Pembayaran Aman:</strong> Nikmati jaminan pembayaran dan transaksi yang terpercaya.</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Dukungan Pemasaran:</strong> Kami bantu promosikan produk Anda melalui berbagai kanal pemasaran kami.</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Analisis Bisnis Mendalam:</strong> Dapatkan data dan wawasan berharga untuk pertumbuhan bisnis Anda.</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Ekosistem Terintegrasi:</strong> Terhubung dengan sesama peternak dan pemasok dalam jaringan kami.</li>
                        </ul>
                        <a href="{{ route('partner.register') }}" class="btn btn-primary btn-lg w-100 mt-4 shadow">
                            Daftar Mitra Sekarang <i class="bi bi-arrow-right-short ms-2"></i>
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="partner-card card p-4 h-100 shadow-sm">
                        <h4 class="mb-3 text-success">Program Mitra Kami</h4>
                        <p class="mb-4">Bergabunglah dengan jaringan pemasok ternak terpercaya kami dan raih pelanggan di seluruh wilayah. Sebagai mitra, Anda akan merasakan:</p>
                        <div class="row mt-4 text-center">
                            <div class="col-6 mb-3">
                                <h3 class="text-success display-5 fw-bold">500+</h3>
                                <p class="text-muted">Mitra Aktif</p>
                            </div>
                            <div class="col-6 mb-3">
                                <h3 class="text-success display-5 fw-bold">10rb+</h3>
                                <p class="text-muted">Pelanggan Puas</p>
                            </div>
                            <div class="col-6">
                                <h3 class="text-success display-5 fw-bold">24/7</h3>
                                <p class="text-muted">Dukungan Teknis</p>
                            </div>
                            <div class="col-6">
                                <h3 class="text-success display-5 fw-bold">15%</h3>
                                <p class="text-muted">Rata-rata Pertumbuhan Mitra</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection