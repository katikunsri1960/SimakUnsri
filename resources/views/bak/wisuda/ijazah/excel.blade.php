<table>
    <thead>
        <tr>
            <th>NO.</th>
            <th>PISN</th>
            <th>NAMA</th>
            <th>NIM</th>
            <th>TEMPAT LAHIR</th>
            <th>TANGGAL LAHIR</th>
            <th>TANGGAL MASUK</th>
            <th>TANGGAL KELUAR</th>
            <th>LAMA STUDI</th>
            <th>PROGRAM STUDI</th>
            <th>GELAR</th>
            <th>PREDIKAT KELULUSAN</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $i => $d)
            <tr>
                <td>{{ $i+1 }}</td>

                {{-- NOMOR IJAZAH NASIONAL (Khusus Profesi pakai no_sertifikat) --}}
                <td>
                    {{ $d->jenjang === 'Profesi' ? ($d->no_sertifikat ?? '-') : ($d->no_ijazah ?? '-') }}
                </td>

                <td>{{ Str::title($d->nama_mahasiswa) }}</td>
                <td>{{ $d->nim }}</td>
                <td>{{ Str::title($d->tempat_lahir) }}</td>

                <td>{{ \Carbon\Carbon::parse($d->tanggal_lahir)->format('d-m-Y') }}</td>

                {{-- Tanggal Yudisium (tanggal_keluar) --}}
                <td>
                    {{ $d->riwayat_pendidikan
                        ? \Carbon\Carbon::parse($d->riwayat_pendidikan->tanggal_daftar)->format('d-m-Y') 
                        : '-' 
                    }}
                </td>
                
                <td>
                    {{ $d->tgl_keluar
                        ? \Carbon\Carbon::parse($d->tanggal_keluar)->format('d-m-Y') 
                        : '-' 
                    }}
                </td>

                <td>{{ $d->lama_studi ? $d->lama_studi . ' Bulan' : '-' }}</td>

                <td>{{ $d->jenjang }} - {{ $d->nama_prodi }}</td>
                <td>{{ $d->gelar }}</td>
                <td>{{ $d->predikat_kelulusan ? $d->predikat_kelulusan->indonesia : '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
