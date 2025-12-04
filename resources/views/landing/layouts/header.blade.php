<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid mx-2">
        <a class="navbar-brand" href="">
            <img src="{{ asset('store/img/...') }}" style="height: 24px;" alt=""> Sater
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('index') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.index') }}">Produk</a>
                </li>
                @auth
                    @if(Auth::user()->isPartner())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('partner.dashboard') }}">Produk Saya</a>
                        </li>
                    @endif
                @endauth
            </ul>
            <ul class="navbar-nav">
                @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('checkout.index') }}">
                        <div class="cart-icon-wrapper">
                            <i class="bi bi-cart3 fs-5"></i>
                            <span class="cart-badge" id="cartCount"></span>
                        </div>
                    </a>
                </li>
                @endauth

                @guest
                <li class="nav-item">
                    <div class="separator-container">
                        <span class="text-link"><a class="nav-link" href="{{ route('login') }}">Masuk</a></span>
                        <span class="separator">|</span>
                        <span class="text-link"><a class="nav-link" href="{{ route('register') }}">Daftar</a></span>
                        <span class="separator">|</span>
                        <span class="text-link"><a class="nav-link" href="{{ route('partner.register') }}">Daftar Mitra</a></span>
                    </div>
                </li>
                @endguest

                @auth
                    <li class="nav-item user-dropdown dropdown">
                        <a class="nav-link" href="#" id="navbarDropdownUser" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false" 
                        style="padding: 0; display: flex; align-items: center; cursor: pointer;">
                            <div class="user-info">
                                <div class="user-avatar" id="userAvatar">{{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 1)) }}</div>
                                <span id="userName">{{ Auth::user()->username ?? 'User' }}</span>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" id="userMenu" aria-labelledby="navbarDropdownUser">
                            <li>
                                <a href="{{ route('profile.settings') }}" class="dropdown-item user-menu-item">
                                    <i class="bi bi-person-gear"></i> Pengaturan Akun
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('my.orders') }}" class="dropdown-item user-menu-item">
                                    <i class="bi bi-box-seam"></i>Pesanan Saya
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a href="{{ route('logout') }}" class="dropdown-item user-menu-item"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i> Keluar
                                </a>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</nav>