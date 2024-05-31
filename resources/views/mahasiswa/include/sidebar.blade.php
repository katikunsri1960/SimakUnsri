<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">
        <div class="multinav">
            <div class="multinav-scroll" style="height: 100%;">
                <!-- sidebar menu-->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">MENU UTAMA</li>
                    <li class="{{request()->routeIs('mahasiswa.dashboard') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.dashboard')}}">
                            <i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('mahasiswa.biodata') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.biodata')}}">
                            <i class="fa fa-id-badge"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Biodata</span>
                        </a>
                    </li>
                    
                    <!-- <li class="treeview">
                        <a href="#">
                            <i span class="fa fa-newspaper-o"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Akademik</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('mahasiswa.krs')
                                ? 'active' : ''}}">
                                <a href="{{route('mahasiswa.krs')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Kartu Rencana Studi</a>
                            </li>
                            <li class="{{request()->routeIs('mahasiswa.khs')
                                ? 'active' : ''}}">
                                <a href="{{route('mahasiswa.khs')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Kartu Hasil Studi</a>
                            </li>
                            <li class="{{request()->routeIs('mahasiswa.transkrip')
                                ? 'active' : ''}}">
                                <a href="{{route('mahasiswa.transkrip')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Transkrip Nilai</a>
                            </li>
                        </ul>
                    </li> -->
                    {{-- <li class="{{request()->routeIs('mahasiswa.biaya-kuliah') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.biaya-kuliah')}}">
                            <i class="fa fa-money"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Biaya Kuliah</span>
                        </a>
                    </li> --}}

                    <li class="header">PERKULIAHAN</li>
                    <li class="{{request()->routeIs('mahasiswa.krs') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.krs')}}">
                            <i class="fa fa-newspaper-o"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Kartu Rencana Studi</span>
                        </a>
                    </li>
                    {{-- <li class="{{request()->routeIs('mahasiswa.bahan-tugas') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.bahan-tugas')}}">
                            <i class="fa fa-tasks"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Bahan & Tugas</span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('mahasiswa.jadwal-presensi') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.jadwal-presensi')}}">
                            <i class="fa fa-calendar"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Jadwal & Presensi</span>
                        </a>
                    </li> --}}
                    {{-- <li class="{{request()->routeIs('mahasiswa.pa-online') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.pa-online')}}">
                            <i class="fa fa-users"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>PA Online</span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('mahasiswa.kuisioner') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.kuisioner')}}">
                            <i class="fa fa-comments-o"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Kuisioner</span>
                        </a>
                    </li> --}}
                    <li class="{{request()->routeIs('mahasiswa.perkuliahan.nilai-perkuliahan') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.perkuliahan.nilai-perkuliahan')}}">
                            <i class="fa fa-line-chart"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Nilai Perkuliahan</span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('mahasiswa.perkuliahan.nilai-usept') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.perkuliahan.nilai-usept')}}">
                            <i class="fa fa-chart-line"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Nilai USEPT</span>
                        </a>
                    </li>
                    {{-- <li class="treeview">
                        <a href="#">
                            <i span class="fa fa-newspaper-o"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Kegiatan Mahasiswa</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('mahasiswa.akademik')
                                ? 'active' : ''}}">
                                <a href="{{route('mahasiswa.akademik')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Akademik</a>
                            </li>
                            <li class="{{request()->routeIs('mahasiswa.seminar')
                                ? 'active' : ''}}">
                                <a href="{{route('mahasiswa.seminar')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Seminar</a>
                            </li>
                        </ul>
                    </li>

                    <li class="header">LAIN-LAIN</li>
                    <li class="{{request()->routeIs('mahasiswa.pengajuan-cuti') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.pengajuan-cuti')}}">
                            <i class="fa fa-calendar-times-o"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Pengajuan Cuti</span>
                        </a>
                    </li> --}}

                    <li class="header">BANTUAN</li>
                    <li class="{{request()->routeIs('mahasiswa.bantuan.ganti-password') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.bantuan.ganti-password')}}">
                            <i class="fa fa-key"><span class="path1"></span><span class="path2"></span></i>
                            <span>Ganti Password</span>
                        </a>
                    </li>
                    {{-- <li class="{{request()->routeIs('mahasiswa.panduan-aplikasi') ? 'active' : ''}}">
                        <a href="#">
                            <i class="fa fa-question"><span class="path1"></span><span class="path2"></span></i>
                            <span>Panduan Aplikasi</span>
                        </a>
                    </li>   --}}
                </ul>
            </div>
        </div>
    </section>
    <div class="sidebar-footer text-end">
        <a href="javascript:void(0)" class="link" data-bs-toggle="tooltip" title="Settings"><span
                class="icon-Settings-2"></span></a>
        {{-- <a href="mailbox.html" class="link" data-bs-toggle="tooltip" title="Email"><span
                class="icon-Mail"></span></a> --}}
        <a href="{{ route('logout') }}" class="link" data-bs-toggle="tooltip" title="Logout" onclick="event.preventDefault();
        document.getElementById('logout-form').submit();"><span class="icon-Lock-overturning"><span
                    class="path1"></span><span class="path2"></span></span></a>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</aside>
