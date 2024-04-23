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
                            <i class="fa fa-th-large"><span class="path1"></span><span
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
                                class="path1"></span><span class="path2"></span></i>Mahasiswa</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.data-master.kurikulum') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-master.kurikulum')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Kurikulum</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.data-master.mata-kuliah') || request()->routeIs('prodi.data-master.mata-kuliah.*') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-master.mata-kuliah')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Mata Kuliah</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.data-master.matkul-merdeka') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-master.matkul-merdeka')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Kampus Merdeka</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.data-master.ruang-perkuliahan') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-master.ruang-perkuliahan')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Ruang Perkuliahan</a>
                            </li>
                            <!-- <li class="{{request()->routeIs('prodi.data-master.ruang-perkuliahan') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-master.ruang-perkuliahan')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Pengumuman</a>
                            </li> -->
                        </ul>
                    </li>
                    <li class="treeview {{request()->routeIs('prodi.data-akademik.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-graduation-cap"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Data Akademik</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('prodi.data-akademik.kelas-penjadwalan') | request()->routeIs('prodi.data-akademik.kelas-penjadwalan.*') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-akademik.kelas-penjadwalan')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Kelas dan Penjadwalan</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.data-akademik.krs') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-akademik.krs')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Kartu Rencana Studi</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.data-akademik.khs') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-akademik.khs')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Kartu Hasil Studi</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.data-akademik.sidang-mahasiswa') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-akademik.sidang-mahasiswa')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Sidang Mahasiswa</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.data-akademik.yudisium-mahasiswa') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-akademik.yudisium-mahasiswa')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Yudisium Mahasiswa</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.data-akademik.transkrip-mahasiswa') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-akademik.transkrip-mahasiswa')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Daftar Transkrip Nilai</a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{request()->routeIs('prodi.data-aktivitas.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-trophy"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Data Aktivitas</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('prodi.data-aktivitas.aktivitas-penelitian') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-aktivitas.aktivitas-penelitian')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Aktivitas Penelitian</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.data-aktivitas.aktivitas-lomba') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-aktivitas.aktivitas-lomba')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Aktivitas Lomba</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.data-aktivitas.aktivitas-organisasi') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-aktivitas.aktivitas-organisasi')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i> Aktivitas Organisasi</a>
                            </li>
                        </ul>
                    </li>

                    <li class="header">Report & Monitoring</li>
                    <li class="treeview {{request()->routeIs('prodi.report.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-file-text-o"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Report</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('prodi.report.kemahasiswaan') ? 'active' : ''}}"><a href="{{route('prodi.report.kemahasiswaan')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Data Kemahasiswaan</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.report.mahasiswa-aktif') ? 'active' : ''}}">
                                <a href="{{route('prodi.report.mahasiswa-aktif')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Data Mahasiswa Aktif</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.report.perkuliahan') ? 'active' : ''}}">
                                <a href="{{route('prodi.report.perkuliahan')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Data Kuliah Mahasiswa</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.report.aktivitas-mahasiswa') ? 'active' : ''}}">
                                <a href="{{route('prodi.report.aktivitas-mahasiswa')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Data Aktivitas Mahasiswa</a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{request()->routeIs('prodi.monitoring.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-television"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Monitoring</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('prodi.monitoring.entry-nilai') ? 'active' : ''}}">
                                <a href="{{route('prodi.monitoring.entry-nilai')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Entry Nilai Dosen</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.monitoring.pengajaran-dosen') ? 'active' : ''}}">
                                <a href="{{route('prodi.monitoring.pengajaran-dosen')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Pengajaran Dosen</a>
                            </li>
                        </ul>
                    </li>

                    <li class="header">Bantuan</li>
                    <li class="{{request()->routeIs('prodi.bantuan.ganti-password') ? 'active' : ''}}">
                        <a href="{{route('prodi.bantuan.ganti-password')}}">
                            <i class="fa fa-key"><span class="path1"></span><span class="path2"></span></i>
                            <span>Ganti Password</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" target="_blank">
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
