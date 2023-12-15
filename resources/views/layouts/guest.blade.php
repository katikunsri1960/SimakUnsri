<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="upt-tik-unsri">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="images/unsri.png">

    <title>{{ config('app.name', 'SIAKAD') }} - @yield('title')</title>

	<!-- Vendors Style-->
	<link rel="stylesheet" href="{{asset('assets/css/vendors_css.css')}}">

	<!-- Style-->
	<link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/skin_color.css')}}">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="hold-transition theme-primary bg-img" style="background-image: url(images/bg-1.jpg)">

    @yield('content')

	<!-- Vendor JS -->
	<script src="{{asset('assets/js/vendors.min.js')}}"></script>
	<script src="{{asset('assets/js/pages/chat-popup.js')}}"></script>
    <script src="{{asset('assets/icons/feather-icons/feather.min.js')}}"></script>

</body>
</html>
