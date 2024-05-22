<?php

namespace App\Exports;

use App\Models\Perkuliahan\KelasKuliah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;

class ExportDPNA implements FromCollection, WithHeadings, WithEvents, WithMapping
{
    public function __construct(string $kelas)
    {
        $this->kelas = $kelas;
    }  
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // $data_kelas = KelasKuliah::LeftJoin('nilai_perkuliahans', 'nilai_perkulians.id_kelas_kuliah', 'kelas_kuliahs.id_kelas_kuliah')
        //             ->select('kelas_kuliahs.kode_mata_kuliah', 'kelas_kuliahs.nama_mata_kuliah', 'kelas_kuliahs.nama_kelas_kuliah', 'peserta_kelas_kuliahs.nim', 'peserta_kelas_kuliahs.nama_mahasiswa')
        //             ->addSelect(DB::raw('select nilai_komponen_evaluasis.nilai_komp_eval from nilai_komponen_evaluasis where nilai_komponen_evaluasis.id_registrasi_mahasiswa=peserta_kelas_kuliahs.id_registrasi_mahasiswa AND nilai_komponen_evaluasis.id_kelas=kelas_kuliahs.id_kelas_kuliah AND nilai_komponen_evaluasis.id_jns_eval="2"'))
        //             ->addSelect(DB::raw('select nilai_komponen_evaluasis.nilai_komp_eval FROM nilai_komponen_evaluasis WHERE nilai_komponen_evaluasis.id_registrasi_mahasiswa=peserta_kelas_kuliahs.id_registrasi_mahasiswa AND nilai_komponen_evaluasis.id_kelas=kelas_kuliahs.id_kelas_kuliah AND nilai_komponen_evaluasis.id_jns_eval="3"'))
        //             ->addSelect(DB::raw('select nilai_komponen_evaluasis.nilai_komp_eval FROM nilai_komponen_evaluasis WHERE nilai_komponen_evaluasis.id_registrasi_mahasiswa=peserta_kelas_kuliahs.id_registrasi_mahasiswa AND nilai_komponen_evaluasis.id_kelas=kelas_kuliahs.id_kelas_kuliah AND nilai_komponen_evaluasis.id_jns_eval="4" AND nilai_komponen_evaluasis.nama="TGS"'))
        //             ->addSelect(DB::raw('select nilai_komponen_evaluasis.nilai_komp_eval FROM nilai_komponen_evaluasis WHERE nilai_komponen_evaluasis.id_registrasi_mahasiswa=peserta_kelas_kuliahs.id_registrasi_mahasiswa AND nilai_komponen_evaluasis.id_kelas=kelas_kuliahs.id_kelas_kuliah AND nilai_komponen_evaluasis.id_jns_eval="4" AND nilai_komponen_evaluasis.nama="QIZ"'))
        //             ->addSelect(DB::raw('select nilai_komponen_evaluasis.nilai_komp_eval FROM nilai_komponen_evaluasis WHERE nilai_komponen_evaluasis.id_registrasi_mahasiswa=peserta_kelas_kuliahs.id_registrasi_mahasiswa AND nilai_komponen_evaluasis.id_kelas=kelas_kuliahs.id_kelas_kuliah AND nilai_komponen_evaluasis.id_jns_eval="4" AND nilai_komponen_evaluasis.nama="UTS"'))
        //             ->addSelect(DB::raw('select nilai_komponen_evaluasis.nilai_komp_eval FROM nilai_komponen_evaluasis WHERE nilai_komponen_evaluasis.id_registrasi_mahasiswa=peserta_kelas_kuliahs.id_registrasi_mahasiswa AND nilai_komponen_evaluasis.id_kelas=kelas_kuliahs.id_kelas_kuliah AND nilai_komponen_evaluasis.id_jns_eval="4" AND nilai_komponen_evaluasis.nama="UAS"'))
        //             ->where('kelas_kuliahs.id_kelas_kuliah', $this->kelas)
        //             ->get();

