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
                    <li class="{{request()->routeIs('mahasiswa.krs.*') || request()->routeIs('mahasiswa.krs') ||
                                request()->routeIs('mahasiswa.perkuliahan.mbkm.*')
                    ? 'active menu-open' : ''}}">
                        <a href="{{route('mahasiswa.krs')}}">
                            <i class="fa fa-newspaper-o"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Kartu Rencana Studi</span>
                        </a>
                    </li>
                    <li class="{{request()->routeIs('mahasiswa.perkuliahan.nilai-perkuliahan') | request()->routeIs('mahasiswa.perkuliahan.nilai-perkuliahan.*') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.perkuliahan.devop')}}">
                            <i class="fa fa-line-chart"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Nilai Perkuliahan</span>
                        </a>
                    </li>
                    {{--<li class="{{request()->routeIs('mahasiswa.perkuliahan.nilai-perkuliahan') | request()->routeIs('mahasiswa.perkuliahan.nilai-perkuliahan.*') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.perkuliahan.nilai-perkuliahan')}}">
                            <i class="fa fa-line-chart"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Nilai Perkuliahan</span>
                        </a>
                    </li>--}}
                    <li class="{{request()->routeIs('mahasiswa.perkuliahan.nilai-usept') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.perkuliahan.nilai-usept')}}">
                            <i class="fa fa-chart-line"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Nilai USEPT</span>
                        </a>
                    </li>
                    {{--
                     <li class="{{request()->routeIs('mahasiswa.wisuda.index') | request()->routeIs('mahasiswa.wisuda.*') ? 'active' : ''}}">
                        <a href="#">
                            <i class="fa fa-user-graduate"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Wisuda</span>
                        </a>
                    </li> 
                    --}}
                    
                    <li class="{{request()->routeIs('mahasiswa.wisuda.index') | request()->routeIs('mahasiswa.wisuda.*') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.wisuda.index')}}">
                            <i class="fa fa-graduation-cap"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Wisuda</span>
                        </a>
                    </li>
                    
                    
                    <li class="header">Bimbingan</li>
                    <li class="{{request()->routeIs('mahasiswa.bimbingan.bimbingan-tugas-akhir') | request()->routeIs('mahasiswa.bimbingan.bimbingan-tugas-akhir.asistensi') ? 'active' : ''}}">
                        <a href="{{route('mahasiswa.bimbingan.bimbingan-tugas-akhir')}}">
                            <i class="fa fa-users"><span class="path1"></span><span class="path2"></span></i>
                            <span>Bimbingan Tugas Akhir</span>
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
                            {{-- <!-- <li class="{{request()->routeIs('mahasiswa.prestasi.prestasi-non-pendanaan')
                                ? 'active' : ''}}">
                                <a href="{{route('mahasiswa.prestasi.prestasi-non-pendanaan')}}"><i class="fa fa-trophy"><span
                                            class="path1"></span><span class="path2"></span></i>Prestasi Pendanaan UNSRI</a>
                            </li> --> --}}
                        </ul>
                    </li>

                    <li class="header">LAIN-LAIN</li>
                    <li class="treeview {{request()->routeIs('mahasiswa.pengajuan-cuti.index') || request()->routeIs('mahasiswa.pengajuan-cuti.*') ||
                                            request()->routeIs('mahasiswa.penundaan-bayar.index') || request()->routeIs('mahasiswa.penundaan-bayar.*')
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
                            <li class="{{request()->routeIs('mahasiswa.pengajuan-cuti.index') || request()->routeIs('mahasiswa.pengajuan-cuti.*')  ? 'active' : ''}}">
                                <a href="{{route('mahasiswa.pengajuan-cuti.index')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>
                                Cuti Kuliah</a>
                            </li>
                            <li class="{{request()->routeIs('mahasiswa.penundaan-bayar.index') || request()->routeIs('mahasiswa.penundaan-bayar.*')  ? 'active' : ''}}">
                                <a href="{{route('mahasiswa.penundaan-bayar.index')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>
                                Penundaan Bayar</a>
                            </li>
                        </ul>
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
