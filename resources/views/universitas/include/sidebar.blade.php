<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">
        <div class="multinav">
            <div class="multinav-scroll" style="height: 100%;">
                <!-- sidebar menu-->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">Menu Utama</li>
                    <li class="{{request()->routeIs('univ') ? 'active' : ''}}">
                        <a href="{{route('univ')}}">
                            <i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>
                            <span>Dashboard</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                    </li>
                    <li class="treeview {{request()->routeIs('univ.mahasiswa.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-graduation-cap"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Mahasiswa</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('univ.mahasiswa') ? 'active' : ''}}"><a
                                    href="{{route('univ.mahasiswa')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Daftar Mahasiswa</a>
                            </li>
                            <li><a href="contact_app_chat.html"><i class="icon-Commit"><span class="path1"></span><span
                                            class="path2"></span></i>Input Mahasiswa</a></li>
                        </ul>
                    </li>
                    <li class="treeview {{request()->routeIs('univ.dosen.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-person-chalkboard"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Dosen</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('univ.dosen') ? 'active' : ''}}"><a
                                    href="{{route('univ.dosen')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Daftar Dosen</a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{request()->routeIs('univ.perkuliahan.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-scroll"><span class="path1"></span><span class="path2"></span></i>
                            <span>Perkuliahan</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('univ.perkuliahan.kelas-kuliah') ? 'active' : ''}}"><a
                                    href="{{route('univ.perkuliahan.kelas-kuliah')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Kelas Perkuliahan</a>
                            </li>
                            <li class="{{request()->routeIs('univ.perkuliahan.nilai-perkuliahan') ? 'active' : ''}}"><a
                                    href="{{route('univ.perkuliahan.nilai-perkuliahan')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Nilai Perkuliahan</a>
                            </li>
                            <li class="{{request()->routeIs('univ.perkuliahan.aktivitas-kuliah') ? 'active' : ''}}"><a
                                    href="{{route('univ.perkuliahan.aktivitas-kuliah')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Aktivitas Kuliah</a>
                            </li>
                            <li class="{{request()->routeIs('univ.perkuliahan.aktivitas-mahasiswa') ? 'active' : ''}}">
                                <a href="{{route('univ.perkuliahan.aktivitas-mahasiswa')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Aktivitas Mahasiswa</a>
                            </li>
                            <li class="{{request()->routeIs('univ.perkuliahan.konversi-aktivitas') ? 'active' : ''}}">
                                <a href="{{route('univ.perkuliahan.konversi-aktivitas')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Konversi Aktivitas</a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{request()->routeIs('univ.referensi.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="icon-Library"><span class="path1"></span><span class="path2"></span></i>
                            <span>Referensi</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('univ.referensi.prodi') ? 'active' : ''}}"><a
                                    href="{{route('univ.referensi.prodi')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Program Studi</a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="treeview {{request()->routeIs('univ.kurikulum.*') | request()->routeIs('univ.kurikulum') | request()->routeIs('univ.mata-kuliah') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="icon-Library"><span class="path1"></span><span class="path2"></span></i>
                            <span>Kurikulum</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('univ.mata-kuliah') ? 'active' : ''}}">
                                <a href="{{route('univ.mata-kuliah')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Mata Kuliah</a>
                            </li>
                            <li
                                class="{{request()->routeIs('univ.kurikulum.*') | request()->routeIs('univ.kurikulum') ? 'active' : ''}}">
                                <a href="{{route('univ.kurikulum')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>List Kurikulum</a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{request()->routeIs('univ.pengaturan.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-cog"><span class="path1"></span><span class="path2"></span></i>
                            <span>Pengaturan</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('univ.pengaturan.periode-perkuliahan') ? 'active' : ''}}"><a
                                    href="{{route('univ.pengaturan.periode-perkuliahan')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Periode Perkuliahan</a>
                            </li>
                            <li class="{{request()->routeIs('univ.pengaturan.skala-nilai') ? 'active' : ''}}"><a
                                href="{{route('univ.pengaturan.skala-nilai')}}"><i class="icon-Commit"><span
                                        class="path1"></span><span class="path2"></span></i>Skala Nilai</a>
                        </li>
                            <li class="{{request()->routeIs('univ.pengaturan.semester-aktif') ? 'active' : ''}}"><a
                                    href="{{route('univ.pengaturan.semester-aktif')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Semester Aktif</a>
                            </li>
                        </ul>
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
