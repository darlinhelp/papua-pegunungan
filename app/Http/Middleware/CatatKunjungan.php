<?php

namespace App\Http\Middleware;

use App\Models\DailyVisit;
use App\Support\Perangkat;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mencatat satu kunjungan per permintaan halaman yang berhasil, sekaligus
 * jenis perangkatnya (komputer/ponsel/tablet) dan menandai pengunjung
 * sebagai "sedang online".
 *
 * Sengaja hanya mencatat ANGKA per hari, bukan menyimpan alamat IP atau
 * data pengunjung satu per satu — situs organisasi tidak perlu melacak
 * identitas pengunjung, cukup tahu berapa ramainya dan lewat perangkat apa.
 */
class CatatKunjungan
{
    /** Dianggap "online" kalau memuat halaman dalam X menit terakhir. */
    protected const MENIT_ONLINE = 5;

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->layakDicatat($request, $response)) {
            $hariIni = now()->toDateString();

            // ON DUPLICATE KEY UPDATE menambah 1 secara atomik — aman dipakai
            // meski dua pengunjung mengakses situs pada detik yang sama,
            // tanpa perlu membaca-lalu-menulis yang rawan kondisi balapan.
            DB::statement(
                'INSERT INTO daily_visits (tanggal, jumlah) VALUES (?, 1)
                 ON DUPLICATE KEY UPDATE jumlah = jumlah + 1',
                [$hariIni]
            );

            $perangkat = Perangkat::deteksi($request->userAgent());

            DB::statement(
                'INSERT INTO kunjungan_perangkat (tanggal, perangkat, jumlah) VALUES (?, ?, 1)
                 ON DUPLICATE KEY UPDATE jumlah = jumlah + 1',
                [$hariIni, $perangkat]
            );

            // ID sesi Laravel dipakai sebagai penanda "satu pengunjung", bukan
            // alamat IP — jadi tidak ada data pribadi yang tersimpan. Baris
            // ini ditimpa terus tiap pengunjung yang sama memuat halaman
            // baru, jadi jumlah barisnya sama dengan jumlah pengunjung
            // aktif, bukan jumlah kunjungan.
            DB::statement(
                'INSERT INTO online_visitors (visitor_id, halaman, terakhir) VALUES (?, ?, ?)
                 ON DUPLICATE KEY UPDATE halaman = VALUES(halaman), terakhir = VALUES(terakhir)',
                [$request->session()->getId(), $request->path(), now()]
            );

            // Dibersihkan sesekali (1 dari 50 permintaan), bukan tiap kali —
            // cukup untuk menjaga tabel tetap kecil tanpa perlu tugas
            // terjadwal terpisah.
            if (random_int(1, 50) === 1) {
                DB::table('online_visitors')
                    ->where('terakhir', '<', now()->subMinutes(self::MENIT_ONLINE))
                    ->delete();
            }
        }

        return $response;
    }

    protected function layakDicatat(Request $request, Response $response): bool
    {
        if (! $request->isMethod('get')) {
            return false;
        }

        if ($response->getStatusCode() >= 400) {
            return false;
        }

        if ($request->ajax() || $request->wantsJson()) {
            return false;
        }

        // Jangan hitung kunjungan ke panel admin, unggahan Livewire, atau
        // berkas statis — yang ingin diketahui adalah pengunjung situs,
        // bukan aktivitas pengurus sendiri saat menyunting konten.
        return ! $request->is('admin*', 'livewire*', 'storage*', 'build*', 'up');
    }
}
