<?php

namespace App\Exports;

use App\Models\SuratMasuk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SuratMasukExport implements
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
        return SuratMasuk::with(['kategori', 'user'])
            ->whereMonth('tanggal_arsip', $this->bulan)
            ->whereYear('tanggal_arsip', $this->tahun)
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'No Agenda',
            'Nomor Surat',
            'Perihal',
            'Pengirim',
            'Tanggal Surat',
            'Tanggal Arsip',
            'Sifat',
            'Kategori',
            'Status',
            'Diinput Oleh',
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $row->no_agenda,
            $row->nomor_surat,
            $row->judul_surat,
            $row->pengirim,
            $row->tanggal_surat->format('d/m/Y'),
            $row->tanggal_arsip->format('d/m/Y'),
            ucfirst($row->sifat),
            $row->kategori->nama_kategori ?? '-',
            $row->status_label,
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
        return 'Surat Masuk';
    }
}