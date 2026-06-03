<?php

namespace App\Exports;

use App\Models\SuratKeluar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SuratKeluarExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle
{
    protected $bulan;
    protected $tahun;

    public function __construct(int $bulan, int $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return SuratKeluar::with(['kategori', 'bagian', 'user'])
            ->whereMonth('tanggal_arsip', $this->bulan)
            ->whereYear('tanggal_arsip', $this->tahun)
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Surat',
            'Perihal',
            'Penerima',
            'Tanggal Surat',
            'Tanggal Arsip',
            'Sifat',
            'Kategori',
            'Bagian',
            'Diinput Oleh',
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $row->nomor_surat,
            $row->judul_surat,
            $row->penerima,
            $row->tanggal_surat->format('d/m/Y'),
            $row->tanggal_arsip->format('d/m/Y'),
            ucfirst($row->sifat),
            $row->kategori->nama_kategori ?? '-',
            $row->bagian->nama_bagian ?? '-',
            $row->user->name ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '1D4ED8']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    public function title(): string
    {
        return 'Surat Keluar';
    }
}