<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Ekstrakurikuler;
use App\Models\Kriteria;
use App\Models\PenilaianSiswa;
use App\Models\Pendaftaran;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();

        try {
            // ============================================
            // 1. CREATE USERS WITH CLEAR ROLE SEPARATION
            // ============================================


            // 👨‍🏫 PEMBINA = ACTIVITY MANAGERS
            $pembina1 = User::create([
                'name' => 'Pak Ahmad Hidayat',
                'email' => 'ahmad@ekskul.com',
                'password' => Hash::make('pembina123'),
                'role' => 'pembina',
                'phone' => '081234567891'
            ]);

            $pembina2 = User::create([
                'name' => 'Bu Sari Dewi',
                'email' => 'sari@ekskul.com',
                'password' => Hash::make('pembina123'),
                'role' => 'pembina',
                'phone' => '081234567892'
            ]);

            $pembina3 = User::create([
                'name' => 'Pak Budi Santoso',
                'email' => 'budi@ekskul.com',
                'password' => Hash::make('pembina123'),
                'role' => 'pembina',
                'phone' => '081234567893'
            ]);

            $pembina4 = User::create([
                'name' => 'Bu Rina Wulandari',
                'email' => 'rina@ekskul.com',
                'password' => Hash::make('pembina123'),
                'role' => 'pembina',
                'phone' => '081234567894'
            ]);

            // 👨‍🎓 SISWA = END USERS
            $siswaUsers = [];
            $siswaData = [
                ['name' => 'Muhammad Fadil', 'email' => 'fadil@student.com', 'nisn' => '2024001', 'kelas' => 'X IPA 1'],
                ['name' => 'Siti Aisyah', 'email' => 'aisyah@student.com', 'nisn' => '2024002', 'kelas' => 'X IPA 1'],
                ['name' => 'Ahmad Rizki', 'email' => 'rizki@student.com', 'nisn' => '2024003', 'kelas' => 'X IPA 2'],
                ['name' => 'Dewi Sartika', 'email' => 'dewi@student.com', 'nisn' => '2024004', 'kelas' => 'X IPS 1'],
                ['name' => 'Iqbal Maulana', 'email' => 'iqbal@student.com', 'nisn' => '2024005', 'kelas' => 'XI IPA 1'],
                ['name' => 'Nurul Fitri', 'email' => 'nurul@student.com', 'nisn' => '2024006', 'kelas' => 'XI IPA 2'],
                ['name' => 'Rahman Hakim', 'email' => 'rahman@student.com', 'nisn' => '2024007', 'kelas' => 'XI IPS 1'],
                ['name' => 'Zahra Amelia', 'email' => 'zahra@student.com', 'nisn' => '2024008', 'kelas' => 'XII IPA 1'],
                ['name' => 'Dian Pratama', 'email' => 'dian@student.com', 'nisn' => '2024009', 'kelas' => 'XII IPA 2'],
                ['name' => 'Lestari Wati', 'email' => 'lestari@student.com', 'nisn' => '2024010', 'kelas' => 'XII IPS 1'],
            ];

            foreach ($siswaData as $data) {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make('siswa123'),
                    'role' => 'siswa',
                    'phone' => '0812345678' . rand(10, 99)
                ]);
                $siswaUsers[] = ['user' => $user, 'data' => $data];
            }

            // ============================================
            // 2. CREATE KRITERIA FOR WEIGHTED SCORING
            // ============================================
            $kriteria = [
                [
                    'nama_kriteria' => 'Nilai Akademik',
                    'bobot' => 0.30,
                    'tipe' => 'benefit',
                    'deskripsi' => 'Rata-rata nilai akademik siswa secara keseluruhan',
                    'is_active' => true
                ],
                [
                    'nama_kriteria' => 'Minat dan Bakat',
                    'bobot' => 0.25,
                    'tipe' => 'benefit',
                    'deskripsi' => 'Tingkat minat dan bakat siswa terhadap bidang tertentu',
                    'is_active' => true
                ],
                [
                    'nama_kriteria' => 'Ketersediaan Waktu',
                    'bobot' => 0.20,
                    'tipe' => 'benefit',
                    'deskripsi' => 'Fleksibilitas waktu siswa untuk mengikuti kegiatan',
                    'is_active' => true
                ],
                [
                    'nama_kriteria' => 'Kemampuan Sosial',
                    'bobot' => 0.15,
                    'tipe' => 'benefit',
                    'deskripsi' => 'Kemampuan berinteraksi dan bekerja sama dengan orang lain',
                    'is_active' => true
                ],
                [
                    'nama_kriteria' => 'Pengalaman Sebelumnya',
                    'bobot' => 0.10,
                    'tipe' => 'benefit',
                    'deskripsi' => 'Pengalaman siswa dalam kegiatan sejenis',
                    'is_active' => true
                ]
            ];

            foreach ($kriteria as $k) {
                Kriteria::create($k);
            }

            // ============================================
            // 3. CREATE SISWA PROFILES
            // ============================================
            $siswaProfiles = [];
            foreach ($siswaUsers as $item) {
                $minatOptions = ['Olahraga', 'Seni', 'Akademik', 'Teknologi', 'Sosial'];
                $randomMinat = collect($minatOptions)->random(rand(1, 3))->toArray();

                $siswa = Siswa::create([
                    'user_id' => $item['user']->id,
                    'nisn' => $item['data']['nisn'],
                    'kelas' => $item['data']['kelas'],
                    'jenis_kelamin' => rand(0, 1) ? 'L' : 'P',
                    'tanggal_lahir' => now()->subYears(rand(15, 18))->subDays(rand(1, 365)),
                    'alamat' => 'Jl. Contoh No. ' . rand(1, 100) . ', Bandung',
                    'minat' => $randomMinat,
                    'nilai_akademik' => rand(70, 95)
                ]);
                $siswaProfiles[] = $siswa;
            }

            // ============================================
            // 4. CREATE EKSTRAKURIKULER
            // ============================================
            $ekstrakurikulerData = [
                [
                    'nama_ekskul' => 'Futsal',
                    'deskripsi' => 'Ekstrakurikuler olahraga futsal untuk mengembangkan kemampuan bermain sepak bola dalam ruangan',
                    'kategori' => 'Olahraga',
                    'kapasitas_maksimal' => 20,
                    'hari' => 'Selasa',
                    'jam_mulai' => '15:30:00',
                    'jam_selesai' => '17:00:00',
                    'tempat' => 'Lapangan Futsal',
                    'pembina_id' => $pembina1->id
                ],
                [
                    'nama_ekskul' => 'Basket',
                    'deskripsi' => 'Ekstrakurikuler basket untuk melatih kemampuan bermain bola basket',
                    'kategori' => 'Olahraga',
                    'kapasitas_maksimal' => 15,
                    'hari' => 'Kamis',
                    'jam_mulai' => '15:30:00',
                    'jam_selesai' => '17:00:00',
                    'tempat' => 'Lapangan Basket',
                    'pembina_id' => $pembina1->id
                ],
                [
                    'nama_ekskul' => 'Seni Tari',
                    'deskripsi' => 'Ekstrakurikuler seni tari tradisional dan modern untuk mengembangkan kreativitas',
                    'kategori' => 'Seni',
                    'kapasitas_maksimal' => 25,
                    'hari' => 'Rabu',
                    'jam_mulai' => '14:00:00',
                    'jam_selesai' => '16:00:00',
                    'tempat' => 'Aula Seni',
                    'pembina_id' => $pembina2->id
                ],
                [
                    'nama_ekskul' => 'Paduan Suara',
                    'deskripsi' => 'Ekstrakurikuler musik vokal untuk mengembangkan kemampuan bernyanyi',
                    'kategori' => 'Seni',
                    'kapasitas_maksimal' => 30,
                    'hari' => 'Jumat',
                    'jam_mulai' => '14:00:00',
                    'jam_selesai' => '16:00:00',
                    'tempat' => 'Ruang Musik',
                    'pembina_id' => $pembina2->id
                ],
                [
                    'nama_ekskul' => 'Robotika',
                    'deskripsi' => 'Ekstrakurikuler teknologi untuk belajar membuat dan memprogram robot',
                    'kategori' => 'Teknologi',
                    'kapasitas_maksimal' => 15,
                    'hari' => 'Sabtu',
                    'jam_mulai' => '08:00:00',
                    'jam_selesai' => '10:00:00',
                    'tempat' => 'Lab Komputer',
                    'pembina_id' => $pembina3->id
                ],
                [
                    'nama_ekskul' => 'Jurnalistik',
                    'deskripsi' => 'Ekstrakurikuler jurnalistik untuk mengembangkan kemampuan menulis dan komunikasi',
                    'kategori' => 'Akademik',
                    'kapasitas_maksimal' => 20,
                    'hari' => 'Senin',
                    'jam_mulai' => '15:30:00',
                    'jam_selesai' => '17:00:00',
                    'tempat' => 'Ruang Redaksi',
                    'pembina_id' => $pembina3->id
                ],
                [
                    'nama_ekskul' => 'Pramuka',
                    'deskripsi' => 'Ekstrakurikuler pramuka untuk membentuk karakter dan kepemimpinan',
                    'kategori' => 'Sosial',
                    'kapasitas_maksimal' => 40,
                    'hari' => 'Sabtu',
                    'jam_mulai' => '14:00:00',
                    'jam_selesai' => '16:30:00',
                    'tempat' => 'Lapangan Upacara',
                    'pembina_id' => $pembina4->id
                ],
                [
                    'nama_ekskul' => 'PMR (Palang Merah Remaja)',
                    'deskripsi' => 'Ekstrakurikuler kesehatan untuk belajar pertolongan pertama dan kemanusiaan',
                    'kategori' => 'Sosial',
                    'kapasitas_maksimal' => 25,
                    'hari' => 'Rabu',
                    'jam_mulai' => '15:30:00',
                    'jam_selesai' => '17:00:00',
                    'tempat' => 'Ruang PMR',
                    'pembina_id' => $pembina4->id
                ]
            ];

            $ekstrakurikulerList = [];
            foreach ($ekstrakurikulerData as $data) {
                $ekskul = Ekstrakurikuler::create($data);
                $ekstrakurikulerList[] = $ekskul;
            }

            // ============================================
            // 5. CREATE PENILAIAN SISWA
            // ============================================
            $kriteriaList = Kriteria::where('is_active', true)->get();

            foreach ($siswaProfiles as $siswa) {
                foreach ($kriteriaList as $kriteria) {
                    // Generate realistic scores based on siswa profile
                    $baseScore = $siswa->nilai_akademik;

                    $score = match ($kriteria->nama_kriteria) {
                        'Nilai Akademik' => $baseScore + rand(-5, 5),
                        'Minat dan Bakat' => $this->calculateMinatScore($siswa, $baseScore),
                        'Ketersediaan Waktu' => rand(60, 90),
                        'Kemampuan Sosial' => rand(65, 85),
                        'Pengalaman Sebelumnya' => rand(50, 80),
                        default => rand(60, 85)
                    };

                    PenilaianSiswa::create([
                        'siswa_id' => $siswa->id,
                        'kriteria_id' => $kriteria->id,
                        'nilai' => max(0, min(100, $score))
                    ]);
                }
            }


            // ============================================
            // 7. CREATE SAMPLE PENGUMUMAN
            // ============================================
            $pengumumanData = [
                [
                    'judul' => 'Latihan Rutin Futsal - Perubahan Jadwal',
                    'konten' => 'Latihan futsal minggu ini dipindah ke hari Rabu karena ada acara sekolah pada hari Selasa.',
                    'kategori' => 'ekstrakurikuler',
                    'author_id' => $pembina1->id,
                    'is_published' => true,
                    'published_at' => now()->subDays(2)
                ],
                [
                    'judul' => 'Perlombaan Seni Tari Tingkat Provinsi',
                    'konten' => 'Akan diadakan seleksi internal untuk mengikuti perlombaan seni tari tingkat provinsi. Pendaftaran dibuka untuk anggota ekstrakurikuler seni tari.',
                    'kategori' => 'ekstrakurikuler',
                    'author_id' => $pembina2->id,
                    'is_published' => true,
                    'published_at' => now()->subDays(1)
                ]
            ];

            foreach ($pengumumanData as $data) {
                Pengumuman::create($data);
            }

            DB::commit();

            $this->command->info('✅ DATABASE SEEDED SUCCESSFULLY!');
            $this->command->info('');
            $this->command->info('🔐 LOGIN CREDENTIALS:');
            $this->command->info('👨‍💼 ADMIN (System Manager): admin@ekskul.com / admin123');
            $this->command->info('👨‍🏫 PEMBINA (Activity Manager): ahmad@ekskul.com / pembina123');
            $this->command->info('👨‍🎓 SISWA (End User): fadil@student.com / siswa123');
            $this->command->info('');
            $this->command->info('📊 DATA CREATED:');
            $this->command->info('- Users: ' . User::count());
            $this->command->info('- Siswa: ' . Siswa::count());
            $this->command->info('- Ekstrakurikuler: ' . Ekstrakurikuler::count());
            $this->command->info('- Kriteria: ' . Kriteria::count());
            $this->command->info('- Pendaftaran: ' . Pendaftaran::count());
        } catch (\Exception $e) {
            DB::rollback();
            $this->command->error('❌ Seeding failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Calculate minat score based on siswa interests
     */
    private function calculateMinatScore($siswa, $baseScore)
    {
        $minatCategories = $siswa->minat ?? [];

        if (empty($minatCategories)) {
            return rand(50, 70);
        }

        // Higher score if student has diverse interests
        $diversityBonus = count($minatCategories) * 5;
        return min(100, $baseScore + $diversityBonus + rand(-10, 10));
    }
}
