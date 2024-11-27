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
                            <li class="{{request()->routeIs('univ.perkuliahan.transkrip') ? 'active' : ''}}">
                                <a href="{{route('univ.perkuliahan.transkrip')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Transkrip</a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{request()->routeIs('univ.referensi.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-asterisk"><span class="path1"></span><span class="path2"></span></i>
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
                    <li class="{{request()->routeIs('univ.kuisioner') | request()->routeIs('univ.kuisioner.*') ? 'active' : ''}}">
                        <a href="{{route('univ.kuisioner')}}">
                            <i class="fa fa-comments"><span class="path1"></span><span class="path2"></span></i>
                            <span>Kuisioner</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('univ.cuti-kuliah') ? 'active' : ''}}">
                        <a href="{{route('univ.cuti-kuliah')}}">
                            <i span class="fa fa-sign-out-alt"><span class="path1"></span><span class="path2"></span></i>
                            <span>List Cuti Kuliah</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                    </li>
                    <li class="treeview {{request()->routeIs('univ.pembayaran-manual') | request()->routeIs('univ.pembayaran-manual.*')
                                        | request()->routeIs('univ.krs-manual') | request()->routeIs('univ.krs-manual.*') | request()->routeIs('univ.p-bayar') | request()->routeIs('univ.p-bayar.*')
                                        | request()->routeIs('univ.pembatalan-krs') | request()->routeIs('univ.pembatalan-krs.*') | request()->routeIs('univ.beasiswa') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-money"><span class="path1"></span><span class="path2"></span></i>
                            <span>Manual KRS</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('univ.pembatalan-krs') | request()->routeIs('univ.pembatalan-krs.*') ? 'active' : ''}}"><a
                                href="{{route('univ.pembatalan-krs')}}"><i class="icon-Commit"><span
                                        class="path1"></span><span class="path2"></span></i>Pembatalan KRS</a>
                            </li>
                            <li class="{{request()->routeIs('univ.pembayaran-manual') | request()->routeIs('univ.pembayaran-manual.*') ? 'active' : ''}}"><a
                                    href="{{route('univ.pembayaran-manual')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Pembayaran Manual</a>
                            </li>
                            <li class="{{request()->routeIs('univ.krs-manual') | request()->routeIs('univ.krs-manual.*') ? 'active' : ''}}"><a
                                    href="{{route('univ.krs-manual')}}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Batas Isi KRS Manual</a>
                            </li>
                            <li class="{{request()->routeIs('univ.p-bayar') | request()->routeIs('univ.p-bayar.*') ? 'active' : ''}}"><a
                                href="{{route('univ.p-bayar')}}"><i class="icon-Commit"><span
                                        class="path1"></span><span class="path2"></span></i>Penundaan Bayar</a>
                            </li>
                            <li class="{{request()->routeIs('univ.cuti-manual') | request()->routeIs('univ.cuti-manual.*') ? 'active' : ''}}"><a
                                href="{{route('univ.cuti-manual')}}"><i class="icon-Commit"><span
                                        class="path1"></span><span class="path2"></span></i>Pengajuan Cuti Manual</a>
                            </li>
                            <li class="{{request()->routeIs('univ.beasiswa') ? 'active' : ''}}"><a
                                href="{{route('univ.beasiswa')}}"><i class="icon-Commit"><span
                                        class="path1"></span><span class="path2"></span></i>Beasiswa</a>
                            </li>

                        </ul>
                    </li>
                    <li class="treeview {{request()->routeIs('univ.feeder-upload.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-cloud-upload-alt"><span class="path1"></span><span class="path2"></span></i>
                            <span>Feeder Upload</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('univ.feeder-upload.akm') ? 'active' : ''}}">
                                <a href="{{route('univ.feeder-upload.akm')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>AKM</a>
                            </li>
                            <li class="treeview {{request()->routeIs('univ.feeder-upload.mata-kuliah.*') ? 'active menu-open' : ''}}">
                                <a href="#">
                                    <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Matakuliah
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="{{request()->routeIs('univ.feeder-upload.mata-kuliah.rps') ? 'active' : ''}}">
                                        <a href="{{route('univ.feeder-upload.mata-kuliah.rps')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>RPS</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview {{request()->routeIs('univ.feeder-upload.kelas') | request()->routeIs('univ.feeder-upload.perkuliahan.*') ? 'active menu-open' : ''}}">
                                <a href="#">
                                    <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Perkuliahan
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="{{request()->routeIs('univ.feeder-upload.perkuliahan.kelas') ? 'active' : ''}}">
                                        <a href="{{route('univ.feeder-upload.perkuliahan.kelas')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Kelas</a>
                                    </li>
                                    <li class="{{request()->routeIs('univ.feeder-upload.perkuliahan.krs') ? 'active' : ''}}">
                                        <a href="{{route('univ.feeder-upload.perkuliahan.krs')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>KRS</a>
                                    </li>
                                    <li class="{{request()->routeIs('univ.feeder-upload.perkuliahan.komponen-evaluasi') ? 'active' : ''}}">
                                        <a href="{{route('univ.feeder-upload.perkuliahan.komponen-evaluasi')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Komponen Evaluasi</a>
                                    </li>
                                    <li class="{{request()->routeIs('univ.feeder-upload.perkuliahan.nilai-komponen') ? 'active' : ''}}">
                                        <a href="{{route('univ.feeder-upload.perkuliahan.nilai-komponen')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Nilai Komponen</a>
                                    </li>
                                    <li class="{{request()->routeIs('univ.feeder-upload.perkuliahan.nilai-kelas') ? 'active' : ''}}">
                                        <a href="{{route('univ.feeder-upload.perkuliahan.nilai-kelas')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Nilai Kelas</a>
                                    </li>
                                    <li class="{{request()->routeIs('univ.feeder-upload.perkuliahan.nilai-transfer') ? 'active' : ''}}">
                                        <a href="{{route('univ.feeder-upload.perkuliahan.nilai-transfer')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Nilai Transfer</a>
                                    </li>
                                    <li class="{{request()->routeIs('univ.feeder-upload.perkuliahan.dosen-ajar') ? 'active' : ''}}">
                                        <a href="{{route('univ.feeder-upload.perkuliahan.dosen-ajar')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Dosen Ajar</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="treeview {{request()->routeIs('univ.feeder-upload.aktivitas.*') ? 'active menu-open' : ''}}">
                                <a href="#">
                                    <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Aktivitas
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="{{request()->routeIs('univ.feeder-upload.aktivitas') ? 'active' : ''}}">
                                        <a href="{{route('univ.feeder-upload.aktivitas')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Aktivitas Mahasiswa</a>
                                    </li>
                                    <li class="{{request()->routeIs('univ.feeder-upload.aktivitas.anggota') ? 'active' : ''}}">
                                        <a href="{{route('univ.feeder-upload.aktivitas.anggota')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Anggota Aktivitas</a>
                                    </li>
                                    <li class="{{request()->routeIs('univ.feeder-upload.aktivitas.pembimbing') ? 'active' : ''}}">
                                        <a href="{{route('univ.feeder-upload.aktivitas.pembimbing')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Pembimbing Aktivitas</a>
                                    </li>
                                    <li class="{{request()->routeIs('univ.feeder-upload.aktivitas.penguji') ? 'active' : ''}}">
                                        <a href="{{route('univ.feeder-upload.aktivitas.penguji')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Penguji Aktivitas</a>
                                    </li>
                                    <li class="{{request()->routeIs('univ.feeder-upload.aktivitas.nilai-konversi') ? 'active' : ''}}">
                                        <a href="{{route('univ.feeder-upload.aktivitas.nilai-konversi')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Nilai Konversi</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{request()->routeIs('univ.monitoring.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-television"><span class="path1"></span><span class="path2"></span></i>
                            <span>Monitoring</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{request()->routeIs('univ.monitoring.pengisian-krs') || request()->routeIs('univ.monitoring.pengisian-krs.*') ? 'active' : ''}}">
                                <a href="{{route('univ.monitoring.pengisian-krs')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Pengisian KRS</a>
                            </li>
                            <li class="{{request()->routeIs('univ.monitoring.lulus-do') || request()->routeIs('univ.monitoring.lulus-do.*') ? 'active' : ''}}">
                                <a href="{{route('univ.monitoring.lulus-do')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Lulus Do</a>
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
                            <li class="{{request()->routeIs('univ.pengaturan.akun') ? 'active' : ''}}"><a
                                href="{{route('univ.pengaturan.akun')}}"><i class="icon-Commit"><span
                                        class="path1"></span><span class="path2"></span></i>Akun</a>
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
