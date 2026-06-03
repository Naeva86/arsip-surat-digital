<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 25px 35px;
            font-size: 13px;
            line-height: 1.5;
            color: #000;
        }
        .kop-surat { text-align: center; margin-bottom: 5px; }
        .kop-surat img { width: 100%; max-width: 700px; height: auto; }
        .kop-border { border-bottom: 3px double #000; margin: 0 0 20px 0; }
        .form-row { display: table; width: 100%; margin-bottom: 2px; }
        .form-label { display: table-cell; width: 170px; padding: 6px 0; vertical-align: top; font-size: 13px; }
        .form-colon { display: table-cell; width: 15px; text-align: center; padding: 6px 0; vertical-align: top; }
        .form-value { display: table-cell; padding: 6px 0 6px 5px; vertical-align: top; border-bottom: 1px solid #000; font-size: 13px; }
        
        /* Modifikasi tinggi minimal agar muat dengan barcode di tengah */
        .form-value-disposisi { display: table-cell; padding: 6px 5px; vertical-align: top; border-bottom: 1px solid #000; font-size: 13px; min-height: 100px; }
        
        .separator { border-top: 2px solid #000; margin: 12px 0; }
        .separator-thin { border-top: 1px solid #000; margin: 10px 0; clear: both; }
        
        /* Layout Barcode Center */
        .ttd-container {
            width: 100%;
            margin-top: 12px;
            text-align: center; /* Membuat isi container otomatis ke tengah */
            clear: both;
        }
        .qr-img { 
            width: 75px; 
            height: 75px; 
            display: inline-block;
            vertical-align: middle;
        }
        
        .footer { margin-top: 20px; font-size: 7px; color: #999; text-align: right; }
    </style>
</head>
<body>

    @php
        $surat = $disposisi->suratMasuk;
        $allDisposisis = $surat->disposisis->sortBy('created_at');

        // Level 1: Staff → Direktur (yang disetujui)
        $dispoLevel1 = $allDisposisis->where('level', 1)->where('keputusan', 'setuju')->first();

        // Level 2: Direktur → Kabag
        $dispoLevel2 = $allDisposisis->where('level', 2)->first();

        // Level 3: Kabag → Kasubag
        $dispoLevel3 = $allDisposisis->where('level', 3)->first();
    @endphp

    {{-- Kop Surat --}}
    <div class="kop-surat">
        <img src="{{ public_path('images/kop surat.png') }}" alt="Kop Surat">
    </div>
    <div class="kop-border"></div>

    {{-- Surat Dari --}}
    <div class="form-row">
        <div class="form-label">Surat dari</div>
        <div class="form-colon">:</div>
        <div class="form-value">{{ $surat->pengirim ?? '' }}</div>
    </div>

    {{-- Tanggal / Nomor --}}
    <div class="form-row">
        <div class="form-label">Tanggal/ Nomor</div>
        <div class="form-colon">:</div>
        <div class="form-value">{{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d - m - Y') : '' }} / {{ $surat->nomor_surat ?? '' }}</div>
    </div>

    {{-- Perihal --}}
    <div class="form-row">
        <div class="form-label">Perihal</div>
        <div class="form-colon">:</div>
        <div class="form-value">{{ $surat->judul_surat ?? '' }}</div>
    </div>

    <div class="separator"></div>

    {{-- Diterima Tanggal --}}
    <div class="form-row">
        <div class="form-label">Diterima Tanggal</div>
        <div class="form-colon">:</div>
        <div class="form-value">{{ $surat->tanggal_arsip ? $surat->tanggal_arsip->format('d - m - Y') : '' }}</div>
    </div>

    {{-- Diagendakan Nomor --}}
    <div class="form-row">
        <div class="form-label">Diagendakan Nomor</div>
        <div class="form-colon">:</div>
        <div class="form-value">{{ $surat->diagendakan_nomor ?? '' }}</div>
    </div>

    {{-- ═══════════════════════════════════ --}}
    {{-- Level 1 — Disposisi Direktur        --}}
    {{-- ═══════════════════════════════════ --}}

    <div class="form-row">
        <div class="form-label">Diteruskan</div>
        <div class="form-colon">:</div>
        <div class="form-value">
            @if($dispoLevel2 && $dispoLevel2->kepadaUser)
                {{ $dispoLevel2->kepadaUser->jabatan->nama_jabatan ?? '' }}
                {{ $dispoLevel2->kepadaUser->bagian->nama_bagian ?? '' }}
            @elseif($dispoLevel1 && $dispoLevel1->tujuanBagian)
                Kepala Bagian {{ $dispoLevel1->tujuanBagian->nama_bagian ?? '' }}
            @endif
        </div>
    </div>

    <div class="form-row">
        <div class="form-label">Disposisi</div>
        <div class="form-colon">:</div>
        <div class="form-value-disposisi">
            <div>
                @if($dispoLevel1)
                    {{ $dispoLevel1->instruksi_disposisi ?? '' }}
                @endif
            </div>
            
            {{-- Barcode Direktur Utama (Berada tepat di tengah kolom disposisi) --}}
            @if(isset($qrData['dari']))
            <div class="ttd-container">
                <img src="{{ $qrData['dari'] }}" class="qr-img" alt="QR">
            </div>
            @endif
        </div>
    </div>


    {{-- ═══════════════════════════════════ --}}
    {{-- Level 2 — Disposisi Kabag           --}}
    {{-- ═══════════════════════════════════ --}}
    <div class="separator-thin"></div>

    <div class="form-row">
        <div class="form-label">Diteruskan</div>
        <div class="form-colon">:</div>
        <div class="form-value">
            @if($dispoLevel3 && $dispoLevel3->kepadaUser)
                {{ $dispoLevel3->kepadaUser->jabatan->nama_jabatan ?? '' }}
                {{ $dispoLevel3->kepadaUser->bagian->nama_bagian ?? '' }}
            @elseif($dispoLevel2 && $dispoLevel2->status === 'selesai')
                {{ $dispoLevel2->kepadaUser->jabatan->nama_jabatan ?? 'Kabag' }}
            @endif
        </div>
    </div>

    <div class="form-row">
        <div class="form-label">Disposisi</div>
        <div class="form-colon">:</div>
        <div class="form-value-disposisi">
            <div>
                @if($dispoLevel2)
                    {{ $dispoLevel2->instruksi_disposisi ?? $dispoLevel2->isi_disposisi ?? '' }}
                @endif
            </div>
            
            {{-- Barcode Kabag (Berada tepat di tengah kolom disposisi) --}}
            @if(isset($qrData['kepada']))
            <div class="ttd-container">
                <img src="{{ $qrData['kepada'] }}" class="qr-img" alt="QR">
            </div>
            @endif
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        Dicetak pada {{ now()->format('d/m/Y H:i') }} — Sistem Arsip Digital PERUMDA Tirta Danu Arta
    </div>

</body>
</html>