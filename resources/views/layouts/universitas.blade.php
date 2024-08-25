<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    @vite(['resources/sass/app.scss'])
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

        @include('universitas.include.header')

        @include('universitas.include.sidebar')


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

        <!-- Control Sidebar -->

        <div id="chat-box-body">
            <div id="chat-circle" class="waves-effect waves-circle btn btn-circle btn-lg btn-warning l-h-70">
                <div id="chat-overlay"></div>
                <span class="icon-Group-chat fs-30"><span class="path1"></span><span class="path2"></span></span>
            </div>
            <div class="chat-box">
                <div class="chat-box-header p-15 d-flex justify-content-between align-items-center">
                    <div class="text-center flex-grow-1">
                        <div class="text-dark fs-18">Sync in Progress</div>

                    </div>
                    <div class="chat-box-toggle">
                        <a id="chat-box-toggle"
                            class="waves-effect waves-circle btn btn-circle btn-danger-light h-40 w-40 rounded-circle l-h-45"
                            href="#">
                            <span class="icon-Close fs-22"><span class="path1"></span><span class="path2"></span></span>
                        </a>
                    </div>
                </div>
                <div class="chat-box-body">
                    <div class="chat-box-overlay">
                    </div>
                    <div class="chat-logs">
                        <div class="chat-msg user">
                            <div class="cm-msg-text">
                                job batch name :
                                 
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- ./wrapper -->


    <!-- Vendor JS -->
    <script src="{{asset('assets/js/vendors.min.js')}}"></script>
    <script src="{{asset('assets/icons/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('assets/vendor_components/moment/min/moment.min.js')}}"></script>
    <!-- EduAdmin App -->
    <script src="{{asset('assets/js/template.js')}}"></script>
    <script src="{{asset('assets/js/pages/chat-popup.js')}}"></script>
    @stack('js')
    {{-- <script src="{{asset('assets/js/pages/dashboard3.js')}}"></script> --}}


</body>

</html>
