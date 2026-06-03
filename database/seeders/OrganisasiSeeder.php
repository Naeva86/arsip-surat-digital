<?php

namespace Database\Seeders;

use App\Models\Bagian;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OrganisasiSeeder extends Seeder
{
    public function run(): void
    {
        // ══════════════════════════════════════════
        // JABATAN (jika belum ada)
        // ══════════════════════════════════════════
        $jabatans = [
            ['nama_jabatan' => 'Direktur Utama',          'level' => 1],
            ['nama_jabatan' => 'Kepala Bagian',            'level' => 2],
            ['nama_jabatan' => 'Kepala Sub Bagian',        'level' => 3],
            ['nama_jabatan' => 'Staff',                    'level' => 4],
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

        // ══════════════════════════════════════════
        // BAGIAN (parent = Kabag, child = Kasubag)
        // ══════════════════════════════════════════

        // 3 Kabag (parent)
        $bagAdminKeu = Bagian::firstOrCreate(
            ['nama_bagian' => 'Bagian Administrasi & Keuangan'],
            ['parent_id' => null, 'urutan' => 1]
        );
        $bagTeknik = Bagian::firstOrCreate(
            ['nama_bagian' => 'Bagian Teknik'],
            ['parent_id' => null, 'urutan' => 2]
        );
        $bagHubPel = Bagian::firstOrCreate(
            ['nama_bagian' => 'Bagian Hubungan Pelanggan'],
            ['parent_id' => null, 'urutan' => 3]
        );

        // Sub bagian di bawah Administrasi & Keuangan
        $subKeuangan = Bagian::firstOrCreate(
            ['nama_bagian' => 'Sub Bagian Keuangan'],
            ['parent_id' => $bagAdminKeu->id, 'urutan' => 1]
        );
        $subUmum = Bagian::firstOrCreate(
            ['nama_bagian' => 'Sub Bagian Umum & Kepegawaian'],
            ['parent_id' => $bagAdminKeu->id, 'urutan' => 2]
        );

        // Sub bagian di bawah Teknik
        $subProduksi = Bagian::firstOrCreate(
            ['nama_bagian' => 'Sub Bagian Produksi'],
            ['parent_id' => $bagTeknik->id, 'urutan' => 1]
        );
        $subDistribusi = Bagian::firstOrCreate(
            ['nama_bagian' => 'Sub Bagian Distribusi/Perencanaan'],
            ['parent_id' => $bagTeknik->id, 'urutan' => 2]
        );

        // Sub bagian di bawah Hubungan Pelanggan
        $subPelayanan = Bagian::firstOrCreate(
            ['nama_bagian' => 'Sub Bagian Pelayanan Pelanggan'],
            ['parent_id' => $bagHubPel->id, 'urutan' => 1]
        );
        $subHumas = Bagian::firstOrCreate(
            ['nama_bagian' => 'Sub Bagian Humas/Pembacaan Meter'],
            ['parent_id' => $bagHubPel->id, 'urutan' => 2]
        );

        // ══════════════════════════════════════════
        // USER
        // ══════════════════════════════════════════
        $password = Hash::make('password');

        // Admin (jika belum ada)
        User::firstOrCreate(
            ['email' => 'admin@perumda.local'],
            [
                'name'       => 'Administrator',
                'nip'        => '000000000',
                'role'       => 'admin',
                'jabatan_id' => $jabStaff->id,
                'bagian_id'  => $bagAdminKeu->id,
                'password'   => $password,
                'is_active'  => true,
            ]
        );

        // Staff
        User::firstOrCreate(
            ['email' => 'staff@perumda.local'],
            [
                'name'       => 'Staff Arsip',
                'nip'        => '100000001',
                'role'       => 'staff',
                'jabatan_id' => $jabStaff->id,
                'bagian_id'  => $bagAdminKeu->id,
                'password'   => $password,
                'is_active'  => true,
            ]
        );

        // Direktur
        User::firstOrCreate(
            ['email' => 'direktur@perumda.local'],
            [
                'name'       => 'I Wayan Sudirta',
                'nip'        => '200000001',
                'role'       => 'direktur',
                'jabatan_id' => $jabDirektur->id,
                'bagian_id'  => $bagAdminKeu->id,
                'password'   => $password,
                'is_active'  => true,
            ]
        );

        // ── 3 Kabag ──

        User::firstOrCreate(
            ['email' => 'kabag.adminkeu@perumda.local'],
            [
                'name'       => 'Ni Made Ayu Dewi',
                'nip'        => '300000001',
                'role'       => 'kabag',
                'jabatan_id' => $jabKabag->id,
                'bagian_id'  => $bagAdminKeu->id,
                'password'   => $password,
                'is_active'  => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'kabag.teknik@perumda.local'],
            [
                'name'       => 'I Ketut Bagiarta',
                'nip'        => '300000002',
                'role'       => 'kabag',
                'jabatan_id' => $jabKabag->id,
                'bagian_id'  => $bagTeknik->id,
                'password'   => $password,
                'is_active'  => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'kabag.hubpel@perumda.local'],
            [
                'name'       => 'I Nyoman Suardika',
                'nip'        => '300000003',
                'role'       => 'kabag',
                'jabatan_id' => $jabKabag->id,
                'bagian_id'  => $bagHubPel->id,
                'password'   => $password,
                'is_active'  => true,
            ]
        );

        // ── 6 Kasubag ──

        // Di bawah Kabag Administrasi & Keuangan
        User::firstOrCreate(
            ['email' => 'kasubag.keuangan@perumda.local'],
            [
                'name'       => 'Ni Luh Putu Sari',
                'nip'        => '400000001',
                'role'       => 'kasubbag',
                'jabatan_id' => $jabKasubag->id,
                'bagian_id'  => $subKeuangan->id,
                'password'   => $password,
                'is_active'  => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'kasubag.umum@perumda.local'],
            [
                'name'       => 'I Made Wirawan',
                'nip'        => '400000002',
                'role'       => 'kasubbag',
                'jabatan_id' => $jabKasubag->id,
                'bagian_id'  => $subUmum->id,
                'password'   => $password,
                'is_active'  => true,
            ]
        );

        // Di bawah Kabag Teknik
        User::firstOrCreate(
            ['email' => 'kasubag.produksi@perumda.local'],
            [
                'name'       => 'I Wayan Sudiarta',
                'nip'        => '400000003',
                'role'       => 'kasubbag',
                'jabatan_id' => $jabKasubag->id,
                'bagian_id'  => $subProduksi->id,
                'password'   => $password,
                'is_active'  => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'kasubag.distribusi@perumda.local'],
            [
                'name'       => 'I Ketut Artawan',
                'nip'        => '400000004',
                'role'       => 'kasubbag',
                'jabatan_id' => $jabKasubag->id,
                'bagian_id'  => $subDistribusi->id,
                'password'   => $password,
                'is_active'  => true,
            ]
        );

        // Di bawah Kabag Hubungan Pelanggan
        User::firstOrCreate(
            ['email' => 'kasubag.pelayanan@perumda.local'],
            [
                'name'       => 'Ni Komang Ayu Ratih',
                'nip'        => '400000005',
                'role'       => 'kasubbag',
                'jabatan_id' => $jabKasubag->id,
                'bagian_id'  => $subPelayanan->id,
                'password'   => $password,
                'is_active'  => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'kasubag.humas@perumda.local'],
            [
                'name'       => 'I Gede Mahendra',
                'nip'        => '400000006',
                'role'       => 'kasubbag',
                'jabatan_id' => $jabKasubag->id,
                'bagian_id'  => $subHumas->id,
                'password'   => $password,
                'is_active'  => true,
            ]
        );

        $this->command->info('✅ Data organisasi berhasil di-seed!');
        $this->command->newLine();
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin',    'admin@perumda.local',              'password'],
                ['Staff',    'staff@perumda.local',              'password'],
                ['Direktur', 'direktur@perumda.local',           'password'],
                ['Kabag',    'kabag.adminkeu@perumda.local',     'password'],
                ['Kabag',    'kabag.teknik@perumda.local',       'password'],
                ['Kabag',    'kabag.hubpel@perumda.local',       'password'],
                ['Kasubag',  'kasubag.keuangan@perumda.local',   'password'],
                ['Kasubag',  'kasubag.umum@perumda.local',       'password'],
                ['Kasubag',  'kasubag.produksi@perumda.local',   'password'],
                ['Kasubag',  'kasubag.distribusi@perumda.local', 'password'],
                ['Kasubag',  'kasubag.pelayanan@perumda.local',  'password'],
                ['Kasubag',  'kasubag.humas@perumda.local',      'password'],
            ]
        );
    }
}