<?php

use Carbon\Carbon;

function rupiah($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.') .',00';
}

function tanggal($date)
{
    return Carbon::parse($date)->format('d-m-Y');
}

function tanggal_waktu($datetime)
{
    return Carbon::parse($datetime)->format('d-m-Y H:i:s');
}