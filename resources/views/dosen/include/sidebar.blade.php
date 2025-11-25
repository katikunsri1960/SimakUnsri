<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">
        <div class="multinav">
            <div class="multinav-scroll" style="height: 100%;">
                <!-- sidebar menu-->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">Menu Utama</li>
                    <li class="{{request()->routeIs('dosen') ? 'active' : ''}}">
                        <a href="{{route('dosen')}}">
                            <i class="fa fa-th-large"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="treeview {{request()->routeIs('dosen.profile.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-user"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Profile Dosen</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('dosen.profile.biodata') ? 'active' : ''}}"><a href="{{route('dosen.profile.biodata')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Biodata Dosen</a>
                            </li>
                            {{-- <li class="treeview {{request()->routeIs('dosen.profile.aktivitas.penelitian') | request()->routeIs('dosen.profile.aktivitas.publikasi') | request()->routeIs('dosen.profile.aktivitas.pengabdian') ? 'active menu-open' : ''}}">
                                <a href="#">
                                    <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Aktivitas Dosen
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="{{request()->routeIs('dosen.profile.aktivitas.penelitian') ? 'active' : ''}}">
                                        <a href="{{route('dosen.profile.aktivitas.penelitian')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Penelitian Dosen</a>
                                    </li>
                                    <li class="{{request()->routeIs('dosen.profile.aktivitas.publikasi') ? 'active' : ''}}">
                                        <a href="{{route('dosen.profile.aktivitas.publikasi')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Publikasi Dosen</a>
                                    </li>
                                    <li class="{{request()->routeIs('dosen.profile.aktivitas.pengabdian') ? 'active' : ''}}">
                                        <a href="{{route('dosen.profile.aktivitas.pengabdian')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Pengabdian Dosen</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="{{request()->routeIs('dosen.profile.mengajar') ? 'active' : ''}}">
                                <a href="{{route('dosen.profile.mengajar')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Mengajar Dosen</a>
                            </li>
                            <li class="{{request()->routeIs('dosen.profile.riwayat_pendidikan') ? 'active' : ''}}">
                                <a href="{{route('dosen.profile.riwayat_pendidikan')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Riwayat Pendidikan</a>
                            </li> --}}
                        </ul>
                    </li>
                    <li class="treeview {{request()->routeIs('dosen.monev.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i class="fa fa-chart-pie"><span class="path1"></span><span class="path2"></span></i>
                            <span>Monev</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('dosen.monev.karya-ilmiah') || request()->routeIs('dosen.monev.karya-ilmiah.*') ? 'active' : ''}}">
                                <a href="{{route('dosen.monev.karya-ilmiah')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Pembimbing K. Ilmiah</a>
                            </li>
                            {{-- <li class="{{request()->routeIs('dosen.monev.pa-prodi') || request()->routeIs('dosen.monev.pa-prodi.*') ? 'active' : ''}}"> --}}
                            <li class="{{request()->routeIs('dosen.monev.penguji-sidang') || request()->routeIs('dosen.monev.penguji-sidang.*') ? 'active' : ''}}">
                                <a href="{{route('dosen.monev.penguji-sidang')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Penguji Sidang</a>
                            </li>
                            <li class="{{request()->routeIs('dosen.monev.pa-prodi') || request()->routeIs('dosen.monev.pa-prodi.*') ? 'active' : ''}}">
                                <a href="{{route('dosen.monev.pa-prodi')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Pembimbing Akademik</a>
                            </li>
                            <li>
                                <a href="#"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Profil Lulusan</a>
                            </li>
                            <li>
                                <a href="#"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Presensi Dosen</a>
                            </li>
                        </ul>
                    </li>
                    <!-- <li class="{{request()->routeIs('dosen.kalender_akademik') ? 'active' : ''}}">
                        <a href="{{route('dosen.kalender_akademik')}}">
                            <i class="fa fa-calendar-o"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Kalender Akademik</span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('dosen.pengumuman') ? 'active' : ''}}">
                        <a href="{{route('dosen.pengumuman')}}">
                            <i class="fa fa-bullhorn"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Pengumuman</span>
                        </a>
                    </li> -->
                    <li class="header">Perkuliahan</li>
                    <li class="treeview {{request()->routeIs('dosen.perkuliahan.jadwal-kuliah') | request()->routeIs('dosen.perkuliahan.kesediaan-waktu-kuliah') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i class="fa fa-calendar-o"><span class="path1"></span><span class="path2"></span></i>
                            <span>Jadwal Dosen</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('dosen.perkuliahan.jadwal-kuliah') | request()->routeIs('dosen.perkuliahan.kesediaan-waktu-kuliah') ? 'active' : ''}}">
                                <a href="{{route('dosen.perkuliahan.jadwal-kuliah')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Jadwal Mengajar</a>
                            </li>
                            <!-- <li class="{{request()->routeIs('dosen.perkuliahan.jadwal-bimbingan') | request()->routeIs('dosen.perkuliahan.kesediaan-waktu-bimbingan') ? 'active' : ''}}">
                                <a href="{{route('dosen.perkuliahan.jadwal-bimbingan')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Jadwal Bimbingan</a>
                            </li> -->
                        </ul>
                    </li>
                    <li class="{{request()->routeIs('dosen.perkuliahan.rencana-pembelajaran') ? 'active' : ''}}">
                        <a href="{{route('dosen.perkuliahan.rencana-pembelajaran')}}">
                            <i class="fa fa-pencil-square-o"><span class="path1"></span><span class="path2"></span></i>
                            <span>RPS</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://elearning.unsri.ac.id/" target="_blank">
                            <i class="fa fa-desktop"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>E-Learning UNSRI</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://uscamz.unsri.ac.id/b/signin" target="_blank">
                            <i class="fa fa-video-camera"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>USCAMZ UNSRI</span>
                        </a>
                    </li>

                    <!-- <li class="{{request()->routeIs('dosen.kehadiran.kehadiran-elearning.detail-dosen') || request()->routeIs('dosen.penilaian.kehadiran-elearning.kehadiran-elearning*') ? 'active' : ''}}">
                        <a href="{{route('dosen.kehadiran.kehadiran-elearning.detail-dosen')}}">
                            <i class="fa fa-calendar-check-o"><span class="path1"></span><span class="path2"></span></i>
                            <span>Daftar Hadir Dosen</span>
                        </a>
                    </li> -->
                    <li class="header">Penilaian Mahasiswa</li>
                    <li class="{{request()->routeIs('dosen.penilaian.penilaian-perkuliahan') | request()->routeIs('dosen.penilaian.penilaian-perkuliahan.*')  ? 'active' : ''}}">
                        <a href="{{route('dosen.penilaian.penilaian-perkuliahan')}}">
                            <i class="fa fa-pencil-square-o"><span class="path1"></span><span class="path2"></span></i>
                            <span>Penilaian Perkuliahan</span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('dosen.penilaian.riwayat-penilaian') || request()->routeIs('dosen.penilaian.riwayat-penilaian.*')  ? 'active' : ''}}">
                        <a href="{{route('dosen.penilaian.riwayat-penilaian')}}">
                            <i class="fa fa-book"><span class="path1"></span><span class="path2"></span></i>
                            <span>Riwayat Penilaian</span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('dosen.penilaian.sidang-mahasiswa') || request()->routeIs('dosen.penilaian.sidang-mahasiswa.*') ? 'active' : ''}}">
                        <a href="{{route('dosen.penilaian.sidang-mahasiswa')}}">
                            <i class="fa fa-pencil-square-o"><span class="path1"></span><span class="path2"></span></i>
                            <span>Penguji Sidang Mahasiswa</span>
                        </a>
                    </li>
                    <li class="header">Pembimbing Mahasiswa</li>
                    <li class="treeview {{request()->routeIs('dosen.pembimbing.bimbingan-akademik') || request()->routeIs('dosen.pembimbing.bimbingan-akademik.*') ? 'active' : ''}}">
                        {{-- <a href="{{route('dosen.pembimbing.bimbingan-akademik')}}"> --}}
                            <a href="#">
                                <i class="fa fa-file-lines"><span class="path1"></span><span class="path2"></span></i>
                                <span>Akademik</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                        {{-- <a href="#">
                            <i class="fa fa-users"><span class="path1"></span><span class="path2"></span></i>
                            <span>Pembimbing Akademik</span>
                        </a> --}}

                        {{-- <a href="#">
                            <i class="fa fa-calendar-o"><span class="path1"></span><span class="path2"></span></i>
                            <span>Jadwal Dosen</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a> --}}
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('dosen.pembimbing.bimbingan-akademik') ? 'active' : ''}}">
                                <a href="{{route('dosen.pembimbing.bimbingan-akademik')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Pembimbing Akademik</a>
                            </li>
                            <li class="{{request()->routeIs('dosen.pembimbing.bimbingan-akademik.riwayat') | request()->routeIs('dosen.pembimbing.bimbingan-akademik.riwayat.*') ? 'active' : ''}}">
                                <a href="{{route('dosen.pembimbing.bimbingan-akademik.riwayat')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Riwayat</a>
                            </li>
                        </ul>

                    </li>
                    <li class="{{request()->routeIs('dosen.pembimbing.bimbingan-non-akademik') ? 'active' : ''}}">
                        <a href="{{route('dosen.pembimbing.bimbingan-non-akademik')}}">
                            <i class="fa fa-users"><span class="path1"></span><span class="path2"></span></i>
                            <span>Pembimbing Non-Akademik</span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('dosen.pembimbing.bimbingan-tugas-akhir') | request()->routeIs('dosen.pembimbing.bimbingan-tugas-akhir.*') ? 'active' : ''}}">
                        <a href="{{route('dosen.pembimbing.bimbingan-tugas-akhir')}}">
                            <i class="fa fa-users"><span class="path1"></span><span class="path2"></span></i>
                            <span>Pembimbing Karya Ilmiah</span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('dosen.pembimbing.bimbingan-non-tugas-akhir') | request()->routeIs('dosen.pembimbing.bimbingan-non-tugas-akhir.*') ? 'active' : ''}}">
                        <a href="{{route('dosen.pembimbing.bimbingan-non-tugas-akhir')}}">
                            <i class="fa fa-users"><span class="path1"></span><span class="path2"></span></i>
                            <span>Pembimbing Aktivitas</span>
                        </a>
                    </li>
                    <li class="header">Bantuan</li>
                    <li class="{{request()->routeIs('dosen.bantuan.ganti-password') ? 'active' : ''}}">
                        <a href="{{route('dosen.bantuan.ganti-password')}}">
                            <i class="fa fa-key"><span class="path1"></span><span class="path2"></span></i>
                            <span>Ganti Password</span>
                        </a>
                    </li>
                    <li>
                        <a href="http://repository.unsri.ac.id/154973/" target="_blank">
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
