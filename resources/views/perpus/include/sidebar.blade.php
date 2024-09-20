<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">
        <div class="multinav">
            <div class="multinav-scroll" style="height: 100%;">
                <!-- sidebar menu-->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">Menu Utama</li>
                    <li class="{{request()->routeIs('perpus') ? 'active' : ''}}">
                        <a href="{{route('perpus')}}">
                            <i class="fa fa-th-large"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('perpus.bebas-pustaka') || request()->routeIs('perpus.bebas-pustaka.*') ? 'active' : ''}}">
                        <a href="{{route('perpus.bebas-pustaka')}}">
                            <i class="fa fa-pen-square"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Bebas Pustaka</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                    </li>

                    {{-- <li class="{{request()->routeIs('bak.transkrip-nilai') ? 'active' : ''}}">
                        <a href="{{route('bak.transkrip-nilai')}}">
                            <i class="fa fa-list-alt"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Transkrip Nilai</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('bak.beasiswa') ? 'active' : ''}}">
                        <a href="{{route('bak.beasiswa')}}">
                            <i class="fa fa-book"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Beasiswa</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('bak.pengajuan-cuti') ? 'active' : ''}}">
                        <a href="{{route('bak.pengajuan-cuti')}}">
                            <i class="fa fa-exclamation-triangle"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Pengajuan Cuti</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                    </li>
                    <li class="treeview {{request()->routeIs('bak.monitoring.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-television"><span class="path1"></span><span class="path2"></span></i>
                            <span>Monitoring</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('bak.monitoring.pengisian-krs') || request()->routeIs('bak.monitoring.pengisian-krs.*') ? 'active' : ''}}">
                                <a href="{{route('bak.monitoring.pengisian-krs')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Pengisian KRS</a>
                            </li>
                            <li class="{{request()->routeIs('bak.monitoring.lulus-do') || request()->routeIs('bak.monitoring.lulus-do.*') ? 'active' : ''}}">
                                <a href="{{route('bak.monitoring.lulus-do')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Lulus Do</a>
                            </li>
                        </ul>
                    </li> --}}
                </ul>
            </div>
        </div>
    </section>
    <div class="sidebar-footer text-end">
        <a href="javascript:void(0)" class="link" data-bs-toggle="tooltip" title="Settings"><span
                class="icon-Settings-2"></span></a>
        <a href="{{ route('logout') }}" class="link" data-bs-toggle="tooltip" title="Logout" onclick="event.preventDefault();
        document.getElementById('logout-form').submit();"><span
                class="icon-Lock-overturning"><span class="path1"></span><span class="path2"></span></span></a>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</aside>
