<?php

if (!function_exists('formatJam')) {
    /**
     * Format menit menjadi format jam dan menit
     * Contoh: 90 menit menjadi "1 jam 30 menit"
     *
     * @param int $menit Jumlah menit
     * @return string Format jam dan menit
     */
    function formatJam($menit)
    {
        if (!is_numeric($menit) || $menit < 0) {
            return '0 menit';
        }

        $jam = intdiv($menit, 60);
        $sisa_menit = $menit % 60;

        if ($jam === 0) {
            return $sisa_menit . ' menit';
        } elseif ($sisa_menit === 0) {
            return $jam . ' jam';
        } else {
            return $jam . ' jam ' . $sisa_menit . ' menit';
        }
    }
}
