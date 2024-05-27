<?php

namespace App\Exports;

use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\KomponenEvaluasiKelas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Facades\DB;

class ExportDPNA implements FromCollection, WithHeadings, WithEvents, WithMapping
{
    use RegistersEventListeners;
    
    public function __construct(string $kelas)
    {
        $this->kelas = $kelas;

        // Get bobot komponen evaluasi kelas
        $this->bobot_participatory = KomponenEvaluasiKelas::where('id_kelas_kuliah', $kelas)
            ->where('id_jenis_evaluasi', 2)
            ->first()->bobot_evaluasi;
        
        $this->bobot_project = KomponenEvaluasiKelas::where('id_kelas_kuliah', $kelas)
            ->where('id_jenis_evaluasi', 3)
            ->first()->bobot_evaluasi;
        
        $this->bobot_assignment = KomponenEvaluasiKelas::where('id_kelas_kuliah', $kelas)
            ->where('id_jenis_evaluasi', 4)
            ->where('nomor_urut', 3)
            ->first()->bobot_evaluasi;
        
        $this->bobot_quiz = KomponenEvaluasiKelas::where('id_kelas_kuliah', $kelas)
            ->where('id_jenis_evaluasi', 4)
            ->where('nomor_urut', 4)
            ->first()->bobot_evaluasi;
        
        $this->bobot_midterm = KomponenEvaluasiKelas::where('id_kelas_kuliah', $kelas)
            ->where('id_jenis_evaluasi', 4)
            ->where('nomor_urut', 5)
            ->first()->bobot_evaluasi;
        
        $this->bobot_finalterm = KomponenEvaluasiKelas::where('id_kelas_kuliah', $kelas)
            ->where('id_jenis_evaluasi', 4)
            ->where('nomor_urut', 6)
            ->first()->bobot_evaluasi;
    }  
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //Get kelas information (kelas, peserta kelas, nilai komponen evaluasi)
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
                        AND nilai_komponen_evaluasis.urutan = '3') AS nilai_tugas"),
            DB::raw("(SELECT nilai_komponen_evaluasis.nilai_komp_eval 
                      FROM nilai_komponen_evaluasis 
                      WHERE nilai_komponen_evaluasis.id_registrasi_mahasiswa = peserta_kelas_kuliahs.id_registrasi_mahasiswa 
                        AND nilai_komponen_evaluasis.id_kelas = kelas_kuliahs.id_kelas_kuliah 
                        AND nilai_komponen_evaluasis.id_jns_eval = '4' 
                        AND nilai_komponen_evaluasis.urutan = '4') AS nilai_kuis"),
            DB::raw("(SELECT nilai_komponen_evaluasis.nilai_komp_eval 
                      FROM nilai_komponen_evaluasis 
                      WHERE nilai_komponen_evaluasis.id_registrasi_mahasiswa = peserta_kelas_kuliahs.id_registrasi_mahasiswa 
                        AND nilai_komponen_evaluasis.id_kelas = kelas_kuliahs.id_kelas_kuliah 
                        AND nilai_komponen_evaluasis.id_jns_eval = '4' 
                        AND nilai_komponen_evaluasis.urutan = '5') AS nilai_uts"),
            DB::raw("(SELECT nilai_komponen_evaluasis.nilai_komp_eval 
                      FROM nilai_komponen_evaluasis 
                      WHERE nilai_komponen_evaluasis.id_registrasi_mahasiswa = peserta_kelas_kuliahs.id_registrasi_mahasiswa 
                        AND nilai_komponen_evaluasis.id_kelas = kelas_kuliahs.id_kelas_kuliah 
                        AND nilai_komponen_evaluasis.id_jns_eval = '4' 
                        AND nilai_komponen_evaluasis.urutan = '6') AS nilai_uas"),
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
            'Nilai Angka'
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $this->afterSheet($event);
            },
        ];
    }

    /**
     * Register events to manipulate the sheet after it has been created.
     *
     * @return array
     */
    private function afterSheet(AfterSheet $event)
    {
        $sheet = $event->sheet->getDelegate();
        $highestRow = $sheet->getHighestRow();

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
        $sheet->setCellValue('L1', 'Nilai Angka');

        // Apply styles to header cells
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'FFFFE0B2'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ]);

        // Apply borders and alignment to all data cells
        $sheet->getStyle('A2:L' . $highestRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ]);

        // Add formula in the column (L) for each row
        for ($row = 2; $row <= $highestRow; $row++) {
            $cell = 'L' . $row;
            $formula = "=F{$row}*{$this->bobot_participatory} + G{$row}*{$this->bobot_project} + H{$row}*{$this->bobot_assignment} + I{$row}*{$this->bobot_quiz} + J{$row}*{$this->bobot_midterm} + K{$row}*{$this->bobot_finalterm}";

            $sheet->setCellValue($cell, $formula);
        }

        // Lock specific columns
        $columnsToLock = ['A', 'B', 'C', 'D', 'E', 'L'];
        foreach ($columnsToLock as $column) {
            $sheet->getStyle($column . '1:' . $column . $highestRow)
                ->getProtection()
                ->setLocked(Protection::PROTECTION_PROTECTED);
        }

        // Ensure other cells are unlocked before applying sheet protection
        $sheet->getStyle('F2:K'.$highestRow)
              ->getProtection()
              ->setLocked(Protection::PROTECTION_UNPROTECTED);

        // Enable sheet protection but allow other cells to be editable
        $sheet->getProtection()->setSheet(true);
        $sheet->getProtection()->setPassword('password'); // Optional: Set a password for sheet protection

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(32);
        $sheet->getColumnDimension('F')->setWidth(22);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(15);
        $sheet->getColumnDimension('L')->setWidth(15);
    }
}
