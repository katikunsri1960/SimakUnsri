<?php

namespace App\Http\Traits;

trait Terbilang
{
    /**
     * Mengubah angka menjadi teks dalam bahasa Indonesia.
     */
    private function penyebut(int|float $nilai): string
    {
        $nilai = abs((int) $nilai);
        $libs = [
            '', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam',
            'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas',
        ];

        if ($nilai < 12) {
            return ' '.$libs[$nilai];
        }

        if ($nilai < 20) {
            return $this->penyebut($nilai - 10).' belas';
        }

        if ($nilai < 100) {
            return $this->penyebut(intval($nilai / 10)).' puluh'.$this->penyebut($nilai % 10);
        }

        if ($nilai < 200) {
            return ' seratus'.$this->penyebut($nilai - 100);
        }

        if ($nilai < 1000) {
            return $this->penyebut(intval($nilai / 100)).' ratus'.$this->penyebut($nilai % 100);
        }

        if ($nilai < 2000) {
            return ' seribu'.$this->penyebut($nilai - 1000);
        }

        if ($nilai < 1_000_000) {
            return $this->penyebut(intval($nilai / 1000)).' ribu'.$this->penyebut($nilai % 1000);
        }

        if ($nilai < 1_000_000_000) {
            return $this->penyebut(intval($nilai / 1_000_000)).' juta'.$this->penyebut($nilai % 1_000_000);
        }

        if ($nilai < 1_000_000_000_000) {
            return $this->penyebut(intval($nilai / 1_000_000_000)).' milyar'.$this->penyebut(fmod($nilai, 1_000_000_000));
        }

        if ($nilai < 1_000_000_000_000_000) {
            return $this->penyebut(intval($nilai / 1_000_000_000_000)).' trilyun'.$this->penyebut(fmod($nilai, 1_000_000_000_000));
        }

        return '';
    }

    public function pembilang(int|float $nilai): string
    {
        $isNegative = $nilai < 0;
        $nilai = abs($nilai);

        $nilaiStr = strpos((string)$nilai, '.') === false
            ? (string)number_format($nilai, 2, '.', '')
            : (string)$nilai;

        $parts = explode('.', $nilaiStr);
        $integerPart = (int)$parts[0];
        $decimalPart = $parts[1] ?? null;

        $hasil = trim($this->penyebut($integerPart));

        if ($decimalPart !== null) {
            if (preg_match('/^0+$/', $decimalPart)) {
                $hasil .= ' koma nol';
            } else {
                $hasil .= ' koma';
                foreach (str_split($decimalPart) as $digit) {
                    $hasil .= ' '.$this->penyebut((int)$digit);
                }
            }
        }

        return $isNegative ? 'minus '.$hasil : $hasil;
    }
}
