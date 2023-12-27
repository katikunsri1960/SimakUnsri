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
                            <i class="fa fa-line-chart"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="treeview {{request()->routeIs('dosen.profile.biodata') | request()->routeIs('dosen.profile.aktivitas') | request()->routeIs('dosen.profile.mengajar') ? 'active menu-open' : ''}}">
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
                            <li class="{{request()->routeIs('dosen.profile.aktivitas') ? 'active' : ''}}">
                                <a href="{{route('dosen.profile.aktivitas')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Aktivitas Dosen</a>
                            </li>
                            <li class="{{request()->routeIs('dosen.profile.mengajar') ? 'active' : ''}}">
                                <a href="{{route('dosen.profile.mengajar')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Mengajar Dosen</a>
                            </li>
                            <li class="{{request()->routeIs('dosen.profile.riwayat_pendidikan') ? 'active' : ''}}">
                                <a href="{{route('dosen.profile.riwayat_pendidikan')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Riwayat Pendidikan</a>
                            </li>
                            <!-- <li>
                                <a href="contact_app_chat.html"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Penelitian Dosen</a>
                            </li>
                            <li>
                                <a href="contact_app_chat.html"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Publikasi Dosen</a>
                            </li>
                            <li>
                                <a href="contact_app_chat.html"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Pengabdian Dosen</a>
                            </li> -->
                        </ul>
                    </li>
                    <li class="{{request()->routeIs('dosen.kalender_akademik') ? 'active' : ''}}">
                        <a href="{{route('dosen.kalender_akademik')}}">
                            <i class="fa fa-calendar-o"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Kalender Akademik</span>
                        </a>
                    </li>
                    <!-- <li class="{{request()->routeIs('dosen') ? 'active' : ''}}">
                        <a href="{{route('dosen')}}">
                            <i class="fa fa-list"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Honor Akademik</span>
                        </a>
                    </li> -->
                    <!-- <li class="{{request()->routeIs('dosen') ? 'active' : ''}}">
                        <a href="{{route('dosen')}}">
                            <i class="fa fa-bullhorn"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Pengumuman</span>
                        </a>
                    </li> -->
                    <li class="header">Perkuliahan</li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-calendar-o"><span class="path1"></span><span class="path2"></span></i>
                            <span>Jadwal Dosen</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="ui_grid.html"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Kesediaan Waktu Dosen</a></li>
                            <li><a href="ui_grid.html"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Jadwal Kuliah</a></li>
                            <li><a href="ui_grid.html"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Jadwal Bimbingan</a></li>
                        </ul>
                    </li> 
                    <li class="{{request()->routeIs('univ') ? 'active' : ''}}">
                        <a href="https://elearning.unsri.ac.id/" target="_blank">
                            <i class="fa fa-desktop"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>E-Learning UNSRI</span>
                        </a>
                    </li> 
                    <li class="{{request()->routeIs('univ') ? 'active' : ''}}">
                        <a href="https://uscamz.unsri.ac.id/b/signin" target="_blank">
                            <i class="fa fa-video-camera"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>USCAMZ UNSRI</span>
                        </a>
                    </li> 
                    <li class="header">Penilaian Mahasiswa</li>
                    <li class="{{request()->routeIs('univ') ? 'active' : ''}}">
                        <a href="#">
                            <i class="fa fa-percent"><span class="path1"></span><span class="path2"></span></i>
                            <span>Presentase Penilaian</span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('univ') ? 'active' : ''}}">
                        <a href="#">
                            <i class="fa fa-pencil-square-o"><span class="path1"></span><span class="path2"></span></i>
                            <span>Penilaian Perkuliahan</span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('univ') ? 'active' : ''}}">
                        <a href="#">
                            <i class="fa fa-pencil-square-o"><span class="path1"></span><span class="path2"></span></i>
                            <span>Penilaian Sidang</span>
                        </a>
                    </li>  
                    <li class="header">Pembimbing Mahasiswa</li>
                    <li class="{{request()->routeIs('univ') ? 'active' : ''}}">
                        <a href="#">
                            <i class="fa fa-users"><span class="path1"></span><span class="path2"></span></i>
                            <span>Bimbingan Akademik</span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('univ') ? 'active' : ''}}">
                        <a href="#">
                            <i class="fa fa-users"><span class="path1"></span><span class="path2"></span></i>
                            <span>Bimbingan Non-Akademik</span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('univ') ? 'active' : ''}}">
                        <a href="#">
                            <i class="fa fa-users"><span class="path1"></span><span class="path2"></span></i>
                            <span>Bimbingan Tugas Akhir</span>
                        </a>
                    </li> 
                    <li class="header">Bantuan</li>
                    <li class="{{request()->routeIs('univ') ? 'active' : ''}}">
                        <a href="#">
                            <i class="fa fa-key"><span class="path1"></span><span class="path2"></span></i>
                            <span>Ganti Password</span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('univ') ? 'active' : ''}}">
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
