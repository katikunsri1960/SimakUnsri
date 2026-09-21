<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">
        <div class="multinav">
            <div class="multinav-scroll" style="height: 100%;">
                <!-- sidebar menu-->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">Menu Utama</li>
                    <li class="{{request()->routeIs('ditmawa') ? 'active' : ''}}">
                        <a href="{{route('ditmawa')}}">
                            <i class="fa fa-th-large"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="treeview {{request()->routeIs('ditmawa.pejabat.*') || request()->routeIs('ditmawa.gelar-lulusan') || request()->routeIs('ditmawa.gelar-lulusan.*') 
                        || request()->routeIs('ditmawa.usept-prodi') || request()->routeIs('ditmawa.usept-prodi.*')
                        || request()->routeIs('ditmawa.data-master.predikat') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-database"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Data Master</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="treeview {{request()->routeIs('ditmawa.data-master.dosen.*') ? 'active menu-open' : ''}}">
                                <a href="#">
                                    <i class="fa fa-users"><span<span class="path1"></span><span class="path2"></span></i>
                                    <span>Dosen</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="{{request()->routeIs('ditmawa.data-master.dosen.index') || request()->routeIs('ditmawa.data-master.dosen.index.*') ? 'active' : ''}}">
                                        <a href="{{route('ditmawa.data-master.dosen.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Daftar Dosen</a>
                                    </li>
                                    <li class="{{request()->routeIs('ditmawa.data-master.dosen.gelar') || request()->routeIs('ditmawa.data-master.dosen.gelar.*') ? 'active' : ''}}">
                                        <a href="{{route('ditmawa.data-master.dosen.gelar')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Penugasan & Gelar</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="{{request()->routeIs('ditmawa.mahasiswa') || request()->routeIs('ditmawa.mahasiswa.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.mahasiswa')}}">
                                    <i class="fa fa-graduation-cap"><span class="path1"></span><span
                                            class="path2"></span></i>
                                    <span>Mahasiswa</span>

                                </a>
                            </li>
                            <li class="treeview {{request()->routeIs('ditmawa.pejabat.*') ? 'active menu-open' : ''}}">
                                <a href="#">
                                    <i span class="fa fa-sitemap"><span class="path1"></span><span class="path2"></span></i>
                                    <span>Pejabat</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="{{request()->routeIs('ditmawa.pejabat.fakultas') || request()->routeIs('ditmawa.pejabat.fakultas.*') ? 'active' : ''}}">
                                        <a href="{{route('ditmawa.pejabat.fakultas')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Fakultas</a>
                                    </li>
                                    <li class="{{request()->routeIs('ditmawa.pejabat.universitas') || request()->routeIs('ditmawa.pejabat.universitas.*') ? 'active' : ''}}">
                                        <a href="{{route('ditmawa.pejabat.universitas')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Universitas</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="{{request()->routeIs('ditmawa.gelar-lulusan') || request()->routeIs('ditmawa.gelar-lulusan.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.gelar-lulusan')}}">
                                    <i class="fa fa-graduation-cap"><span class="path1"></span><span
                                            class="path2"></span></i>
                                    <span>Gelar Lulusan</span>

                                </a>
                            </li>
                            <li class="{{request()->routeIs('ditmawa.usept-prodi') || request()->routeIs('ditmawa.usept-prodi.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.usept-prodi')}}">
                                    <i class="fa fa-pen-square"><span class="path1"></span><span
                                            class="path2"></span></i>
                                    <span>USEPT Prodi</span>
                                </a>
                            </li>
                            <li class="{{request()->routeIs('ditmawa.data-master.predikat') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.data-master.predikat')}}">
                                    <i class="fa fa-graduation-cap"><span class="path1"></span><span
                                            class="path2"></span></i>
                                    <span>Predikat Lulusan</span>

                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{request()->routeIs('ditmawa.transkrip-nilai') || request()->routeIs('ditmawa.transkrip-nilai.*') ? 'active' : ''}}">
                        <a href="{{route('ditmawa.transkrip-nilai')}}">
                            <i class="fa fa-list-alt"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Transkrip Nilai</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                    </li>

                    <li class="header">Pengajuan</li>
                    <li class="{{request()->routeIs('ditmawa.beasiswa') ? 'active' : ''}}">
                        <a href="{{route('ditmawa.beasiswa')}}">
                            <i class="fa fa-book"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Beasiswa</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('ditmawa.pengajuan-cuti') ? 'active' : ''}}">
                        <a href="{{route('ditmawa.pengajuan-cuti')}}">
                            <i class="fa fa-exclamation-triangle"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Pengajuan Cuti</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('ditmawa.tunda-bayar') ? 'active' : ''}}">
                        <a href="{{route('ditmawa.tunda-bayar')}}">
                            <i class="fa fa-calendar-times"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Tunda Bayar</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                    </li>
                    

                    <li class="header">Kelulusan</li>
                    <li class="treeview {{request()->routeIs('ditmawa.yudisium.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-file-lines"><span class="path1"></span><span class="path2"></span></i>
                            <span>Yudisium</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            {{--
                            <li class="{{request()->routeIs('ditmawa.yudisium.pengaturan') || request()->routeIs('ditmawa.yudisium.pengaturan.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.yudisium.pengaturan')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Pembukaan yudisium</a>
                            </li>
                            --}}

                            <li class="{{request()->routeIs('ditmawa.yudisium.peserta') || request()->routeIs('ditmawa.yudisium.peserta.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.yudisium.peserta')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Calon Peserta yudisium</a>
                            </li>
                            <li class="{{request()->routeIs('ditmawa.yudisium.registrasi-ijazah.index') || request()->routeIs('ditmawa.yudisium.registrasi-ijazah.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.yudisium.registrasi-ijazah.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Registrasi Ijazah</a>
                            </li>
                            
                            {{--
                            <li class="{{request()->routeIs('ditmawa.yudisium.perbaikan-data') || request()->routeIs('ditmawa.perbaikan-data.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.yudisium.perbaikan-data')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Perbaikan Data</a>
                            </li>
                            
                            
                            <li class="{{request()->routeIs('ditmawa.yudisium.ijazah.index') || request()->routeIs('ditmawa.yudisium.ijazah.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.yudisium.ijazah.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Daftar Ijazah</a>
                            </li>
                            
                            <li class="{{request()->routeIs('ditmawa.yudisium.transkrip.index') || request()->routeIs('ditmawa.yudisium.transkrip.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.yudisium.transkrip.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Transkrip yudisiumwan</a>
                            </li>

                            <li class="{{request()->routeIs('ditmawa.yudisium.album.index') || request()->routeIs('ditmawa.yudisium.album.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.yudisium.album.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Album yudisiumwan</a>
                            </li>

                            <li class="{{request()->routeIs('ditmawa.skpi.data.index') || request()->routeIs('ditmawa.skpi.data.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.skpi.data.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>SKPI yudisiumwan</a>
                            </li>

                            <li class="{{request()->routeIs('ditmawa.yudisium.usept.index') || request()->routeIs('ditmawa.yudisium.usept.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.yudisium.usept.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Nilai USEPT</a>
                            </li>
                            --}}
                        </ul>
                    </li>

                    <li class="treeview {{request()->routeIs('ditmawa.wisuda.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-user-graduate"><span class="path1"></span><span class="path2"></span></i>
                            <span>Wisuda</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('ditmawa.wisuda.pengaturan') || request()->routeIs('ditmawa.wisuda.pengaturan.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.wisuda.pengaturan')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Pembukaan Wisuda</a>
                            </li>
                            <li class="{{request()->routeIs('ditmawa.wisuda.peserta') || request()->routeIs('ditmawa.wisuda.peserta.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.wisuda.peserta')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Calon Peserta Wisuda</a>
                            </li>

                            {{--
                            <li class="{{request()->routeIs('ditmawa.wisuda.registrasi-ijazah.index') || request()->routeIs('ditmawa.wisuda.registrasi-ijazah.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.wisuda.registrasi-ijazah.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Registrasi Ijazah</a>
                            </li>
                            <li class="{{request()->routeIs('ditmawa.wisuda.perbaikan-data') || request()->routeIs('ditmawa.perbaikan-data.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.wisuda.perbaikan-data')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Perbaikan Data</a>
                            </li>
                            --}}
                            
                            <li class="{{request()->routeIs('ditmawa.wisuda.ijazah.index') || request()->routeIs('ditmawa.wisuda.ijazah.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.wisuda.ijazah.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Daftar Ijazah</a>
                            </li>
                            
                            <li class="{{request()->routeIs('ditmawa.wisuda.transkrip.index') || request()->routeIs('ditmawa.wisuda.transkrip.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.wisuda.transkrip.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Transkrip Wisudawan</a>
                            </li>

                            <li class="{{request()->routeIs('ditmawa.wisuda.album.index') || request()->routeIs('ditmawa.wisuda.album.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.wisuda.album.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Album Wisudawan</a>
                            </li>

                            <li class="{{request()->routeIs('ditmawa.skpi.data.index') || request()->routeIs('ditmawa.skpi.data.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.skpi.data.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>SKPI Wisudawan</a>
                            </li>
                        </ul>
                    </li>

                    <li class="treeview {{request()->routeIs('ditmawa.skpi*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i class="fa fa-file"></i>
                            <span>Data Isian SKPI</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('ditmawa.skpi.bidang.index') || request()->routeIs('ditmawa.skpi.bidang.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.skpi.bidang.index')}}">
                                    <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                    Bidang SKPI
                                </a>
                            </li>
                            <li class="{{request()->routeIs('ditmawa.skpi.jenis.index') || request()->routeIs('ditmawa.skpi.jenis.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.skpi.jenis.index')}}">
                                    <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                    Jenis SKPI
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="header">Monitoring</li>
                    <li class="treeview {{request()->routeIs('ditmawa.monitoring.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-television"><span class="path1"></span><span class="path2"></span></i>
                            <span>Monitoring</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            {{--
                            <li class="{{request()->routeIs('ditmawa.monitoring.status-aipt')
                             ? 'active' : ''}}">
                                <a href="{{route('ditmawa.monitoring.status-aipt')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Data Akd AIPT</a>
                            </li>
                            --}}

                            <li class="treeview {{ request()->routeIs('ditmawa.monitoring.status-aipt*') ? 'active' : '' }}">
                                <a href="#">
                                    <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                    <span>Data Akd AIPT</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>

                                <ul class="treeview-menu">
                                    {{-- Dosen --}}
                                    <li class="{{ request()->routeIs('ditmawa.monitoring.status-aipt.dosen') ? 'active' : '' }}">
                                        <a href="{{ route('ditmawa.monitoring.status-aipt.dosen') }}">
                                            <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                            <span>Dosen</span>
                                        </a>
                                    </li>

                                    {{-- Mahasiswa --}}
                                    <li class="{{ request()->routeIs('ditmawa.monitoring.status-aipt.mahasiswa') ? 'active' : '' }}">
                                        <a href="{{ route('ditmawa.monitoring.status-aipt.mahasiswa') }}">
                                            <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                            <span>Mahasiswa</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="{{request()->routeIs('ditmawa.monitoring.status-mahasiswa') || request()->routeIs('ditmawa.monitoring.status-mahasiswa.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.monitoring.status-mahasiswa')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Status Akd Mhs</a>
                            </li>
                            <li class="{{request()->routeIs('ditmawa.monitoring.status-ukt') || request()->routeIs('ditmawa.monitoring.status-ukt.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.monitoring.status-ukt')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Status UKT Mhs</a>
                            </li>
                            <li class="{{request()->routeIs('ditmawa.monitoring.pengisian-krs') || request()->routeIs('ditmawa.monitoring.pengisian-krs.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.monitoring.pengisian-krs')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Pengisian KRS</a>
                            </li>
                            <li class="{{request()->routeIs('ditmawa.monitoring.lulus-do') || request()->routeIs('ditmawa.monitoring.lulus-do.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.monitoring.lulus-do')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Lulus Do</a>
                            </li>
                            <li class="{{request()->routeIs('ditmawa.monitoring.pengisian-nilai') || request()->routeIs('ditmawa.monitoring.pengisian-nilai.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.monitoring.pengisian-nilai')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Pengisian Nilai</a>
                            </li>
                            <li class="{{request()->routeIs('ditmawa.monitoring.cpl-kurikulum') || request()->routeIs('ditmawa.monitoring.cpl-kurikulum.*') ? 'active' : ''}}">
                                <a href="{{route('ditmawa.monitoring.cpl-kurikulum')}}">
                                    <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                    CPL Kurikulum
                                </a>
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
