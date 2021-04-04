<header class="main-nav">
    <div class="logo-wrapper">PT Dira Utama Jaya
        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
        <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="grid" id="sidebar-toggle"> </i></div>
    </div>
    <div class="logo-icon-wrapper"><a href="index.html"><img class="img-fluid" src="../assets/images/logo/logo-icon.png" alt=""></a></div>
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn"><a href="index.html"><img class="img-fluid" src="../assets/images/logo/logo-icon.png" alt=""></a>
                        <div class="mobile-back text-right"><span>Back</span><i class="fa fa-angle-right pl-2" aria-hidden="true"></i></div>
                    </li>

                    <li class="dropdown"><a class="nav-link menu-title link-nav" href="{{route('home')}}"><i data-feather="home"> </i><span>Dashboard</span></a></li>
                    <li class="dropdown"><a class="nav-link menu-title link-nav" href="{{route('user.index')}}"><i data-feather="user"> </i><span>Profile</span></a></li>
                    <li class="dropdown"><a class="nav-link menu-title" href="#"><i data-feather="menu"> </i><span>Kelola Infrastruktur</span></a>
                        <ul class="nav-submenu menu-content">
                            @if (auth()->user()->role != 'teknisi')
                                <li><a href="{{route('infra.index')}}">Infrastruktur</a></li>
                            @endif
                            {{-- @if (auth()->user()->role != 'teknisi') --}}
                            <li><a href="{{route('serviceInfra.index')}}">Service Infrastruktur</a></li>
                            {{-- @endif --}}
                        </ul>
                    </li>
                    <li class="dropdown"><a class="nav-link menu-title" href="#"><i data-feather="file-text"> </i><span>Kelola Aftersale</span></a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="{{route('after.index')}}">Aftersale</a></li>
                        </ul>
                    </li>
                    @if (auth()->user()->role != 'teknisi')

                        @if (auth()->user()->role == 'admin')
                            <li class="dropdown"><a class="nav-link menu-title link-nav" href="{{route('user.all')}}"><i data-feather="users"></i><span>User</span></a></li>
                            <li class="dropdown"><a class="nav-link menu-title link-nav" href="{{route('log.index')}}"><i data-feather="file-text"></i><span>Riwayat aktivitas</span></a></li>
                            <li class="dropdown"><a class="nav-link menu-title link-nav" href="{{route('barang.index')}}"><i data-feather="file-text"></i><span>Semua Barang</span></a></li>
                        @endif
                        @if (auth()->user()->role == 'head')
                            <li class="dropdown"><a class="nav-link menu-title link-nav" href="{{route('barcode.edit')}}"><i data-feather="check-square"> </i><span>Aktifasi Barcode</span></a></li>
                        @endif
                        <li class="dropdown"><a class="nav-link menu-title link-nav" href="{{route('barcode.terjual')}}"><i data-feather="box"> </i><span>barang terjual</span></a></li>
                        <li class="dropdown"><a class="nav-link menu-title " href="#"><i data-feather="box"> </i><span>Kelola Barang</span></a>
                            <ul class="nav-submenu menu-content">
                                <li><a href="{{route('barang.create')}}">Nama Barang</a></li>
                                @if (auth()->user()->role != 'teknisi')
                                    <li><a href="{{route('masuk.index')}}">List Barang Masuk</a></li>
                                @endif
                                @if (auth()->user()->role == 'head')
                                    <li><a href="{{route('barang.index')}}">semua barang</a></li>
                                    <li><a href="{{route('barcode.terjual','all')}}">semua barang terjual</a></li>

                                @endif
                            </ul>
                        </li>
                        <li class="dropdown"><a class="nav-link menu-title" href="#"><i data-feather="archive"> </i><span>Kelola Mutasi</span></a>
                            <ul class="nav-submenu menu-content">
                                @if (auth()->user()->role == 'head')
                                    <li><a href="{{route('mutasi.create')}}">Mutasi</a></li>

                                @endif
                                {{-- @if (auth()->user()->role != 'teknisi') --}}
                                <li><a href="{{route('mutasi.index')}}">Riwayat Mutasi</a></li>
                                {{-- @endif --}}
                            </ul>
                        </li>
                        {{-- teknisi --}}
                        {{-- end teknisi --}}
                        <li class="dropdown"><a class="nav-link menu-title link-nav" href="{{route('gudang.index')}}"><i data-feather="monitor"> </i><span>Kelola Gudang</span></a></li>
                        <li class="dropdown"><a class="nav-link menu-title link-nav" href="{{route('suplier.index')}}"><i data-feather="list"> </i><span>Kelola Suplier</span></a></li>
                @endif

            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
