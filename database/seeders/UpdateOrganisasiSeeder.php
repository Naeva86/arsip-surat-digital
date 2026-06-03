<?php

namespace Database\Seeders;

use App\Models\Bagian;
use App\Models\Jabatan;
use App\Models\KategoriSurat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UpdateOrganisasiSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        // ══════════════════════════════════════
        // JABATAN
        // ══════════════════════════════════════
        $this->command->info('📌 Menyiapkan jabatan...');

        $jabatans = [
            ['nama_jabatan' => 'Direktur Utama',    'level' => 1],
            ['nama_jabatan' => 'Kepala Bagian',     'level' => 2],
            ['nama_jabatan' => 'Kepala Sub Bagian', 'level' => 3],
            ['nama_jabatan' => 'Staff',             'level' => 4],
        ];

        foreach ($jabatans as $j) {
            Jabatan::firstOrCreate(
                ['nama_jabatan' => $j['nama_jabatan']],
                ['level' => $j['level']]
            );
        }

        $jabDirektur = Jabatan::where('nama_jabatan', 'Direktur Utama')->first();
        $jabKabag    = Jabatan::where('nama_jabatan', 'Kepala Bagian')->first();
        $jabKasubag  = Jabatan::where('nama_jabatan', 'Kepala Sub Bagian')->first();
        $jabStaff    = Jabatan::where('nama_jabatan', 'Staff')->first();

        // ══════════════════════════════════════
        // BERSIHKAN DATA LAMA
        // ══════════════════════════════════════
        $this->command->info('🗑️  Membersihkan data kabag/kasubag lama...');

        // Matikan foreign key check sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Force delete semua kabag & kasubag (termasuk soft deleted)
        User::withTrashed()->whereIn('role', ['kabag', 'kasubbag'])->forceDelete();

        // Hapus semua sub bagian (child)
        Bagian::whereNotNull('parent_id')->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // ══════════════════════════════════════
        // BAGIAN PARENT (3 Kabag)
        // ══════════════════════════════════════
        $this->command->info('🏢 Membuat struktur bagian baru...');

        $bagAdminKeu = Bagian::updateOrCreate(
            ['nama_bagian' => 'Bagian Administrasi & Keuangan'],
            ['parent_id' => null, 'urutan' => 1]
        );

        $bagHubPel = Bagian::updateOrCreate(
            ['nama_bagian' => 'Bagian Hubungan Pelanggan'],
            ['parent_id' => null, 'urutan' => 2]
        );

        $bagTeknik = Bagian::updateOrCreate(
            ['nama_bagian' => 'Bagian Teknik'],
            ['parent_id' => null, 'urutan' => 3]
        );

        // ── Sub Bagian: Administrasi & Keuangan (3 sub) ──
        $subLogistik = Bagian::create([
            'nama_bagian' => 'Sub Bagian Logistik',
            'parent_id'   => $bagAdminKeu->id,
            'urutan'      => 1,
        ]);

        $subAdmUmum = Bagian::create([
            'nama_bagian' => 'Sub Bagian Administrasi Umum & Personalia',
            'parent_id'   => $bagAdminKeu->id,
            'urutan'      => 2,
        ]);

        $subKeuangan = Bagian::create([
            'nama_bagian' => 'Sub Bagian Keuangan',
            'parent_id'   => $bagAdminKeu->id,
            'urutan'      => 3,
        ]);

        // ── Sub Bagian: Hubungan Pelanggan (1 sub) ──
        $subHumas = Bagian::create([
            'nama_bagian' => 'Sub Bagian Humas',
            'parent_id'   => $bagHubPel->id,
            'urutan'      => 1,
        ]);

        // ── Sub Bagian: Teknik (3 sub) ──
        $subProduksi = Bagian::create([
            'nama_bagian' => 'Sub Bagian Produksi',
            'parent_id'   => $bagTeknik->id,
            'urutan'      => 1,
        ]);

        $subTransDist = Bagian::create([
            'nama_bagian' => 'Sub Bagian Transportasi & Distribusi',
            'parent_id'   => $bagTeknik->id,
            'urutan'      => 2,
        ]);

        $subPerencana = Bagian::create([
            'nama_bagian' => 'Sub Bagian Perencanaan',
            'parent_id'   => $bagTeknik->id,
            'urutan'      => 3,
        ]);

        // ══════════════════════════════════════
        // USER: Admin, Staff, Direktur
        // ══════════════════════════════════════
        $this->command->info('👤 Menyiapkan user Admin, Staff, Direktur...');

        User::withTrashed()->updateOrCreate(
            ['email' => 'admin@perumda.local'],
            [
                'name'       => 'Administrator',
                'nip'        => '000000000',
                'role'       => 'admin',
                'jabatan_id' => $jabStaff->id,
                'bagian_id'  => $bagAdminKeu->id,
                'password'   => $password,
                'is_active'  => true,
                'deleted_at' => null,
            ]
        );

        User::withTrashed()->updateOrCreate(
            ['email' => 'staff@perumda.local'],
            [
                'name'       => 'Staff Arsip',
                'nip'        => '100000001',
                'role'       => 'staff',
                'jabatan_id' => $jabStaff->id,
                'bagian_id'  => $bagAdminKeu->id,
                'password'   => $password,
                'is_active'  => true,
                'deleted_at' => null,
            ]
        );

        User::withTrashed()->updateOrCreate(
            ['email' => 'direktur@perumda.local'],
            [
                'name'       => 'I Wayan Sudirta',
                'nip'        => '200000001',
                'role'       => 'direktur',
                'jabatan_id' => $jabDirektur->id,
                'bagian_id'  => $bagAdminKeu->id,
                'password'   => $password,
                'is_active'  => true,
                'deleted_at' => null,
            ]
        );

        // ══════════════════════════════════════
        // USER: 3 Kabag (baru)
        // ══════════════════════════════════════
        $this->command->info('👤 Membuat 3 Kabag...');

        User::create([
            'name'       => 'Ni Made Ayu Dewi',
            'email'      => 'kabag.adminkeu@perumda.local',
            'nip'        => '300000001',
            'role'       => 'kabag',
            'jabatan_id' => $jabKabag->id,
            'bagian_id'  => $bagAdminKeu->id,
            'password'   => $password,
            'is_active'  => true,
        ]);

        User::create([
            'name'       => 'I Nyoman Suardika',
            'email'      => 'kabag.hubpel@perumda.local',
            'nip'        => '300000002',
            'role'       => 'kabag',
            'jabatan_id' => $jabKabag->id,
            'bagian_id'  => $bagHubPel->id,
            'password'   => $password,
            'is_active'  => true,
        ]);

        User::create([
            'name'       => 'I Ketut Bagiarta',
            'email'      => 'kabag.teknik@perumda.local',
            'nip'        => '300000003',
            'role'       => 'kabag',
            'jabatan_id' => $jabKabag->id,
            'bagian_id'  => $bagTeknik->id,
            'password'   => $password,
            'is_active'  => true,
        ]);

        // ══════════════════════════════════════
        // USER: 7 Kasubag (baru)
        // ══════════════════════════════════════
        $this->command->info('👤 Membuat 7 Kasubag...');

        // ── Di bawah Kabag Administrasi & Keuangan ──
        User::create([
            'name'       => 'I Made Wirawan',
            'email'      => 'kasubag.logistik@perumda.local',
            'nip'        => '400000001',
            'role'       => 'kasubbag',
            'jabatan_id' => $jabKasubag->id,
            'bagian_id'  => $subLogistik->id,
            'password'   => $password,
            'is_active'  => true,
        ]);

        User::create([
            'name'       => 'Ni Luh Putu Sari',
            'email'      => 'kasubag.admumum@perumda.local',
            'nip'        => '400000002',
            'role'       => 'kasubbag',
            'jabatan_id' => $jabKasubag->id,
            'bagian_id'  => $subAdmUmum->id,
            'password'   => $password,
            'is_active'  => true,
        ]);

        User::create([
            'name'       => 'I Gede Mahendra',
            'email'      => 'kasubag.keuangan@perumda.local',
            'nip'        => '400000003',
            'role'       => 'kasubbag',
            'jabatan_id' => $jabKasubag->id,
            'bagian_id'  => $subKeuangan->id,
            'password'   => $password,
            'is_active'  => true,
        ]);

        // ── Di bawah Kabag Hubungan Pelanggan ──
        User::create([
            'name'       => 'Ni Komang Ayu Ratih',
            'email'      => 'kasubag.humas@perumda.local',
            'nip'        => '400000004',
            'role'       => 'kasubbag',
            'jabatan_id' => $jabKasubag->id,
            'bagian_id'  => $subHumas->id,
            'password'   => $password,
            'is_active'  => true,
        ]);

        // ── Di bawah Kabag Teknik ──
        User::create([
            'name'       => 'I Wayan Sudiarta',
            'email'      => 'kasubag.produksi@perumda.local',
            'nip'        => '400000005',
            'role'       => 'kasubbag',
            'jabatan_id' => $jabKasubag->id,
            'bagian_id'  => $subProduksi->id,
            'password'   => $password,
            'is_active'  => true,
        ]);

        User::create([
            'name'       => 'I Ketut Artawan',
            'email'      => 'kasubag.distribusi@perumda.local',
            'nip'        => '400000006',
            'role'       => 'kasubbag',
            'jabatan_id' => $jabKasubag->id,
            'bagian_id'  => $subTransDist->id,
            'password'   => $password,
            'is_active'  => true,
        ]);

        User::create([
            'name'       => 'I Nyoman Darma',
            'email'      => 'kasubag.perencanaan@perumda.local',
            'nip'        => '400000007',
            'role'       => 'kasubbag',
            'jabatan_id' => $jabKasubag->id,
            'bagian_id'  => $subPerencana->id,
            'password'   => $password,
            'is_active'  => true,
        ]);

        // ══════════════════════════════════════
        // KATEGORI SURAT
        // ══════════════════════════════════════
        $this->command->info('📁 Membuat kategori surat...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        KategoriSurat::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $kategoris = [
            'Surat Keputusan',
            'Surat Edaran',
            'Surat Undangan',
            'Surat Pemberitahuan',
            'Surat Permohonan',
            'Surat Perintah',
            'Surat Tugas',
            'Surat Keterangan',
            'Surat Pengantar',
            'Surat Rekomendasi',
            'Nota Dinas',
            'Memorandum',
            'Disposisi Internal',
            'Surat Pelanggan / Pengaduan',
            'Surat Tagihan / Piutang',
            'Surat Perjanjian / Kontrak',
            'Surat Izin / Cuti',
            'Berita Acara',
            'Laporan',
            'Surat Lainnya',
        ];

        foreach ($kategoris as $k) {
            KategoriSurat::create(['nama_kategori' => $k]);
        }

        // ══════════════════════════════════════
        // OUTPUT
        // ══════════════════════════════════════
        $this->command->newLine();
        $this->command->info('✅ Semua data berhasil diperbarui!');
        $this->command->newLine();

        $this->command->info('📋 STRUKTUR ORGANISASI:');
        $this->command->table(
            ['Bagian', 'Sub Bagian'],
            [
                ['Bag. Administrasi & Keuangan', 'Sub Bag. Logistik'],
                ['',                              'Sub Bag. Administrasi Umum & Personalia'],
                ['',                              'Sub Bag. Keuangan'],
                ['Bag. Hubungan Pelanggan',       'Sub Bag. Humas'],
                ['Bag. Teknik',                   'Sub Bag. Produksi'],
                ['',                              'Sub Bag. Transportasi & Distribusi'],
                ['',                              'Sub Bag. Perencanaan'],
            ]
        );

        $this->command->newLine();
        $this->command->info('👤 AKUN LOGIN (semua password: password):');
        $this->command->table(
            ['Role', 'Email', 'Bagian'],
            [
                ['Admin',    'admin@perumda.local',              '-'],
                ['Staff',    'staff@perumda.local',              '-'],
                ['Direktur', 'direktur@perumda.local',           '-'],
                ['Kabag',    'kabag.adminkeu@perumda.local',     'Administrasi & Keuangan'],
                ['Kabag',    'kabag.hubpel@perumda.local',       'Hubungan Pelanggan'],
                ['Kabag',    'kabag.teknik@perumda.local',       'Teknik'],
                ['Kasubag',  'kasubag.logistik@perumda.local',   'Logistik'],
                ['Kasubag',  'kasubag.admumum@perumda.local',    'Adm Umum & Personalia'],
                ['Kasubag',  'kasubag.keuangan@perumda.local',   'Keuangan'],
                ['Kasubag',  'kasubag.humas@perumda.local',      'Humas'],
                ['Kasubag',  'kasubag.produksi@perumda.local',   'Produksi'],
                ['Kasubag',  'kasubag.distribusi@perumda.local', 'Transportasi & Distribusi'],
                ['Kasubag',  'kasubag.perencanaan@perumda.local','Perencanaan'],
            ]
        );

        $this->command->newLine();
        $this->command->info('📁 KATEGORI SURAT (' . count($kategoris) . ' kategori):');
        $this->command->table(
            ['No', 'Kategori'],
            collect($kategoris)->map(fn($k, $i) => [$i + 1, $k])->toArray()
        );
    }
}