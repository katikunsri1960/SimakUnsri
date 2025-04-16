<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    {{-- @vite(['resources/sass/app.scss']) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{asset('images/unsri.png')}}">

    <title>{{ config('app.name', 'SIMAK') }} - @yield('title')</title>
    @stack('css')
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{asset('assets/css/vendors_css.css')}}">

    <!-- Style-->
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/skin_color.css')}}">

    <style>
        .centered {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .select2-dropdown {
            z-index: 9999;
        }
    </style>

</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
    <div id="spinner" class="centered"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.5); z-index: 9999;">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div class="wrapper">
        <div id="loader"></div>

        @include('fakultas.include.header')
        @include('fakultas.include.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="container-full">
                <!-- Main content -->
                @yield('content')
                <!-- /.content -->
            </div>
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            &copy; {{date('Y')}} <a href="https://tik.unsri.ac.id/">Created By UPT TIK UNSRI</a>. All Rights Reserved.
        </footer>

    </div>
    <!-- ./wrapper -->

    <!-- Vendor JS -->
    <script src="{{asset('assets/js/vendors.min.js')}}"></script>
    <script src="{{asset('assets/icons/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
    <script src="{{asset('assets/vendor_components/apexcharts-bundle/dist/apexcharts.js')}}"></script>
    <script src="{{asset('assets/vendor_components/moment/min/moment.min.js')}}"></script>
    {{-- <script src="{{asset('assets/assets/vendor_components/chart.js-master/Chart.min.js')}}"></script> --}}
    <script src="{{asset('assets/vendor_components/easypiechart/dist/jquery.easypiechart.js')}}"></script>
    <!-- EduAdmin App -->
    <script src="{{asset('assets/js/template.js')}}"></script>
    <script src="{{asset('assets/js/pages/data-table.js')}}"></script>
    {{-- <script src="{{asset('assets/js/pages/chart-widgets.js')}}"></script> --}}
    {{-- <script src="{{asset('assets/js/pages/chartjs-int.js')}}"></script> --}}
    <script>
        $(document).ajaxStart(function() {
             $("#spinner").show();
         }).ajaxStop(function() {
             $("#spinner").hide();
         });
    </script>
    @stack('js')
    <!-- EduAdmin App -->



</body>

</html>
