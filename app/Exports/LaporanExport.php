<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanExport implements WithMultipleSheets
{
    protected $bulan;
    protected $tahun;

    public function __construct(int $bulan, int $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function sheets(): array
    {
        return [
            new SuratMasukExport($this->bulan, $this->tahun),
            new SuratKeluarExport($this->bulan, $this->tahun),
        ];
    }
}