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
                            {{-- <li class="{{request()->routeIs('fakultas.data-master.kurikulum') || request()->routeIs('fakultas.data-master.kurikulum.*') ?  'active' : ''}}">
                                <a href="{{route('fakultas.data-master.kurikulum')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Kurikulum</a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.data-master.mata-kuliah') || request()->routeIs('fakultas.data-master.mata-kuliah.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-master.mata-kuliah')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Mata Kuliah</a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.data-master.matkul-merdeka') || request()->routeIs('fakultas.data-master.matkul-merdeka.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-master.matkul-merdeka')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>MK MBKM</a>
                            </li>
                            <li class="{{request()->routeIs('fakultas.data-master.ruang-perkuliahan') || request()->routeIs('fakultas.data-master.ruang-perkuliahan.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-master.ruang-perkuliahan')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Ruang Perkuliahan</a>
                            </li> --}}
                            <!-- <li class="{{request()->routeIs('fakultas.data-master.ruang-perkuliahan') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-master.ruang-perkuliahan')}}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Pengumuman</a>
                            </li> -->
                        </ul>
                    </li>
                    <li class="treeview {{request()->routeIs('prodi.data-akademik.*') ? 'active menu-open' : ''}}">
                        <a href="#">
                            <i span class="fa fa-graduation-cap"><span class="path1"></span><span
                                    class="path2"></span></i>
                            <span>Data Akademik</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            {{-- <li class="{{request()->routeIs('prodi.data-akademik.kelas-penjadwalan') | request()->routeIs('prodi.data-akademik.kelas-penjadwalan.*') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-akademik.kelas-penjadwalan')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Kelas dan Penjadwalan</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.data-akademik.krs') || request()->routeIs('prodi.data-akademik.krs.*') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-akademik.krs')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Kartu Rencana Studi</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.data-akademik.khs') || request()->routeIs('prodi.data-akademik.khs.*') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-akademik.khs')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Kartu Hasil Studi</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.data-akademik.tugas-akhir') || request()->routeIs('prodi.data-akademik.tugas-akhir.*') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-akademik.tugas-akhir')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Aktivitas @if(Auth::user()->fk->nama_jenjang_pendidikan == 'S1')Skripsi
                                    @elseif (Auth::user()->fk->nama_jenjang_pendidikan == 'S2')Tesis
                                    @elseif (Auth::user()->fk->nama_jenjang_pendidikan == 'S3')Disertasi
                                    @else Tugas Akhir
                                    @endif
                                     Mhs</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.data-akademik.non-tugas-akhir') || request()->routeIs('prodi.data-akademik.non-tugas-akhir.*') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-akademik.non-tugas-akhir')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Aktivitas Non @if(Auth::user()->fk->nama_jenjang_pendidikan == 'S1')Skripsi
                                    @elseif (Auth::user()->fk->nama_jenjang_pendidikan == 'S2')Tesis
                                    @elseif (Auth::user()->fk->nama_jenjang_pendidikan == 'S3')Disertasi
                                    @else Tugas Akhir
                                    @endif</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.data-akademik.sidang-mahasiswa') || request()->routeIs('prodi.data-akademik.sidang-mahasiswa.*') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-akademik.sidang-mahasiswa')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Sidang Mahasiswa</a>
                            </li>
                            <li class="{{request()->routeIs('prodi.data-akademik.yudisium-mahasiswa') || request()->routeIs('prodi.data-akademik.yudisium-mahasiswa.*') ? 'active' : ''}}">
                                <a href="{{route('prodi.data-akademik.yudisium-mahasiswa')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Yudisium Mahasiswa</a>
                            </li> --}}
                            <li class="{{request()->routeIs('fakultas.data-akademik.transkrip-mahasiswa') || request()->routeIs('fakultas.data-akademik.transkrip-mahasiswa.*') ? 'active' : ''}}">
                                <a href="{{route('fakultas.data-akademik.transkrip-mahasiswa')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Daftar Transkrip Nilai</a>
                            </li>
                        </ul>
                    </li>
                    <li class="header">LAIN-LAIN</li>
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
