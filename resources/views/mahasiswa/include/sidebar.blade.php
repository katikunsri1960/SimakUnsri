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

                    <li class="{{request()->routeIs('mahasiswa.biaya-kuliah') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.biaya-kuliah')}}">
                            <i class="fa fa-money"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Biaya Kuliah</span>
                        </a>
                    </li>

                    <li class="header">PERKULIAHAN</li>
                    {{-- <li class="treeview {{request()->routeIs('mahasiswa.krs') || request()->routeIs('mahasiswa.krs.index.*') ||
                                        request()->routeIs('mahasiswa.perkuliahan.aktivitas-magang.index') || request()->routeIs('mahasiswa.perkuliahan.aktivitas-magang.tambah')
                    ? 'active menu-open' : ''}}">
                        <a href="{{route('mahasiswa.krs')}}">
                            <i span class="fa fa-newspaper-o"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Kartu Rencana Studi</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('mahasiswa.krs.index') || request()->routeIs('mahasiswa.krs.index.*') ? 'active' : ''}}">
                                <a href="{{route('mahasiswa.krs.index')}}">
                                    <i class="fa fa-newspaper-o"><span class="path1"></span><span
                                            class="path2"></span></i>
                                    <span>Kartu Studi Mahasiswa</span>
                                </a>
                            </li>
                            <li class="{{request()->routeIs('mahasiswa.perkuliahan.aktivitas-magang.index') || request()->routeIs('mahasiswa.perkuliahan.aktivitas-magang.index.*') ||
                                        request()->routeIs('mahasiswa.perkuliahan.aktivitas-magang.tambah')
                             ? 'active' : ''}}">
                                <a href="{{route('mahasiswa.perkuliahan.aktivitas-magang.index')}}">
                                    <i class="fa fa-newspaper-o"><span class="path1"></span><span
                                            class="path2"></span></i>
                                    <span>Aktivitas Magang</span>
                                </a>
                            </li>
                        </ul>
                    </li> --}}
                    <li class="{{request()->routeIs('mahasiswa.krs.*') || request()->routeIs('mahasiswa.krs') ||
                                request()->routeIs('mahasiswa.perkuliahan.mbkm.*')
                    ? 'active menu-open' : ''}}">
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
                    <li class="{{request()->routeIs('mahasiswa.perkuliahan.nilai-perkuliahan') | request()->routeIs('mahasiswa.perkuliahan.nilai-perkuliahan.*') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.perkuliahan.nilai-perkuliahan')}}">
                            <i class="fa fa-line-chart"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Nilai Perkuliahan</span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('mahasiswa.perkuliahan.nilai-usept.devop') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.perkuliahan.nilai-usept.devop')}}">
                            <i class="fa fa-chart-line"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Nilai USEPT</span>
                        </a>
                    </li>
                    <li class="header">PRESTASI MAHASISWA</li>
                    <li class="treeview {{request()->routeIs('mahasiswa.prestasi.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-file-text-o"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Pelaporan Prestasi</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('mahasiswa.prestasi.prestasi-non-pendanaan') | request()->routeIs('mahasiswa.prestasi.prestasi-non-pendanaan.*')
                                ? 'active' : ''}}">
                                <a href="{{route('mahasiswa.prestasi.prestasi-non-pendanaan')}}"><i class="fa fa-trophy"><span
                                            class="path1"></span><span class="path2"></span></i>Prestasi Non Pendanaan</a>
                            </li>
                            <!-- <li class="{{request()->routeIs('mahasiswa.prestasi.prestasi-non-pendanaan')
                                ? 'active' : ''}}">
                                <a href="{{route('mahasiswa.prestasi.prestasi-non-pendanaan')}}"><i class="fa fa-trophy"><span
                                            class="path1"></span><span class="path2"></span></i>Prestasi Pendanaan UNSRI</a>
                            </li> -->
                        </ul>
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
                    </li> --}}

                    <li class="header">Bimbingan</li>
                    {{-- <li class="{{request()->routeIs('dosen.pembimbing.bimbingan-akademik') ? 'active' : ''}}">
                        <a href="{{route('dosen.pembimbing.bimbingan-akademik')}}">
                            <i class="fa fa-users"><span class="path1"></span><span class="path2"></span></i>
                            <span>Bimbingan Akademik</span>
                        </a>
                    </li> --}}
                    <li class="{{request()->routeIs('mahasiswa.bimbingan.bimbingan-tugas-akhir') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.bimbingan.bimbingan-tugas-akhir')}}">
                            <i class="fa fa-users"><span class="path1"></span><span class="path2"></span></i>
                            <span>Bimbingan Tugas Akhir</span>
                        </a>
                    </li>

                    <li class="header">LAIN-LAIN</li>
                    <li class="{{request()->routeIs('mahasiswa.pengajuan-cuti.index') | request()->routeIs('mahasiswa.pengajuan-cuti.tambah') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.pengajuan-cuti.index')}}">
                            <i class="fa fa-calendar-times-o"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Pengajuan Cuti</span>
                        </a>
                    </li>

                    <li class="header">BANTUAN</li>
                    <li class="{{request()->routeIs('mahasiswa.bantuan.ganti-password') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.bantuan.ganti-password')}}">
                            <i class="fa fa-key"><span class="path1"></span><span class="path2"></span></i>
                            <span>Ganti Password</span>
                        </a>
                    </li>
                    <li>
                        <a href="http://repository.unsri.ac.id/id/eprint/154976">
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
