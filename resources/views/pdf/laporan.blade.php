<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1f2937; }

        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1D4ED8; padding-bottom: 12px; }
        .header h1 { font-size: 16px; font-weight: bold; color: #1D4ED8; }
        .header p { font-size: 11px; color: #6b7280; margin-top: 3px; }

        .periode { text-align: center; margin-bottom: 16px; }
        .periode span { background: #EFF6FF; color: #1D4ED8; padding: 3px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; }

        .section-title { font-size: 12px; font-weight: bold; color: #1f2937; margin: 16px 0 8px; padding: 5px 10px; background: #F3F4F6; border-left: 3px solid #1D4ED8; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        th { background: #1D4ED8; color: white; padding: 6px 8px; text-align: left; font-size: 10px; }
        td { padding: 5px 8px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        tr:nth-child(even) td { background: #F9FAFB; }

        .badge { padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-baru     { background: #DBEAFE; color: #1D4ED8; }
        .badge-proses   { background: #FEF9C3; color: #92400E; }
        .badge-selesai  { background: #DCFCE7; color: #166534; }
        .badge-urgent   { background: #FEE2E2; color: #991B1B; }
        .badge-penting  { background: #FEF9C3; color: #92400E; }
        .badge-rahasia  { background: #F3E8FF; color: #6B21A8; }
        .badge-biasa    { background: #F3F4F6; color: #374151; }

        .summary { display: flex; gap: 10px; margin-bottom: 16px; }
        .summary-box { flex: 1; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 12px; text-align: center; }
        .summary-box .num { font-size: 20px; font-weight: bold; color: #1D4ED8; }
        .summary-box .label { font-size: 9px; color: #6b7280; margin-top: 2px; }

        .footer { margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 10px; text-align: right; font-size: 9px; color: #9ca3af; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <h1>PERUMDA Air Minum Tirta Danu Arta</h1>
        <p>Laporan Arsip Surat — Kabupaten Bangli</p>
    </div>

    {{-- Periode --}}
    <div class="periode">
        <span>Periode: {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}</span>
    </div>

    {{-- Summary --}}
    <table style="margin-bottom: 16px;">
        <tr>
            <td style="width:25%; text-align:center; border:1px solid #e5e7eb; padding:10px; border-radius:4px;">
                <div style="font-size:22px; font-weight:bold; color:#1D4ED8;">{{ $suratMasuks->count() }}</div>
                <div style="font-size:9px; color:#6b7280;">Total Surat Masuk</div>
            </td>
            <td style="width:25%; text-align:center; border:1px solid #e5e7eb; padding:10px;">
                <div style="font-size:22px; font-weight:bold; color:#16A34A;">{{ $suratKeluars->count() }}</div>
                <div style="font-size:9px; color:#6b7280;">Total Surat Keluar</div>
            </td>
            <td style="width:25%; text-align:center; border:1px solid #e5e7eb; padding:10px;">
                <div style="font-size:22px; font-weight:bold; color:#CA8A04;">{{ $suratMasuks->where('status','proses_disposisi')->count() }}</div>
                <div style="font-size:9px; color:#6b7280;">Proses Disposisi</div>
            </td>
            <td style="width:25%; text-align:center; border:1px solid #e5e7eb; padding:10px;">
                <div style="font-size:22px; font-weight:bold; color:#7C3AED;">{{ $suratMasuks->where('status','selesai')->count() }}</div>
                <div style="font-size:9px; color:#6b7280;">Surat Selesai</div>
            </td>
        </tr>
    </table>

    {{-- Surat Masuk --}}
    <div class="section-title">SURAT MASUK</div>
    @if($suratMasuks->isEmpty())
        <p style="color:#9ca3af; font-size:10px; padding:8px;">Tidak ada data surat masuk pada periode ini.</p>
    @else
    <table>
        <thead>
            <tr>
                <th style="width:4%">No</th>
                <th style="width:12%">No Agenda</th>
                <th style="width:12%">No Surat</th>
                <th style="width:25%">Perihal</th>
                <th style="width:15%">Pengirim</th>
                <th style="width:10%">Tgl Surat</th>
                <th style="width:8%">Sifat</th>
                <th style="width:10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suratMasuks as $i => $surat)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="font-family:monospace; font-size:9px;">{{ $surat->no_agenda }}</td>
                <td style="font-size:9px;">{{ $surat->nomor_surat }}</td>
                <td>{{ Str::limit($surat->judul_surat, 40) }}</td>
                <td>{{ $surat->pengirim }}</td>
                <td>{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                <td>
                    <span class="badge badge-{{ $surat->sifat }}">{{ ucfirst($surat->sifat) }}</span>
                </td>
                <td>
                    @php
                        $sc = match($surat->status) {
                            'baru'             => 'badge-baru',
                            'proses_disposisi' => 'badge-proses',
                            'selesai'          => 'badge-selesai',
                            default            => '',
                        };
                    @endphp
                    <span class="badge {{ $sc }}">{{ $surat->status_label }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- Surat Keluar --}}
    <div class="section-title">SURAT KELUAR</div>
    @if($suratKeluars->isEmpty())
        <p style="color:#9ca3af; font-size:10px; padding:8px;">Tidak ada data surat keluar pada periode ini.</p>
    @else
    <table>
        <thead>
            <tr>
                <th style="width:4%">No</th>
                <th style="width:18%">No Surat</th>
                <th style="width:28%">Perihal</th>
                <th style="width:18%">Penerima</th>
                <th style="width:10%">Tgl Surat</th>
                <th style="width:10%">Bagian</th>
                <th style="width:8%">Sifat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suratKeluars as $i => $surat)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="font-size:9px;">{{ $surat->nomor_surat }}</td>
                <td>{{ Str::limit($surat->judul_surat, 40) }}</td>
                <td>{{ $surat->penerima }}</td>
                <td>{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                <td>{{ $surat->bagian->nama_bagian ?? '-' }}</td>
                <td>
                    <span class="badge badge-{{ $surat->sifat }}">{{ ucfirst($surat->sifat) }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- Footer --}}
    <div class="footer">
        Dicetak pada {{ now()->format('d/m/Y H:i') }} oleh {{ auth()->user()->name }}
    </div>

</body>
</html>