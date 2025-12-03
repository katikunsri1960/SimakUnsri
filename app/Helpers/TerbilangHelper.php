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

        // Pecah menjadi integer dan decimal
        $parts = explode('.', number_format($ipk, 2, '.', ''));

        $integer = $angkaTerbilang[$parts[0]];

        $decimalDigits = str_split($parts[1]); // contoh: ["4","7"]

        $decimalTerbilang = array_map(function ($digit) use ($angkaTerbilang) {
            return $angkaTerbilang[$digit];
        }, $decimalDigits);

        return $integer . ' Koma ' . implode(' ', $decimalTerbilang);
    }
}


