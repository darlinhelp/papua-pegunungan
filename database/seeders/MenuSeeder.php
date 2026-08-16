<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

/**
 * Susunan menu bawaan, dipindahkan dari config/menu.php yang lama supaya
 * situs tetap tampil sama persis setelah beralih ke menu berbasis basis
 * data. Berkas config/menu.php sendiri sudah tidak dipakai lagi setelah
 * tambalan ini, tapi dibiarkan ada — tidak mengganggu apa pun.
 */
class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $susunan = [
            [
                'label' => 'Seputar DPW',
                'items' => [
                    ['label' => 'Profil DPW Papua Pegunungan', 'url' => '/halaman/profil-dpw'],
                    ['label' => 'Struktur Pengurus',           'url' => '/halaman/struktur-pengurus'],
                    ['label' => 'Visi, Misi & Platform',       'url' => '/halaman/visi-misi'],
                    ['label' => 'AD & ART Partai',             'url' => '/halaman/ad-art'],
                    ['label' => 'Laporan Keuangan',            'url' => '/halaman/laporan-keuangan'],
                    ['label' => 'Formulir Anggota', 'url' => 'https://daftar.pks.id', 'luar' => true],
                ],
            ],
            [
                'label' => 'Sikap & Kebijakan',
                'items' => [
                    ['label' => 'Sikap DPW',  'url' => '/halaman/sikap-dpw'],
                    ['label' => 'Bayanat',    'url' => '/halaman/bayanat'],
                    ['label' => 'Anti Hoaks', 'url' => '/halaman/anti-hoaks'],
                ],
            ],
            [
                'label' => 'Terkini',
                'items' => [
                    ['label' => 'Berita Pilihan', 'url' => 'route:posts.featured'],
                    ['label' => 'Kabar DPW',      'url' => '/kategori/kabar-dpw'],
                    ['label' => 'Kabar DPD',      'url' => '/kategori/kabar-dpd'],
                    ['label' => 'Opini',          'url' => '/kategori/opini'],
                    ['label' => 'Kutipan Media',  'url' => '/kategori/kutipan-media'],
                    ['label' => 'Galeri Foto',    'url' => 'route:gallery.index'],
                    ['label' => 'Video',          'url' => 'route:videos.index'],
                    ['label' => 'Publikasi',      'url' => 'route:publications.index'],
                ],
            ],
            [
                'label' => 'DPD Kabupaten',
                'wide'  => true,
                'items' => [
                    ['label' => 'Jayawijaya',         'url' => '/halaman/dpd-jayawijaya'],
                    ['label' => 'Lanny Jaya',         'url' => '/halaman/dpd-lanny-jaya'],
                    ['label' => 'Tolikara',           'url' => '/halaman/dpd-tolikara'],
                    ['label' => 'Yahukimo',           'url' => '/halaman/dpd-yahukimo'],
                    ['label' => 'Yalimo',             'url' => '/halaman/dpd-yalimo'],
                    ['label' => 'Nduga',              'url' => '/halaman/dpd-nduga'],
                    ['label' => 'Mamberamo Tengah',   'url' => '/halaman/dpd-mamberamo-tengah'],
                    ['label' => 'Pegunungan Bintang', 'url' => '/halaman/dpd-pegunungan-bintang'],
                ],
            ],
        ];

        foreach ($susunan as $urutanGrup => $grup) {
            $induk = MenuItem::create([
                'label' => $grup['label'],
                'wide'  => $grup['wide'] ?? false,
                'order' => $urutanGrup,
            ]);

            foreach ($grup['items'] as $urutanItem => $item) {
                MenuItem::create([
                    'parent_id'   => $induk->id,
                    'label'       => $item['label'],
                    'url'         => $item['url'],
                    'is_external' => $item['luar'] ?? false,
                    'order'       => $urutanItem,
                ]);
            }
        }
    }
}
