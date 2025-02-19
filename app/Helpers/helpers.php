<?php

if (!function_exists('getContrastColor')) {
    function getContrastColor($hexColor) {
        // Validasi format heksadesimal
        if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $hexColor)) {
            return '#000000'; // Default ke hitam jika format tidak valid
        }

        // Konversi hex ke RGB
        $r = hexdec(substr($hexColor, 1, 2));
        $g = hexdec(substr($hexColor, 3, 2));
        $b = hexdec(substr($hexColor, 5, 2));

        // Hitung kecerahan (brightness)
        $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

        // Jika kecerahan > 128, gunakan teks hitam; jika tidak, gunakan teks putih
        return ($brightness > 128) ? '#000000' : '#FFFFFF';
    }
}

if (!function_exists('darkenColor')) {
    function darkenColor($hexColor, $percent) {
        // Konversi hex ke RGB
        $r = hexdec(substr($hexColor, 1, 2));
        $g = hexdec(substr($hexColor, 3, 2));
        $b = hexdec(substr($hexColor, 5, 2));

        // Gelapkan warna
        $r = max(0, min(255, $r - ($r * $percent / 100)));
        $g = max(0, min(255, $g - ($g * $percent / 100)));
        $b = max(0, min(255, $b - ($b * $percent / 100)));

        // Kembalikan ke format hex
        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }
}