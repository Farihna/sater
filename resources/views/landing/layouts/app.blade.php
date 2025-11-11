<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sater - Home</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('store/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('store/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('store/css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('store/css/nice-select.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('store/css/jquery-ui.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('store/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('store/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('store/css/style.css') }}" type="text/css">
</head>

<body>
    <!-- header -->
    @include('landing.layouts.header')

    <!-- Main Content -->
    @yield('content')

    <!-- footer -->
    @include('landing.layouts.footer')

    <!-- Back to Top -->


    <!-- Js Plugins -->
    <script src="{{ asset('store/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('store/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('store/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('store/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('store/js/jquery.slicknav.js') }}"></script>
    <script src="{{ asset('store/js/mixitup.min.js') }}"></script>
    <script src="{{ asset('store/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('store/js/main.js') }}"></script>
</body>