        $data_kelas = KelasKuliah::select([
            'kelas_kuliahs.kode_mata_kuliah',
            'kelas_kuliahs.nama_mata_kuliah',
            'kelas_kuliahs.nama_kelas_kuliah',
            'peserta_kelas_kuliahs.nim',
            'peserta_kelas_kuliahs.nama_mahasiswa',
            DB::raw("(SELECT nilai_komponen_evaluasis.nilai_komp_eval 
                      FROM nilai_komponen_evaluasis 
                      WHERE nilai_komponen_evaluasis.id_registrasi_mahasiswa = peserta_kelas_kuliahs.id_registrasi_mahasiswa 
                        AND nilai_komponen_evaluasis.id_kelas = kelas_kuliahs.id_kelas_kuliah 
                        AND nilai_komponen_evaluasis.id_jns_eval = '2') AS nilai_keaktifan_kelas"),
            DB::raw("(SELECT nilai_komponen_evaluasis.nilai_komp_eval 
                      FROM nilai_komponen_evaluasis 
                      WHERE nilai_komponen_evaluasis.id_registrasi_mahasiswa = peserta_kelas_kuliahs.id_registrasi_mahasiswa 
                        AND nilai_komponen_evaluasis.id_kelas = kelas_kuliahs.id_kelas_kuliah 
                        AND nilai_komponen_evaluasis.id_jns_eval = '3') AS nilai_projek"),
            DB::raw("(SELECT nilai_komponen_evaluasis.nilai_komp_eval 
                      FROM nilai_komponen_evaluasis 
                      WHERE nilai_komponen_evaluasis.id_registrasi_mahasiswa = peserta_kelas_kuliahs.id_registrasi_mahasiswa 
                        AND nilai_komponen_evaluasis.id_kelas = kelas_kuliahs.id_kelas_kuliah 
                        AND nilai_komponen_evaluasis.id_jns_eval = '4' 
                        AND nilai_komponen_evaluasis.nama = 'TGS') AS nilai_tugas"),
            DB::raw("(SELECT nilai_komponen_evaluasis.nilai_komp_eval 
                      FROM nilai_komponen_evaluasis 
                      WHERE nilai_komponen_evaluasis.id_registrasi_mahasiswa = peserta_kelas_kuliahs.id_registrasi_mahasiswa 
                        AND nilai_komponen_evaluasis.id_kelas = kelas_kuliahs.id_kelas_kuliah 
                        AND nilai_komponen_evaluasis.id_jns_eval = '4' 
                        AND nilai_komponen_evaluasis.nama = 'QIZ') AS nilai_kuis"),
            DB::raw("(SELECT nilai_komponen_evaluasis.nilai_komp_eval 
                      FROM nilai_komponen_evaluasis 
                      WHERE nilai_komponen_evaluasis.id_registrasi_mahasiswa = peserta_kelas_kuliahs.id_registrasi_mahasiswa 
                        AND nilai_komponen_evaluasis.id_kelas = kelas_kuliahs.id_kelas_kuliah 
                        AND nilai_komponen_evaluasis.id_jns_eval = '4' 
                        AND nilai_komponen_evaluasis.nama = 'UTS') AS nilai_uts"),
            DB::raw("(SELECT nilai_komponen_evaluasis.nilai_komp_eval 
                      FROM nilai_komponen_evaluasis 
                      WHERE nilai_komponen_evaluasis.id_registrasi_mahasiswa = peserta_kelas_kuliahs.id_registrasi_mahasiswa 
                        AND nilai_komponen_evaluasis.id_kelas = kelas_kuliahs.id_kelas_kuliah 
                        AND nilai_komponen_evaluasis.id_jns_eval = '4' 
                        AND nilai_komponen_evaluasis.nama = 'UAS') AS nilai_uas"),
        ])
        ->LeftJoin('peserta_kelas_kuliahs', 'kelas_kuliahs.id_kelas_kuliah', '=', 'peserta_kelas_kuliahs.id_kelas_kuliah')
        ->where('kelas_kuliahs.id_kelas_kuliah', $this->kelas)
        ->get();
        
        return $data_kelas;
    }
    /**
     * Define the headings for the Excel file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Kode Mata Kuliah',
            'Nama Mata Kuliah',
            'Nama Kelas Kuliah',
            'NIM',
            'Nama Mahasiswa',
            'Nilai Keaktifan Mahasiswa',
            'Nilai Proyek',
            'Nilai Tugas',
            'Nilai Kuis',
            'Nilai UTS',
            'Nilai UAS',
        ];
    }

    /**
     * Map data for each row.
     * @return array
     */
    public function map($data_kelas): array
    {
        return [
            $data_kelas->kode_mata_kuliah,
            $data_kelas->nama_mata_kuliah,
            $data_kelas->nama_kelas_kuliah,
            $data_kelas->nim,
            $data_kelas->nama_mahasiswa,
            $data_kelas->nilai_keaktifan_kelas,
            $data_kelas->nilai_projek,
            $data_kelas->nilai_tugas,
            $data_kelas->nilai_kuis,
            $data_kelas->nilai_uts,
            $data_kelas->nilai_uas,
        ];
    }

    /**
     * Register events to manipulate the sheet after it has been created.
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set specific cell values
                $sheet->setCellValue('A1', 'Kode Mata Kuliah');
                $sheet->setCellValue('B1', 'Nama Mata Kuliah');
                $sheet->setCellValue('C1', 'Nama Kelas Kuliah');
                $sheet->setCellValue('D1', 'NIM');
                $sheet->setCellValue('E1', 'Nama Mahasiswa');
                $sheet->setCellValue('F1', 'Nilai Keaktifan Mahasiswa');
                $sheet->setCellValue('G1', 'Nilai Proyek');
                $sheet->setCellValue('H1', 'Nilai Tugas');
                $sheet->setCellValue('I1', 'Nilai Kuis');
                $sheet->setCellValue('J1', 'Nilai UTS');
                $sheet->setCellValue('K1', 'Nilai UAS');

                // Apply styles to cells
                $sheet->getStyle('A1:K1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'FFFFE0B2'],
                    ],
                ]);

                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(25);
                $sheet->getColumnDimension('C')->setWidth(30);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(20);
                $sheet->getColumnDimension('F')->setWidth(20);
                $sheet->getColumnDimension('G')->setWidth(20);
                $sheet->getColumnDimension('H')->setWidth(20);
                $sheet->getColumnDimension('I')->setWidth(20);
                $sheet->getColumnDimension('J')->setWidth(20);
                $sheet->getColumnDimension('K')->setWidth(20);

                // Additional formatting can be done here...
            },
        ];
    }
}
