<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Page;
use App\Models\Photo;
use App\Models\Post;
use App\Models\Publication;
use App\Models\Setting;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Isi contoh supaya tampilan bisa langsung dinilai.
 * Semua teks di bawah ini karangan untuk keperluan uji tampilan —
 * hapus dengan `php artisan migrate:fresh` sebelum situs dipakai sungguhan.
 */
class KontenSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = collect([
            ['name' => 'Kabar DPW',     'slug' => 'kabar-dpw',     'type' => 'berita', 'icon' => 'berita',   'order' => 1],
            ['name' => 'Kabar DPD',     'slug' => 'kabar-dpd',     'type' => 'berita', 'icon' => 'peta',     'order' => 2],
            ['name' => 'Opini',         'slug' => 'opini',         'type' => 'opini',  'icon' => 'gagasan',  'order' => 3],
            ['name' => 'Kutipan Media', 'slug' => 'kutipan-media', 'type' => 'media',  'icon' => 'kutipan',  'order' => 4],
        ])->mapWithKeys(fn ($k) => [$k['slug'] => Category::create($k)]);

        $badan = <<<'HTML'
<p>Kegiatan ini menjadi bagian dari agenda konsolidasi wilayah yang dijadwalkan berjalan sepanjang tahun di delapan kabupaten se-Papua Pegunungan.</p>
<p>Pengurus menyampaikan bahwa medan dan jarak antarkabupaten menuntut pola kerja yang berbeda dari daerah lain. Karena itu, koordinasi dilakukan berlapis: tingkat wilayah menetapkan arah, tingkat daerah menyesuaikan dengan kondisi lapangan masing-masing.</p>
<h2>Fokus program</h2>
<p>Tiga hal yang disepakati untuk didahulukan adalah penguatan struktur di tingkat kecamatan, pendataan anggota, dan pendampingan warga di sektor pertanian dataran tinggi.</p>
<ul>
<li>Pelatihan pengurus baru di tiap kabupaten</li>
<li>Pendampingan kelompok tani hortikultura</li>
<li>Layanan pengaduan warga melalui posko wilayah</li>
</ul>
<p>Pengurus juga membuka ruang bagi masyarakat untuk menyampaikan masukan langsung melalui sekretariat DPW di Wamena.</p>
HTML;

        $berita = [
            ['Musyawarah Kerja Wilayah Tetapkan Program Prioritas 2026', 'kabar-dpw', true],
            ['DPW Salurkan Bantuan untuk Warga Terdampak Cuaca Ekstrem di Lanny Jaya', 'kabar-dpw', true],
            ['Pelatihan Kader Tingkat Dasar Digelar Serentak di Empat Kabupaten', 'kabar-dpw', false],
            ['Struktur DPD Pegunungan Bintang Dilantik di Oksibil', 'kabar-dpd', false],
            ['Legislator PKS Dorong Perbaikan Jalan Penghubung Wamena–Tolikara', 'kabar-dpw', true],
            ['Posko Layanan Warga Dibuka di Sekretariat DPW Wamena', 'kabar-dpw', false],
            ['DPD Yahukimo Gelar Bakti Sosial Pemeriksaan Kesehatan Gratis', 'kabar-dpd', false],
            ['Konsolidasi Kader Perempuan Bahas Ketahanan Keluarga di Dataran Tinggi', 'kabar-dpw', false],
            ['Pengurus Tinjau Kesiapan Lumbung Pangan di Kurulu', 'kabar-dpd', false],
        ];

        foreach ($berita as $i => [$judul, $slugKategori, $pilihan]) {
            Post::create([
                'category_id'  => $kategori[$slugKategori]->id,
                'title'        => $judul,
                'slug'         => Str::slug($judul),
                'excerpt'      => 'Ringkasan singkat kegiatan yang dilaksanakan pengurus wilayah bersama struktur daerah di Papua Pegunungan.',
                'body'         => $badan,
                'author_name'  => 'Humas DPW',
                'is_featured'  => $pilihan,
                'published_at' => now()->subDays($i)->subHours($i * 3),
            ]);
        }

        $opini = [
            ['Membangun Kaderisasi yang Berakar pada Adat Pegunungan', 'Ahmad Suryana'],
            ['Pangan Lokal Sebagai Jalan Kemandirian Wilayah', 'Marlina Wenda'],
            ['Politik Pelayanan di Daerah dengan Akses Terbatas', 'Yusuf Kogoya'],
        ];

        foreach ($opini as $i => [$judul, $penulis]) {
            Post::create([
                'category_id'  => $kategori['opini']->id,
                'title'        => $judul,
                'slug'         => Str::slug($judul),
                'excerpt'      => 'Catatan pengurus mengenai arah kerja partai di wilayah pegunungan.',
                'body'         => $badan,
                'author_name'  => $penulis,
                'published_at' => now()->subDays($i * 4 + 1),
            ]);
        }

        $media = [
            ['PKS Papua Pegunungan Dorong Anggaran Infrastruktur Dasar', 'Antara'],
            ['Fraksi PKS Soroti Distribusi Logistik ke Wilayah Terisolasi', 'Kompas'],
            ['Pengurus Wilayah Minta Percepatan Pembangunan Puskesmas', 'Republika'],
            ['PKS Siapkan Kader Muda untuk Pemilu Daerah', 'Detik'],
        ];

        foreach ($media as $i => [$judul, $sumber]) {
            Post::create([
                'category_id'  => $kategori['kutipan-media']->id,
                'title'        => $judul,
                'slug'         => Str::slug($judul),
                'excerpt'      => 'Kutipan pemberitaan media mengenai kegiatan dan sikap partai di Papua Pegunungan.',
                'body'         => $badan,
                'source_name'  => $sumber,
                'published_at' => now()->subDays($i * 2 + 1),
            ]);
        }

        $spanduk = [
            ['Dirgahayu Republik Indonesia', 'Merdeka di tanah pegunungan, berkhidmat untuk negeri', null],
            ['Musyawarah Kerja Wilayah 2026', 'Delapan kabupaten, satu arah kerja', '/kategori/kabar-dpw'],
            ['Pendaftaran Anggota Dibuka', 'Bergabunglah bersama struktur di kabupaten Anda', 'https://daftar.pks.id'],
        ];

        foreach ($spanduk as $i => [$judul, $anak, $tautan]) {
            Banner::create([
                'title'      => $judul,
                'subtitle'   => $anak,
                'image_path' => '',   // kosong -> memakai gambar sementara
                'link'       => $tautan,
                'order'      => $i,
                'is_active'  => true,
            ]);
        }

        $spanduk = [
            ['Musyawarah Kerja Wilayah 2026', 'Wamena, 5–7 Agustus 2026', '/kategori/kabar-dpw'],
            ['Pendaftaran Anggota Dibuka', 'Isi formulir daring, prosesnya lima menit', 'https://daftar.pks.id'],
            ['Posko Layanan Warga', 'Sekretariat DPW, setiap hari kerja 08.00–16.00', null],
        ];

        foreach ($spanduk as $i => [$judul, $anak, $tautan]) {
            Banner::create([
                'title'      => $judul,
                'subtitle'   => $anak,
                'image_path'        => '',   // kosong -> memakai gambar contoh
                'image_mobile_path' => '',
                'link'       => $tautan,
                'order'      => $i,
                'is_active'  => true,
            ]);
        }

        $album = Album::create([
            'title'        => 'Musyawarah Kerja Wilayah 2026 di Wamena',
            'slug'         => 'muskerwil-2026-wamena',
            'description'  => 'Dokumentasi rangkaian musyawarah kerja wilayah yang diikuti pengurus dari delapan kabupaten.',
            'location'     => 'Wamena, Jayawijaya',
            'published_at' => now()->subDays(3),
        ]);

        foreach (range(1, 8) as $n) {
            Photo::create([
                'album_id' => $album->id,
                'path'     => '',   // kosong -> memakai gambar sementara
                'caption'  => "Sesi {$n} musyawarah kerja wilayah",
                'order'    => $n,
            ]);
        }

        $video = [
            'Cuplikan Musyawarah Kerja Wilayah 2026',
            'Wawancara Ketua DPW soal Program Pangan Lokal',
            'Bakti Sosial DPD Yahukimo',
            'Pelatihan Kader Tingkat Dasar di Wamena',
        ];

        foreach ($video as $i => $judul) {
            Video::create([
                'title'        => $judul,
                'slug'         => Str::slug($judul),
                'youtube_id'   => 'dQw4w9WgXcQ',  // ganti dengan ID video asli
                'description'  => 'Tayangan dokumentasi kegiatan DPW PKS Papua Pegunungan.',
                'published_at' => now()->subDays($i * 5 + 2),
            ]);
        }

        $terbitan = [
            ['Buletin Honai', 'Edisi 12 — Agustus 2026'],
            ['Buletin Honai', 'Edisi 11 — Juli 2026'],
            ['Materi Kaderisasi', 'Seri 3: Kerja Wilayah'],
            ['Laporan Kegiatan', 'Semester I 2026'],
        ];

        foreach ($terbitan as $i => [$seri, $edisi]) {
            Publication::create([
                'title'        => "{$seri} — {$edisi}",
                'slug'         => Str::slug("{$seri} {$edisi}"),
                'series'       => $seri,
                'edition'      => $edisi,
                'description'  => 'Edisi ini membahas arah kerja pengurus wilayah di delapan kabupaten se-Papua Pegunungan, dari penguatan struktur kecamatan sampai pendampingan petani dataran tinggi.',
                'published_at' => now()->subMonths($i),
            ]);
        }

        $halaman = [
            ['Profil DPW Papua Pegunungan', 'profil-dpw',       'Seputar DPW'],
            ['Struktur Pengurus',           'struktur-pengurus', 'Seputar DPW'],
            ['Visi, Misi & Platform',       'visi-misi',        'Seputar DPW'],
            ['AD & ART Partai',             'ad-art',           'Seputar DPW'],
            ['Laporan Keuangan',            'laporan-keuangan', 'Seputar DPW'],
            ['Sikap DPW',                   'sikap-dpw',        'Sikap & Kebijakan'],
            ['Bayanat',                     'bayanat',          'Sikap & Kebijakan'],
            ['Anti Hoaks',                  'anti-hoaks',       'Sikap & Kebijakan'],
            ['Info Publik',                 'info-publik',      null],
            ['Sumbangan',                   'sumbangan',        null],
            ['DPD Jayawijaya',         'dpd-jayawijaya',         'DPD Kabupaten'],
            ['DPD Lanny Jaya',         'dpd-lanny-jaya',         'DPD Kabupaten'],
            ['DPD Tolikara',           'dpd-tolikara',           'DPD Kabupaten'],
            ['DPD Yahukimo',           'dpd-yahukimo',           'DPD Kabupaten'],
            ['DPD Yalimo',             'dpd-yalimo',             'DPD Kabupaten'],
            ['DPD Nduga',              'dpd-nduga',              'DPD Kabupaten'],
            ['DPD Mamberamo Tengah',   'dpd-mamberamo-tengah',   'DPD Kabupaten'],
            ['DPD Pegunungan Bintang', 'dpd-pegunungan-bintang', 'DPD Kabupaten'],
        ];

        foreach ($halaman as $i => [$judul, $slug, $grup]) {
            Page::create([
                'title'      => $judul,
                'slug'       => $slug,
                'body'       => "<p>Isi halaman <strong>{$judul}</strong> belum ditulis. Masuk ke panel admin untuk menyuntingnya.</p>",
                'menu_group' => $grup,
                'order'      => $i,
            ]);
        }

        foreach ([
            'alamat'  => 'Jl. Trikora, Wamena, Kabupaten Jayawijaya, Papua Pegunungan 99511',
            'telepon' => '+62 969 000 0000',
            'surel'         => 'sekretariat@papua-pegunungan.pks.id',
            'nama_singkat'  => 'Dewan Pengurus Wilayah',
            'nama_lengkap'  => 'PKS Papua Pegunungan',
            'tagline'       => 'Kenali PKS Papua Pegunungan lebih dekat',
            'ukuran_nama_situs' => 'sedang',
            'footer_tema'       => 'gelap',
            // Contoh dua kanal saja. Sisanya dikosongkan supaya terlihat
            // bahwa kanal kosong memang tidak ditampilkan di situs.
            'sosial_instagram' => 'https://instagram.com/',
            'sosial_youtube'   => 'https://youtube.com/',
            'font_situs'       => 'plus-jakarta-sans',
            'skala_teks'       => 'normal',
            'rasio_spanduk'    => 'auto',
            'sudut_gambar'     => 'sedang',
            'logo_tinggi'       => '44',
            'logo_tinggi_kecil' => '36',
            'kop_tinggi'        => '80',
            'kop_tinggi_kecil'  => '64',
            'kop_lebar'         => 'isi',
            'layout_beranda'      => json_encode(\App\Support\Layout::blokBawaan()),
            'layout_header_pita'  => 'tampil',
            'layout_daftar'       => 'grid',
            'gaya_pita_atas'      => 'hitam',
            'gaya_tautan_judul'   => 'warna',
            'warna_aksen'        => '#FE5000',
            'warna_latar'        => '#F7F7F8',
            'warna_teks'         => '#18181B',
            'skala_judul_beranda' => 'sedang',
            'skala_judul_artikel' => 'sedang',
        ] as $kunci => $nilai) {
            Setting::put($kunci, $nilai);
        }
    }
}
