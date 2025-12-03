<?php

if (! function_exists('terbilang_ipk')) {
    function terbilang_ipk($ipk)
    {
        $angkaTerbilang = [
            '0' => 'Nol',
            '1' => 'Satu',
            '2' => 'Dua',
            '3' => 'Tiga',
            '4' => 'Empat',
            '5' => 'Lima',
            '6' => 'Enam',
            '7' => 'Tujuh',
            '8' => 'Delapan',
            '9' => 'Sembilan'
        ];

        $parts = explode('.', number_format($ipk, 2, '.', ''));
        $integer = $angkaTerbilang[$parts[0]];
        $decimalDigits = str_split($parts[1]);

        $decimalTerbilang = array_map(function ($digit) use ($angkaTerbilang) {
            return $angkaTerbilang[$digit];
        }, $decimalDigits);

        return $integer . ' Koma ' . implode(' ', $decimalTerbilang);
    }
}

if (! function_exists('terbilang_ipk_en')) {
    function terbilang_ipk_en($ipk)
    {
        $angkaTerbilangEn = [
            '0' => 'Zero',
            '1' => 'One',
            '2' => 'Two',
            '3' => 'Three',
            '4' => 'Four',
            '5' => 'Five',
            '6' => 'Six',
            '7' => 'Seven',
            '8' => 'Eight',
            '9' => 'Nine'
        ];

        // Format agar selalu 2 digit di belakang koma
        $parts = explode('.', number_format($ipk, 2, '.', ''));

        $integer = $angkaTerbilangEn[$parts[0]];
        $decimalDigits = str_split($parts[1]); // Contoh: ["4","7"]

        $decimalTerbilang = array_map(function ($digit) use ($angkaTerbilangEn) {
            return $angkaTerbilangEn[$digit];
        }, $decimalDigits);

        return $integer . ' Point ' . implode(' ', $decimalTerbilang);
    }
}
