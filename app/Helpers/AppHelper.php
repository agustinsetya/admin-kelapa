<?php

if (!function_exists('formatRupiah')) {
    function formatRupiah($number, $withRp = true)
    {
        return ($withRp ? 'Rp. ' : '') . number_format($number, 0, ',', '.');
    }
}