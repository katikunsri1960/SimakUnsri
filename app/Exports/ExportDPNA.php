<?php

namespace App\Exports;

use App\Models\Perkuliahan\KelasKuliah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportDPNA implements FromCollection, WithHeadings, WithEvents, WithMapping
{
    public function __construct(string $kelas)
    {
        $this->kelas = $kelas;
    }  
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        $data_kelas = KelasKuliah::LeftJoin('peserta_kelas_kuliahs', 'peserta_kelas_kuliahs.id_kelas_kuliah', 'kelas_kuliahs.id_kelas_kuliah')
                    ->LeftJoin('nilai_perkuliahans', 'nilai_perkulians.id_kelas_kuliah', 'kelas_kuliahs.id_kelas_kuliah')
                    ->where('kelas_kuliahs.id_kelas_kuliah', $kelas);
        
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
            'ID',
            'Name',
            'Email',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * Map data for each row.
     * @return array
     */
    public function map($data_kelas): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->created_at->format('Y-m-d H:i:s'),
            $user->updated_at->format('Y-m-d H:i:s'),
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
                $sheet->setCellValue('A1', 'User ID');
                $sheet->setCellValue('B1', 'Full Name');
                $sheet->setCellValue('C1', 'Email Address');
                $sheet->setCellValue('D1', 'Date Created');
                $sheet->setCellValue('E1', 'Date Updated');

                // Apply styles to cells
                $sheet->getStyle('A1:E1')->applyFromArray([
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

                // Additional formatting can be done here...
            },
        ];
    }
}
