<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">
        <div class="multinav">
            <div class="multinav-scroll" style="height: 100%;">
                <!-- sidebar menu-->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">Menu Utama</li>
                    <li class="{{request()->routeIs('bak') ? 'active' : ''}}">
                        <a href="{{route('bak')}}">
                            <i class="fa fa-th-large"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="treeview {{request()->routeIs('bak.pejabat.*') || request()->routeIs('bak.gelar-lulusan') || request()->routeIs('bak.gelar-lulusan.*') || request()->routeIs('bak.usept-prodi') || request()->routeIs('bak.usept-prodi.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-database"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Data Master</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('bak.mahasiswa') || request()->routeIs('bak.mahasiswa.*') ? 'active' : ''}}">
                                <a href="{{route('bak.mahasiswa')}}">
                                    <i class="fa fa-graduation-cap"><span class="path1"></span><span
                                            class="path2"></span></i>
                                    <span>Mahasiswa</span>

                                </a>
                            </li>
                            <li class="treeview {{request()->routeIs('bak.pejabat.*') ? 'active menu-open' : ''}}">
                                <a href="#">
                                    <i span class="fa fa-sitemap"><span class="path1"></span><span class="path2"></span></i>
                                    <span>Pejabat</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="{{request()->routeIs('bak.pejabat.fakultas') || request()->routeIs('bak.pejabat.fakultas.*') ? 'active' : ''}}">
                                        <a href="{{route('bak.pejabat.fakultas')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Fakultas</a>
                                    </li>
                                    <li class="{{request()->routeIs('bak.pejabat.universitas') || request()->routeIs('bak.pejabat.universitas.*') ? 'active' : ''}}">
                                        <a href="{{route('bak.pejabat.universitas')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Universitas</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="{{request()->routeIs('bak.gelar-lulusan') || request()->routeIs('bak.gelar-lulusan.*') ? 'active' : ''}}">
                                <a href="{{route('bak.gelar-lulusan')}}">
                                    <i class="fa fa-graduation-cap"><span class="path1"></span><span
                                            class="path2"></span></i>
                                    <span>Gelar Lulusan</span>

                                </a>
                            </li>
                            <li class="{{request()->routeIs('bak.usept-prodi') || request()->routeIs('bak.usept-prodi.*') ? 'active' : ''}}">
                                <a href="{{route('bak.usept-prodi')}}">
                                    <i class="fa fa-pen-square"><span class="path1"></span><span
                                            class="path2"></span></i>
                                    <span>USEPT Prodi</span>
                                </a>
                            </li>
                            <li class="{{request()->routeIs('bak.data-master.*') ? 'active' : ''}}">
                                <a href="{{route('bak.data-master.predikat')}}">
                                    <i class="fa fa-graduation-cap"><span class="path1"></span><span
                                            class="path2"></span></i>
                                    <span>Predikat Lulusan</span>

                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{request()->routeIs('bak.transkrip-nilai') || request()->routeIs('bak.transkrip-nilai.*') ? 'active' : ''}}">
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
                    <li class="{{request()->routeIs('bak.tunda-bayar') ? 'active' : ''}}">
                        <a href="{{route('bak.tunda-bayar')}}">
                            <i class="fa fa-calendar-times"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Tunda Bayar</span>
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
                            <li class="{{request()->routeIs('bak.monitoring.status-mahasiswa') || request()->routeIs('bak.monitoring.status-mahasiswa.*') ? 'active' : ''}}">
                                <a href="{{route('bak.monitoring.status-mahasiswa')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Status Akd Mhs</a>
                            </li>
                            <li class="{{request()->routeIs('bak.monitoring.status-ukt') || request()->routeIs('bak.monitoring.status-ukt.*') ? 'active' : ''}}">
                                <a href="{{route('bak.monitoring.status-ukt')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Status UKT Mhs</a>
                            </li>
                            <li class="{{request()->routeIs('bak.monitoring.pengisian-krs') || request()->routeIs('bak.monitoring.pengisian-krs.*') ? 'active' : ''}}">
                                <a href="{{route('bak.monitoring.pengisian-krs')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Pengisian KRS</a>
                            </li>
                            <li class="{{request()->routeIs('bak.monitoring.lulus-do') || request()->routeIs('bak.monitoring.lulus-do.*') ? 'active' : ''}}">
                                <a href="{{route('bak.monitoring.lulus-do')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Lulus Do</a>
                            </li>
                            <li class="{{request()->routeIs('bak.monitoring.pengisian-nilai') || request()->routeIs('bak.monitoring.pengisian-nilai.*') ? 'active' : ''}}">
                                <a href="{{route('bak.monitoring.pengisian-nilai')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Pengisian Nilai</a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{request()->routeIs('bak.wisuda.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-user-graduate"><span class="path1"></span><span class="path2"></span></i>
                            <span>Wisuda</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('bak.wisuda.pengaturan') || request()->routeIs('bak.wisuda.pengaturan.*') ? 'active' : ''}}">
                                <a href="{{route('bak.wisuda.pengaturan')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Pembukaan Wisuda</a>
                            </li>
                            <li class="{{request()->routeIs('bak.wisuda.peserta') || request()->routeIs('bak.wisuda.peserta.*') ? 'active' : ''}}">
                                <a href="{{route('bak.wisuda.peserta')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Calon Peserta Wisuda</a>
                            </li>
                            <li class="{{request()->routeIs('bak.wisuda.registrasi-ijazah.index') || request()->routeIs('bak.wisuda.registrasi-ijazah.*') ? 'active' : ''}}">
                                <a href="{{route('bak.wisuda.registrasi-ijazah.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Registrasi Ijazah</a>
                            </li>
                            <li class="{{request()->routeIs('bak.wisuda.ijazah.index') || request()->routeIs('bak.wisuda.ijazah.*') ? 'active' : ''}}">
                                <a href="{{route('bak.wisuda.ijazah.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Daftar Ijazah</a>
                            </li>
                            <li class="{{request()->routeIs('bak.wisuda.transkrip.index') || request()->routeIs('bak.wisuda.transkrip.*') ? 'active' : ''}}">
                                <a href="{{route('bak.wisuda.transkrip.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Transkrip Wisudawan</a>
                            </li>

                            <li class="{{request()->routeIs('bak.wisuda.album.index') || request()->routeIs('bak.wisuda.album.*') ? 'active' : ''}}">
                                <a href="{{route('bak.wisuda.album.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Album Wisudawan</a>
                            </li>
                            <li class="{{request()->routeIs('bak.wisuda.usept.index') || request()->routeIs('bak.wisuda.usept.*') ? 'active' : ''}}">
                                <a href="{{route('bak.wisuda.usept.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Nilai USEPT</a>
                            </li>
                        </ul>
                    </li>
                    {{-- <li class="treeview {{request()->routeIs('dosen.profile.*') ? 'active menu-open' : ''}}">
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
                        </ul>
                    </li> --}}
                    {{-- <li class="header">Perkuliahan</li>
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
                    <li class="header">Penilaian Mahasiswa</li>
                    <li class="{{request()->routeIs('dosen.penilaian.penilaian-perkuliahan') | request()->routeIs('dosen.penilaian.penilaian-perkuliahan.*')  ? 'active' : ''}}">
                        <a href="{{route('dosen.penilaian.penilaian-perkuliahan')}}">
                            <i class="fa fa-pencil-square-o"><span class="path1"></span><span class="path2"></span></i>
                            <span>Penilaian Perkuliahan</span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('dosen.penilaian.penilaian-sidang') ? 'active' : ''}}">
                        <a href="{{route('dosen.penilaian.penilaian-sidang')}}">
                            <i class="fa fa-pencil-square-o"><span class="path1"></span><span class="path2"></span></i>
                            <span>Penilaian Sidang</span>
                        </a>
                    </li>
                    <li class="header">Pembimbing Mahasiswa</li>
                    <li class="{{request()->routeIs('dosen.pembimbing.bimbingan-akademik') ? 'active' : ''}}">
                        <a href="{{route('dosen.pembimbing.bimbingan-akademik')}}">
                            <i class="fa fa-users"><span class="path1"></span><span class="path2"></span></i>
                            <span>Bimbingan Akademik</span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('dosen.pembimbing.bimbingan-tugas-akhir') | request()->routeIs('dosen.pembimbing.bimbingan-tugas-akhir.*') ? 'active' : ''}}">
                        <a href="{{route('dosen.pembimbing.bimbingan-tugas-akhir')}}">
                            <i class="fa fa-users"><span class="path1"></span><span class="path2"></span></i>
                            <span>Bimbingan Tugas Akhir</span>
                        </a>
                    </li>
                    <li class="header">Bantuan</li>
                    <li class="{{request()->routeIs('dosen.bantuan.ganti-password') ? 'active' : ''}}">
                        <a href="{{route('dosen.bantuan.ganti-password')}}">
                            <i class="fa fa-key"><span class="path1"></span><span class="path2"></span></i>
                            <span>Ganti Password</span>
                        </a>
                    </li> --}}
                    {{-- <li>
                        <a href="#" target="_blank">
                            <i class="fa fa-question"><span class="path1"></span><span class="path2"></span></i>
                            <span>Panduan Aplikasi</span>
                        </a>
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
