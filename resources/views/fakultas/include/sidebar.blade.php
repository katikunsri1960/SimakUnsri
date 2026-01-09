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
                            <li class="{{request()->routeIs('fakultas.data-master.pejabat-fakultas') || request()->routeIs('fakultas.data-master.pejabat-fakultas.*')
                             ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-master.pejabat-fakultas')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Pejabat Fakultas</a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.data-master.ruang-perkuliahan') || request()->routeIs('fakultas.data-master.ruang-perkuliahan.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-master.ruang-perkuliahan')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Ruang Perkuliahan</a>
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
                            <li class="{{request()->routeIs('fakultas.data-akademik.kelas-penjadwalan') | request()->routeIs('fakultas.data-akademik.kelas-penjadwalan.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-akademik.kelas-penjadwalan')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                    Kelas dan Penjadwalan
                                </a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.data-akademik.krs') || request()->routeIs('fakultas.data-akademik.krs.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-akademik.krs')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                    Kartu Rencana Studi
                                </a>
                            </li>
                            <li class="treeview {{request()->routeIs('fakultas.data-akademik.khs.*') || request()->routeIs('fakultas.data-akademik.khs') ? 'active menu-open' : ''}}">
                                <a href="#">
                                    <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i> Kartu Hasil Studi
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    {{--
                                    <li class="{{request()->routeIs('fakultas.data-akademik.khs') ? 'active' : ''}}">
                                        <a href="{{route('fakultas.data-akademik.khs.devop')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Mahasiswa</a>
                                    </li>
                                    <li class="{{request()->routeIs('fakultas.data-akademik.khs.angkatan') ? 'active' : ''}}">
                                        <a href="{{route('fakultas.data-akademik.khs.devop')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Angkatan</a>
                                    </li>
                                    --}}
                                        <li class="{{request()->routeIs('fakultas.data-akademik.khs') ? 'active' : ''}}">
                                        <a href="{{route('fakultas.data-akademik.khs')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Mahasiswa</a>
                                    </li>
                                    <li class="{{request()->routeIs('fakultas.data-akademik.khs.angkatan') ? 'active' : ''}}">
                                        <a href="{{route('fakultas.data-akademik.khs.angkatan')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Angkatan</a>
                                    </li>
                                    
                                </ul>
                            </li>
                            {{-- <li class="{{request()->routeIs('fakultas.data-akademik.khs') || request()->routeIs('fakultas.data-akademik.khs.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-akademik.khs')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                    Kartu Hasil Studi
                                </a>
                            </li> --}}
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
                            <li class="{{request()->routeIs('fakultas.data-akademik.sidang-mahasiswa') || request()->routeIs('fakultas.data-akademik.sidang-fakultas.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-akademik.sidang-mahasiswa')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                    Sidang Mahasiswa
                                </a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.data-akademik.yudisium-mahasiswa') || request()->routeIs('fakultas.data-akademik.yudisium-fakultas.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-akademik.yudisium-mahasiswa')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                    Yudisium Mahasiswa
                                </a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.data-akademik.transkrip-nilai') || request()->routeIs('fakultas.data-akademik.transkrip-nilai.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-akademik.transkrip-nilai')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                    Daftar Transkrip Nilai
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{request()->routeIs('fakultas.wisuda.index.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-user-graduate"><span class="path1"></span><span class="path2"></span></i>
                            <span>Wisuda</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('fakultas.wisuda.index') || request()->routeIs('fakultas.wisuda.index.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.wisuda.index')}}"><i class="icon-Commit"><span class="path1"></span>
                                <span class="path2"></span></i>Peserta Wisuda</a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.wisuda.khs-index') || request()->routeIs('fakultas.wisuda.khs-index.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.wisuda.khs-index')}}"><i class="icon-Commit"><span class="path1"></span>
                                <span class="path2"></span></i>Daftar MK Mahasiswa</a>
                            </li>
                        </ul>
                    </li>
                    <!-- <li class="{{request()->routeIs('fakultas.wisuda.index') | request()->routeIs('fakultas.wisuda.*') ? 'active' : ''}}">
                        <a href="{{route('fakultas.wisuda.index')}}"><i class="fa fa-user-graduate"><span class="path1"></span><spanclass="path2"></spanclass=></i>
                            <span>Wisuda</span>
                        </a>
                    </li> -->
                    

                    <li class="header">Monitoring</li>
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
                            <li class="{{request()->routeIs('fakultas.monitoring.status-mahasiswa') || 
                                        request()->routeIs('fakultas.monitoring.status-mahasiswa.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.monitoring.status-mahasiswa')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Status Mahasiswa</a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.monitoring.pengisian-krs') ||
                                        request()->routeIs('fakultas.monitoring.pengisian-krs.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.monitoring.pengisian-krs')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Pengisian KRS</a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.monitoring.lulus-do')||
                                        request()->routeIs('fakultas.monitoring.lulus-do.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.monitoring.lulus-do')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Lulus DO</a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.monitoring.pengisian-nilai') || request()->routeIs('fakultas.monitoring.pengisian-nilai.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.monitoring.pengisian-nilai')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Pengisian Nilai</a>
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
                    {{-- <li class="{{request()->routeIs('fakultas.pengajuan-cuti') ? 'active' : ''}}">
                        <a href="{{route('fakultas.pengajuan-cuti')}}">
                            <i class="fa fa-exclamation-triangle"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Pengajuan Cuti Mhs</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                    </li> --}}
                    <li class="treeview {{request()->routeIs('fakultas.pengajuan-cuti.index') || request()->routeIs('fakultas.pengajuan-cuti.*') ||
                        request()->routeIs('fakultas.penundaan-bayar.index') || request()->routeIs('fakultas.penundaan-bayar.*')
                            ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-calendar-times-o"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Pengajuan</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('fakultas.pengajuan-cuti.index') || request()->routeIs('fakultas.pengajuan-cuti.*')  ? 'active' : ''}}">
                                <a href="{{route('fakultas.pengajuan-cuti.index')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>
                                Cuti Kuliah</a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.penundaan-bayar.index') || request()->routeIs('fakultas.penundaan-bayar.*')  ? 'active' : ''}}">
                                <a href="{{route('fakultas.penundaan-bayar.index')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>
                                Penundaan Bayar</a>
                            </li>
                        </ul>
                    </li>
                    {{-- <li class="{{request()->routeIs('fakultas.wisuda.index') | request()->routeIs('fakultas.wisuda.*') ? 'active' : ''}}">
                        <a href="#">
                            <i class="fa fa-user-graduate"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Pendaftaran Wisuda</span>
                        </a>
                    </li> --}}

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
