<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
                <img src="{{ asset('admin/img/logo-sater-light.png') }}" alt="navbar brand" class="navbar-brand"
                    height="39" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item {{request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard')}}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item {{request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.products.index') }}">
                        <i class="fas fa-weight-hanging"></i>
                        <p>Products</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarLayouts">
                        <i class="fas icon-people"></i>
                        <p>User & Mitra</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="sidebarLayouts">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('admin.users.index') }}">
                                    <span class="sub-item">Pengguna</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.partner.index') }}">
                                    <span class="sub-item">Mitra</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.partner.verification.index') }}">
                                    <span class="sub-item">Verifikasi Mitra</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>