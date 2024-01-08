<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">
        <div class="multinav">
            <div class="multinav-scroll" style="height: 100%;">
                <!-- sidebar menu-->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">Menu Utama</li>
                    <li class="{{request()->routeIs('prodi') ? 'active' : ''}}">
                        <a href="{{route('prodi')}}">
                            <i class="fa fa-line-chart"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="treeview {{request()->routeIs('prodi.data-master.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-database"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Data Master</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            {{-- <li class="{{request()->routeIs('prodi.referensi.prodi') ? 'active' : ''}}"><a href="{{route('prodi.referensi.prodi')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Program Studi</a>
                            </li> --}}
                            <li class="{{request()->routeIs('prodi.data-master.dosen') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-master.dosen')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Dosen</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.data-master.mahasiswa') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-master.mahasiswa')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Mahasiswa</a></li>
                            <li class="{{request()->routeIs('prodi.data-master.kurikulum') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-master.kurikulum')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Kurikulum</a></li>
                            <li class="{{request()->routeIs('prodi.data-master.mata-kuliah') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-master.mata-kuliah')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Mata Kuliah</a></li>
                            <li class="{{request()->routeIs('prodi.data-master.ruang-perkuliahan') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-master.ruang-perkuliahan')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Ruang Perkuliahan</a></li>
                        </ul>
                    </li>
                    <li class="treeview {{request()->routeIs('prodi.referensi.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-graduation-cap"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Data Akademik</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            {{-- <li class="{{request()->routeIs('prodi.referensi.prodi') ? 'active' : ''}}"><a href="{{route('prodi.referensi.prodi')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Program Studi</a>
                            </li> --}}
                            <li><a href="contact_app_chat.html"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Kelas dan Penjadwalan</a></li>
                            <li><a href="contact_app_chat.html"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Kartu Rencana Studi</a></li>
                            <li><a href="contact_app_chat.html"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Kartu Hasil Studi</a></li>
                            <li><a href="contact_app_chat.html"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Sidang Mahasiswa</a></li>
                            <li><a href="contact_app_chat.html"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Yudisium Mahasiswa</a></li>
                            <li><a href="contact_app_chat.html"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Daftar Transkrip Nilai</a></li>
                        </ul>
                    </li>

                    <li class="header">Report & Monitoring</li>
                    <li class="treeview {{request()->routeIs('prodi') | request()->routeIs('prodi') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-file-text-o"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Report</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('prodi') ? 'active' : ''}}"><a href="{{route('prodi')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Data Kemahasiswaan</a>
                            </li>
                            <li class="{{request()->routeIs('prodi') ? 'active' : ''}}">
                                <a href="{{route('prodi')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Data Mahasiswa Aktif</a>
                            </li>
                            <li>
                                <a href="contact_app_chat.html"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Data Akademik</a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{request()->routeIs('prodi') | request()->routeIs('prodi') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-television"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Monitoring</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('prodi') ? 'active' : ''}}"><a href="{{route('prodi')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Entry Nilai Dosen</a>
                            </li>
                            <li class="{{request()->routeIs('prodi') ? 'active' : ''}}">
                                <a href="{{route('prodi')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Kinerja Pengajaran Dosen</a>
                            </li>
                        </ul>
                    </li>

                    <li class="header">Bantuan</li>
                    <li class="">
                        <a href="#">
                            <i class="fa fa-key"><span class="path1"></span><span class="path2"></span></i>
                            <span>Ganti Password</span>
                        </a>
                        <a href="#">
                            <i class="fa fa-question"><span class="path1"></span><span class="path2"></span></i>
                            <span>Panduan Aplikasi</span>
                        </a>
                    </li>
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
