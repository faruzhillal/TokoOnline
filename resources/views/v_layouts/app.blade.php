<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('image/icon_univ_bsi.png') }}">
    <title>tokoonline</title>
    <link href="https://fonts.googleapis.com/css?family=Hind:400,700" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/slick.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/slick-theme.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/nouislider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <header>
        <div id="top-header">
            <div class="container">
                <div class="pull-left">
                    <span>Selamat datang di toko online</span>
                </div>
            </div>
        </div>
        <div id="header">
            <div class="container">
                <div class="pull-left">
                    <div class="header-logo">
                        <a class="logo" href="#">
                            <img src="{{ asset('image/logo.png') }}" alt="">
                        </a>
                    </div>
                </div>
                <div class="pull-right">
                    <ul class="header-btns">
                        <li class="header-cart dropdown default-dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                <div class="header-btns-icon">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                                <strong class="text-uppercase">Keranjang</strong>
                            </a>
                        </li>
                        @if (Auth::check())
                        <!-- Account -->
                        <li class="header-account dropdown default-dropdown">
                            <div class="dropdown-toggle" role="button" data-toggle="dropdown" aria-expanded="true">
                                <div class="header-btns-icon">
                                    <i class="fa fa-user-o"></i>
                                </div>
                                <strong class="text-uppercase">{{ Auth::user()->nama }}<i
                                        class="fa fa-caret-down"></i></strong>
                            </div>
                            <ul class="custom-menu">
                                <li>
                                    <a href="{{ route('customer.akun', ['id' => Auth::user()->id]) }}">
                                        <i class="fa fa-user-o"></i> Akun Saya
                                    </a>
                                </li>
                                <li><a href="#"><i class="fa fa-check"></i> History</a></li>
                                <li>
                                    <a href="#"
                                        onclick="event.preventDefault(); document.getElementById('keluar-app').submit();"><i
                                            class="fa fa-power-off"></i> Keluar
                                    </a>
                                    <!-- form keluar app -->
                                    <form id="keluar-app" action="{{ route('customer.logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                    <!-- form keluar app end -->
                                </li>
                            </ul>
                        </li>
                        @else
                        <li class="header-account dropdown default-dropdown">
                            <div class="dropdown-toggle" role="button" data-toggle="dropdown" aria-expanded="true">
                                <div class="header-btns-icon">
                                    <i class="fa fa-user-o"></i>
                                </div>
                                <strong class="text-uppercase">Akun Saya<i class="fa fa-caret-down"></i></strong>
                            </div>
                            <a href="{{ route('auth.redirect') }}" class="text-uppercase">Login</a>
                        </li>
                        <!-- /Account -->
                        @endif
                        <li class="nav-toggle">
                            <button class="nav-toggle-btn main-btn icon-btn"><i class="fa fa-bars"></i></button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <div id="navigation">
        <div class="container">
            <div id="responsive-nav">
                @php
                // Pindahkan query kategori ke bagian atas agar bisa digunakan di semua kondisi
                $kategori = DB::table('kategori')->orderBy('nama_kategori', 'asc')->get();
                @endphp

                @if (request()->segment(1) == '' || request()->segment(1) == 'beranda')
                <div class="category-nav">
                    <span class="category-header">Kategori <i class="fa fa-list"></i></span>
                    <ul class="category-list">
                        @foreach ($kategori as $row)
                        <li><a href="{{ route('produk.kategori', $row->id) }}">{{ $row->nama_kategori }}</a></li>
                        @endforeach
                    </ul>
                </div>
                @else
                <div class="category-nav show-on-click">
                    <span class="category-header">Kategori <i class="fa fa-list"></i></span>
                    <ul class="category-list">
                        @foreach ($kategori as $row)
                        <li><a href="{{ route('produk.kategori', $row->id) }}">{{ $row->nama_kategori }}</a></li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="menu-nav">
                    <span class="menu-header">Menu <i class="fa fa-bars"></i></span>
                    <ul class="menu-list">
                        <li><a href="{{ route('beranda') }}">Beranda</a></li>
                        <li><a href="{{ route('produk.all') }}">Produk</a></li>
                        <li><a href="#">Lokasi</a></li>
                        <li><a href="#">Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @if (request()->segment(1) == '' || request()->segment(1) == 'beranda')
    <div id="home">
        <div class="container">
            <div class="home-wrap">
                <div id="home-slick">
                    <div class="banner banner-1">
                        <img src="{{ asset('frontend/banner/banner01.jpg') }}" alt="">
                        <div class="banner-caption text-center">
                            <h1>Jajanan Tradisional</h1>
                            <h3 class="font-weak" style="color: #30323a;">Khas Makanan Indonesia</h3>
                            <button class="primary-btn">Pesan Sekarang</button>
                        </div>
                    </div>
                    <div class="banner banner-1">
                        <img src="{{ asset('frontend/banner/banner02.jpg') }}" alt="">
                        <div class="banner-caption">
                            <h1 class="primary-color">Khas Makanan Indonesia<br><span
                                    class="white-color font-weak">Jajanan Tradisional</span></h1>
                            <button class="primary-btn">Pesan Sekarang</button>
                        </div>
                    </div>
                    <div class="banner banner-1">
                        <img src="{{ asset('frontend/banner/banner03.jpg') }}" alt="">
                        <div class="banner-caption">
                            <h1 style="color: #f8694a;">Khas Makanan <span>Indonesia</span></h1>
                            <button class="primary-btn">Pesan Sekarang</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="section">
        <div class="container">
            <div class="row">
                <div id="aside" class="col-md-3">
                    <div class="aside">
                        <h3 class="aside-title">Top Rated Product</h3>
                        <div class="product product-widget">
                            <div class="product-thumb">
                                <img src="{{ asset('frontend/img/thumb-product01.jpg') }}" alt="">
                            </div>
                            <div class="product-body">
                                <h2 class="product-name"><a href="#">Product Name Goes Here</a></h2>
                                <h3 class="product-price">$32.50 <del class="product-oldprice">$45.00</del></h3>
                                <div class="product-rating">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star-o empty"></i>
                                </div>
                            </div>
                        </div>
                        <div class="product product-widget">
                            <div class="product-thumb">
                                <img src="{{ asset('frontend/img/thumb-product01.jpg') }}" alt="">
                            </div>
                            <div class="product-body">
                                <h2 class="product-name"><a href="#">Product Name Goes Here</a></h2>
                                <h3 class="product-price">$32.50</h3>
                                <div class="product-rating">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star-o empty"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="aside">
                        <h3 class="aside-title">Filter Kategori</h3>
                        <ul class="list-links">
                            @foreach ($kategori as $row)
                            <li><a href="{{ route('produk.kategori', $row->id) }}">{{ $row->nama_kategori }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div id="main" class="col-md-9">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <footer id="footer" class="section section-grey">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="footer">
                        <div class="footer-logo">
                            <a class="logo" href="#">
                                <img src="./img/logo.png" alt="">
                            </a>
                        </div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna</p>
                        <ul class="footer-social">
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                            <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                            <li><a href="#"><i class="fa fa-pinterest"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="footer">
                        <h3 class="footer-header">My Account</h3>
                        <ul class="list-links">
                            <li><a href="#">My Account</a></li>
                            <li><a href="#">My Wishlist</a></li>
                            <li><a href="#">Compare</a></li>
                            <li><a href="#">Checkout</a></li>
                            <li><a href="#">Login</a></li>
                        </ul>
                    </div>
                </div>
                <div class="clearfix visible-sm visible-xs"></div>
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="footer">
                        <h3 class="footer-header">Customer Service</h3>
                        <ul class="list-links">
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Shipping & Return</a></li>
                            <li><a href="#">Shipping Guide</a></li>
                            <li><a href="#">FAQ</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="footer">
                        <h3 class="footer-header">Stay Connected</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.</p>
                        <form>
                            <div class="form-group">
                                <input class="input" placeholder="Enter Email Address">
                            </div>
                            <button class="primary-btn">Join Newsletter</button>
                        </form>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
                    <div class="footer-copyright">
                        Copyright &copy;<script>
                            document.write(new Date().getFullYear());
                        </script> All rights reserved | This template is made with <i class="fa fa-heart-o"
                            aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="{{ asset('frontend/js/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('frontend/js/slick.min.js') }}"></script>
    <script src="{{ asset('frontend/js/nouislider.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.zoom.min.js') }}"></script>
    <script src="{{ asset('frontend/js/main.js') }}"></script>
</body>

</html>