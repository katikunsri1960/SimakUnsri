<?php

use Carbon\Carbon;

if (!function_exists('idDate')) {
    /**
     * Format tanggal Indonesia (KAPITAL)
     * Contoh: 26 MEI 2025
     */
    function idDate($date)
    {
        if (!$date) return '-';

        return strtoupper(
            Carbon::parse($date)->translatedFormat('j F Y')
        );
    }
}

if (!function_exists('enDate')) {
    /**
     * Format tanggal Inggris (KAPITAL + superscript)
     * Contoh: 26ᵗʰ MAY 2025
     */
    function enDate($date)
{
    if (!$date) return '-';

    $carbon = \Carbon\Carbon::parse($date);
    $day = $carbon->format('j');
    $month = $carbon->format('F');
    $year = $carbon->format('Y');

    // Ordinal suffix
    if ($day % 10 == 1 && $day != 11) {
        $suffix = 'st';
    } elseif ($day % 10 == 2 && $day != 12) {
        $suffix = 'nd';
    } elseif ($day % 10 == 3 && $day != 13) {
        $suffix = 'rd';
    } else {
        $suffix = 'th';
    }

    // Superscript kecil + italic
    $sup = '<sup style="font-size:60%;vertical-align:top;">' . $suffix . '</sup>';

    return $day . strtolower($sup) . ' ' . strtoupper($month) . ' ' . $year;
}


}
