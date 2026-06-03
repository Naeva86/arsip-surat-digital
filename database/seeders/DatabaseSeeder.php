<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Bagian;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ═══════════════════════════════════
        // 1. Tambah Jabatan yang belum ada
        // ═══════════════════════════════════
        $jabatanMap = [];

        $jabatanData = [
            ['nama_jabatan' => 'Dewan Pengawas',      'level' => 1],
            ['nama_jabatan' => 'Direktur',             'level' => 1],
            ['nama_jabatan' => 'Kepala SPI',           'level' => 2],
            ['nama_jabatan' => 'Kepala Bagian',        'level' => 2],
            ['nama_jabatan' => 'Kepala Unit',          'level' => 2],
            ['nama_jabatan' => 'Kepala Sub Bagian',    'level' => 3],
            ['nama_jabatan' => 'Kepala Sub Unit',      'level' => 3],
            ['nama_jabatan' => 'Staff',                'level' => 4],
        ];

        foreach ($jabatanData as $j) {
            $jabatan = Jabatan::firstOrCreate(
                ['nama_jabatan' => $j['nama_jabatan']],
                ['level' => $j['level']]
            );
            $jabatanMap[$j['nama_jabatan']] = $jabatan->id;
        }

        // ═══════════════════════════════════
        // 3. Helper: cari bagian ID by nama
        // ═══════════════════════════════════
        $bagianId = function ($nama) {
            return Bagian::where('nama_bagian', 'like', "%{$nama}%")->first()?->id;
        };

        // ═══════════════════════════════════
        // 4. Resolve role dari level
        // ═══════════════════════════════════
        $roleFromLevel = function ($level) {
            return match ($level) {
                1 => 'direktur',
                2 => 'kabag',
                3 => 'kasubbag',
                default => 'staff',
            };
        };

        // ═══════════════════════════════════
        // 5. Data User
        // ═══════════════════════════════════
        $users = [
            // ── Dewan Pengawas & Direktur ──
            [
                'name'     => 'I Wayan Marditha, SH',
                'nip'      => null,
                'jabatan'  => 'Dewan Pengawas',
                'bagian'   => null,
            ],
            [
                'name'     => 'IDG Ratno Suparso Mesi, ST.MT.',
                'nip'      => null,
                'jabatan'  => 'Direktur',
                'bagian'   => null,
            ],

            // ── Kepala Bagian ──
            [
                'name'     => 'Ida Bagus Putu Perenawa, ST',
                'nip'      => '19710807 01 0076',
                'jabatan'  => 'Kepala SPI',
                'bagian'   => 'Bagian SPI',
            ],
            [
                'name'     => 'Ni Putu Ahadyani',
                'nip'      => '19710812 02 0077',
                'jabatan'  => 'Kepala Bagian',
                'bagian'   => 'Bagian Hubungan Pelanggan',
            ],
            [
                'name'     => 'I Wayan Sadia, SE',
                'nip'      => '19740310 01 0112',
                'jabatan'  => 'Kepala Bagian',
                'bagian'   => 'Bagian Administrasi & Keuangan',
            ],
            [
                'name'     => 'I Wayan Gunawan, ST',
                'nip'      => '19770410 01 0142',
                'jabatan'  => 'Kepala Bagian',
                'bagian'   => 'Bagian Teknik',
            ],

            // ── Kepala Unit ──
            [
                'name'     => 'I Nyoman Sumberdana',
                'nip'      => '19710821 01 0100',
                'jabatan'  => 'Kepala Unit',
                'bagian'   => 'Unit Bangli',
            ],
            [
                'name'     => 'I Wayan Suradnya',
                'nip'      => '19740629 01 0124',
                'jabatan'  => 'Kepala Unit',
                'bagian'   => 'Unit Susut',
            ],
            [
                'name'     => 'I G A J Sutha Baskara, SH',
                'nip'      => '19701227 01 0114',
                'jabatan'  => 'Kepala Unit',
                'bagian'   => 'Unit Tembuku',
            ],
            [
                'name'     => 'I Nengah Subadra',
                'nip'      => '19720629 01 0117',
                'jabatan'  => 'Kepala Unit',
                'bagian'   => 'Unit Kintamani',
            ],

            // ── Kepala Sub Bagian ──
            [
                'name'     => 'I Wayan Rejeki',
                'nip'      => '19700727 01 0115',
                'jabatan'  => 'Kepala Sub Bagian',
                'bagian'   => 'Sub Bagian Sumber/Produksi',
            ],
            [
                'name'     => 'I Made Juliana',
                'nip'      => '19750724 01 0121',
                'jabatan'  => 'Kepala Sub Bagian',
                'bagian'   => 'Sub Bagian Transmisi/Distribusi',
            ],
            [
                'name'     => 'I Kadek Roi Saputra',
                'nip'      => '19821226 01 0150',
                'jabatan'  => 'Kepala Sub Bagian',
                'bagian'   => 'Sub Bagian Mekanikal/Elektrikal',
            ],
            [
                'name'     => 'I Wayan Saputra',
                'nip'      => '19930514 01 0183',
                'jabatan'  => 'Kepala Sub Bagian',
                'bagian'   => 'Pjs Sub Bagian Perencanaan Teknik',
            ],
            [
                'name'     => 'Ni Kadek Sri Purnama, SE',
                'nip'      => '19910330 02 0170',
                'jabatan'  => 'Kepala Sub Bagian',
                'bagian'   => 'Sub Bagian Administrasi Umum & Personalia',
            ],
            [
                'name'     => 'Ni Wayan Susiani, S.AK',
                'nip'      => '19841007 02 0156',
                'jabatan'  => 'Kepala Sub Bagian',
                'bagian'   => 'Sub Bagian Aset',
            ],
            [
                'name'     => 'Tjok Gede Rai Sarwadnyana',
                'nip'      => '19740824 01 0157',
                'jabatan'  => 'Kepala Sub Bagian',
                'bagian'   => 'Sub Bagian Logistik',
            ],
            [
                'name'     => 'Dewa Ayu Tri Supartami',
                'nip'      => '19851122 02 0169',
                'jabatan'  => 'Kepala Sub Bagian',
                'bagian'   => 'Sub Bagian Keuangan',
            ],
            [
                'name'     => 'I Dewa Ayu Gita Paramita, S.Pd',
                'nip'      => '19741116 02 0093',
                'jabatan'  => 'Kepala Sub Bagian',
                'bagian'   => 'Sub Bagian Pemasaran & Humas',
            ],
            [
                'name'     => 'Ni Luh Darmiasih',
                'nip'      => '19850706 02 0151',
                'jabatan'  => 'Kepala Sub Bagian',
                'bagian'   => 'Sub Bagian Rekening & Penagihan',
            ],

            // ── Kepala Sub Unit ──
            [
                'name'     => 'I Wayan Mertayasa',
                'nip'      => '19761105 01 0123',
                'jabatan'  => 'Kepala Sub Unit',
                'bagian'   => 'Sub Unit Bangli',
            ],
            [
                'name'     => 'I Dewa Nyoman Putra',
                'nip'      => '19700608 01 0063',
                'jabatan'  => 'Kepala Sub Unit',
                'bagian'   => 'Sub Unit TamanBali',
            ],
            [
                'name'     => 'I Wayan Winata',
                'nip'      => '19810908 01 0155',
                'jabatan'  => 'Kepala Sub Unit',
                'bagian'   => 'Sub Unit Kubu',
            ],
            [
                'name'     => 'I Dewa Gede Darmayuda',
                'nip'      => '19860718 01 0154',
                'jabatan'  => 'Kepala Sub Unit',
                'bagian'   => 'Sub Unit Susut',
            ],
            [
                'name'     => 'Ni Wayan Lilik Mirayani',
                'nip'      => '19831219 02 0148',
                'jabatan'  => 'Kepala Sub Unit',
                'bagian'   => 'Sub Unit Demulih',
            ],
            [
                'name'     => 'I Nengah Ade Satriawan',
                'nip'      => '19801224 01 0162',
                'jabatan'  => 'Kepala Sub Unit',
                'bagian'   => 'Sub Unit Abuan',
            ],
            [
                'name'     => 'I Nyoman Suwidnyana',
                'nip'      => '19750914 01 0182',
                'jabatan'  => 'Kepala Sub Unit',
                'bagian'   => 'Sub Unit Tembuku',
            ],
            [
                'name'     => 'I Wayan Panggih',
                'nip'      => '19790801 01 0159',
                'jabatan'  => 'Kepala Sub Unit',
                'bagian'   => 'Sub Unit Peninjoan',
            ],
            [
                'name'     => 'I Wayan Ariana',
                'nip'      => '19740621 01 0107',
                'jabatan'  => 'Kepala Sub Unit',
                'bagian'   => 'Sub Unit Undisan',
            ],
            [
                'name'     => 'I Nyoman Minggu',
                'nip'      => '19731231 01 0097',
                'jabatan'  => 'Kepala Sub Unit',
                'bagian'   => 'Sub Unit Kintamani',
            ],
            [
                'name'     => 'I Ketut Resi Suciawan',
                'nip'      => '19701127 01 0141',
                'jabatan'  => 'Kepala Sub Unit',
                'bagian'   => 'Sub Unit Catur',
            ],
            [
                'name'     => 'Kadek Dwi Destara',
                'nip'      => '19851203 01 0160',
                'jabatan'  => 'Kepala Sub Unit',
                'bagian'   => 'Sub Unit Suter',
            ],
            [
                'name'     => 'Made Windy Krisdyantari',
                'nip'      => null,
                'jabatan'  => 'staff',
                'bagian'   => null,
            ],
        ];

        // ═══════════════════════════════════
        // 6. Insert / Update Users
        // ═══════════════════════════════════
        $count = 0;

        foreach ($users as $u) {
            $jabatanObj = Jabatan::where('nama_jabatan', $u['jabatan'])->first();

            if (!$jabatanObj) {
                $this->command->warn("Jabatan '{$u['jabatan']}' tidak ditemukan, skip: {$u['name']}");
                continue;
            }

            $level = $jabatanObj->level;
            $role  = $roleFromLevel($level);

            $bagianObj = $u['bagian'] ? Bagian::where('nama_bagian', $u['bagian'])->first() : null;

            if ($u['bagian'] && !$bagianObj) {
                $this->command->warn("Bagian '{$u['bagian']}' tidak ditemukan, skip: {$u['name']}");
                continue;
            }

            // Email generate dari nama
            $emailSlug = strtolower(str_replace([' ', ',', '.', "'"], ['', '', '', ''], $u['name']));
            $emailSlug = substr($emailSlug, 0, 30);
            $email     = $emailSlug . '@perumda.local';

            // Cek duplicate NIP atau email
            $existing = null;
            if ($u['nip']) {
                $existing = User::withTrashed()->where('nip', $u['nip'])->first();
            }
            if (!$existing) {
                $existing = User::withTrashed()->where('email', $email)->first();
            }

            if ($existing) {
                // Update data
                $existing->update([
                    'name'       => $u['name'],
                    'jabatan_id' => $jabatanObj->id,
                    'bagian_id'  => $bagianObj?->id,
                    'role'       => $role,
                    'is_active'  => true,
                    'deleted_at' => null,
                ]);
                $this->command->info("Updated: {$u['name']}");
            } else {
                User::create([
                    'name'       => $u['name'],
                    'nip'        => $u['nip'],
                    'email'      => $email,
                    'password'   => Hash::make('password'),
                    'jabatan_id' => $jabatanObj->id,
                    'bagian_id'  => $bagianObj?->id,
                    'role'       => $role,
                    'is_active'  => true,
                ]);
                $count++;
                $this->command->info("Created: {$u['name']} ({$role}) — {$email}");
            }
        }

        $this->command->newLine();
        $this->command->info("✅ Selesai! {$count} user baru ditambahkan.");
        $this->command->info("📌 Password default semua user: password");
    }
}