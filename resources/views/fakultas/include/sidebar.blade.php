<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">
        <div class="multinav">
            <div class="multinav-scroll" style="height: 100%;">
                <!-- sidebar menu-->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">Menu Utama</li>
                    <li class="{{request()->routeIs('fakultas') ? 'active' : ''}}">
                        <a href="{{route('fakultas')}}">
                            <i class="fa fa-th-large"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="treeview {{request()->routeIs('fakultas.data-master.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-database"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Data Master</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('fakultas.data-master.dosen') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-master.dosen')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Dosen</a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.data-master.mahasiswa') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-master.mahasiswa')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Mahasiswa</a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.data-master.pejabat-fakultas.devop')
                             ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-master.pejabat-fakultas.devop')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Pejabat Fakultas</a>
                            </li>
                            {{-- <li class="{{request()->routeIs('fakultas.data-master.biaya-kuliah.devop')
                                ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-master.biaya-kuliah.devop') }}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Biaya Kuliah</a>
                            </li> --}}
                        </ul>
                    </li>
                    <li class="treeview {{request()->routeIs('fakultas.data-akademik.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-graduation-cap"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Data Akademik</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('fakultas.data-akademik.krs') || request()->routeIs('fakultas.data-akademik.krs.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-akademik.krs')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                    Kartu Rencana Studi
                                </a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.data-akademik.khs') || request()->routeIs('fakultas.data-akademik.khs.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-akademik.khs')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                    Kartu Hasil Studi
                                </a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.data-akademik.nilai-usept') || request()->routeIs('fakultas.data-akademik.nilai-usept.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-akademik.nilai-usept')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                    Nilai USEPT
                                </a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.data-akademik.tugas-akhir') || request()->routeIs('fakultas.data-akademik.tugas-akhir.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-akademik.tugas-akhir')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                    Aktivitas Tugas Akhir
                                </a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.data-akademik.non-tugas-akhir') || request()->routeIs('fakultas.data-akademik.non-tugas-akhir.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-akademik.non-tugas-akhir')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                    Aktivitas Non Tugas Akhir
                                </a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.data-akademik.sidang-mahasiswa') || request()->routeIs('fakultas.data-akademik.sidang-mahasiswa.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-akademik.sidang-mahasiswa')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                    Sidang Mahasiswa
                                </a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.data-akademik.yudisium-mahasiswa') || request()->routeIs('fakultas.data-akademik.yudisium-mahasiswa.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-akademik.yudisium-mahasiswa')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                    Yudisium Mahasiswa
                                </a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.transkrip-nilai') || request()->routeIs('fakultas.transkrip-nilai.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.transkrip-nilai')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                    Daftar Transkrip Nilai
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="header">Report & Monitoring</li>
                    <li class="treeview {{request()->routeIs('fakultas.report.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-file-text-o"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Report</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs
                            // ('fakultas.report.kemahasiswaan')
                             ? 'active' : ''}}"><a href="{{route
                            // ('fakultas.report.kemahasiswaan')
                            ('fakultas.under-development')
                                }}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Data Kemahasiswaan</a>
                            </li>
                            <li class="{{request()->routeIs
                            // ('fakultas.report.mahasiswa-aktif')
                             ? 'active' : ''}}">
                                <a href="{{route
                                // ('fakultas.report.mahasiswa-aktif')
                                ('fakultas.under-development')
                                }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Data Mahasiswa Aktif</a>
                            </li>
                            <li class="{{request()->routeIs
                            // ('fakultas.report.perkuliahan')
                             ? 'active' : ''}}">
                                <a href="{{route
                                // ('fakultas.report.perkuliahan')
                                ('fakultas.under-development')
                                }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Data Kuliah Mahasiswa</a>
                            </li>
                            <li class="{{request()->routeIs
                            // ('fakultas.report.aktivitas-mahasiswa')
                             ? 'active' : ''}}">
                                <a href="{{route
                                // ('fakultas.report.aktivitas-mahasiswa')
                                ('fakultas.under-development')

                                }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Data Aktivitas Mahasiswa</a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{request()->routeIs
                    // ('fakultas.monitoring.*')
                     ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-television"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Monitoring</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('fakultas.monitoring.entry-nilai') ? 'active' : ''}}">
                                <a href="{{route('fakultas.monitoring.entry-nilai')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Entry Nilai Dosen</a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.monitoring.pengajaran-dosen') ? 'active' : ''}}">
                                <a href="{{route('fakultas.monitoring.pengajaran-dosen')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Pengajaran Dosen</a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.monitoring.pengisian-krs') || 
                                        request()->routeIs('fakultas.monitoring.pengisian-krs.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.monitoring.pengisian-krs')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Pengisian KRS</a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.monitoring.lulus-do')|| 
                                        request()->routeIs('fakultas.monitoring.lulus-do.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.monitoring.lulus-do')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Lulus DO</a>
                            </li>
                        </ul>
                    </li>

                    <li class="header">LAIN-LAIN</li>
                    <li class="{{request()->routeIs('fakultas.beasiswa') ? 'active' : ''}}">
                        <a href="{{route('fakultas.beasiswa')}}">
                            <i class="fa fa-book"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Beasiswa</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('fakultas.pengajuan-cuti') ? 'active' : ''}}">
                        <a href="{{route('fakultas.pengajuan-cuti')}}">
                            <i class="fa fa-exclamation-triangle"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Pengajuan Cuti Mhs</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                    </li>

                    <li class="header">BANTUAN</li>
                    <li class="{{request()->routeIs('fakultas.bantuan.ganti-password') ? 'active' : ''}}">
                        <a href="{{route('fakultas.bantuan.ganti-password')}}">
                            <i class="fa fa-key"><span class="path1"></span><span class="path2"></span></i>
                            <span>Ganti Password</span>
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
