# DPW PKS Papua Pegunungan — situs wilayah

Berkas ini berisi **kode aplikasi**, bukan proyek Laravel utuh. Tidak ada `vendor/`,
`composer.json`, maupun `artisan` di dalamnya — jadi kalau folder ini langsung dibuka
di browser, hasilnya pasti error. Laravel-nya dibuat dulu, baru kode ini disalin masuk.

---

## Cara cepat (otomatis)

1. Ekstrak zip ke `C:\laragon\www` — akan terbentuk `C:\laragon\www\pkspapeg`
2. Nyalakan Laragon, klik tombol **Start All**
3. Klik **Terminal** di Laragon (jangan pakai CMD biasa — PATH-nya berbeda)
4. Jalankan:

```
cd C:\laragon\www\pkspapeg
pasang.bat
```

Skrip itu memeriksa versi PHP dan ekstensinya, membuat proyek Laravel di
`C:\laragon\www\papua-pegunungan`, menyalin seluruh kode, memasang paket npm,
menulis `.env`, membuat basis data, menjalankan migrasi beserta isi contoh, dan
membangun CSS/JS. Berhenti dengan pesan jelas kalau ada yang kurang.

Setelah selesai: klik kanan ikon Laragon → **Reload**, buka
`http://papua-pegunungan.test`.

---

## Cara manual

Kalau lebih suka menjalankan sendiri, dari Laragon Terminal:

```bash
cd C:\laragon\www
composer create-project laravel/laravel papua-pegunungan
```

Salin isi folder `pkspapeg` ke `papua-pegunungan`, timpa yang bentrok. Yang tertimpa
hanya `routes/web.php`, `app/Providers/AppServiceProvider.php`,
`database/seeders/DatabaseSeeder.php`, `resources/css/app.css`, `resources/js/app.js`,
dan `vite.config.js`.

Tailwind harus **versi 3**, bukan v4 — Laravel 12 bawaannya v4 dengan cara konfigurasi
yang berbeda, sedangkan `tailwind.config.js` di sini ditulis untuk v3:

```bash
cd papua-pegunungan
npm uninstall tailwindcss @tailwindcss/vite
npm install -D tailwindcss@^3.4 postcss autoprefixer
npm install alpinejs @alpinejs/collapse
```

Buat basis data `pks_papeg` lewat Laragon → Database, lalu sunting `.env`:

```env
APP_NAME="DPW PKS Papua Pegunungan"
APP_URL=http://papua-pegunungan.test
APP_LOCALE=id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pks_papeg
DB_USERNAME=root
DB_PASSWORD=
```

```bash
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run build
```

---

## Kalau ada yang tidak beres

**Halaman putih atau error 500.** Cek `storage/logs/laravel.log`. Paling sering karena
`php artisan key:generate` belum dijalankan.

**Tampilan polos tanpa gaya.** `npm run build` belum jalan, atau gagal. Ulangi di folder
proyek. Selama menyunting tampilan, pakai `npm run dev` dan biarkan terminalnya terbuka.

**`papua-pegunungan.test` tidak bisa dibuka.** Aktifkan Laragon → Preferences → General →
*Auto virtual hosts*, lalu klik kanan ikon Laragon → Reload. Kalau masih belum,
jalankan Laragon sebagai administrator sekali supaya bisa menulis ke berkas `hosts`.

**`storage:link` gagal.** Windows butuh izin khusus untuk membuat symlink. Tutup Laragon,
klik kanan ikonnya, pilih *Run as administrator*, lalu ulangi perintahnya. Tanpa ini
gambar yang diunggah lewat panel admin tidak akan muncul.

**`SQLSTATE[HY000] [1049] Unknown database`.** Basis data `pks_papeg` belum dibuat, atau
namanya beda dengan yang di `.env`.

**"The script tried to call a method on an incomplete object".** Ada objek tersimpan di
cache yang gagal dibaca ulang. Jalankan `php artisan optimize:clear`. Kalau muncul lagi,
berarti masih ada kode yang menyimpan model ke cache — cari `Cache::remember` yang isinya
hasil query.

**`Class "PDO" not found`.** Ekstensi `pdo_mysql` belum aktif. Laragon → Menu → PHP →
php.ini, hapus tanda `;` di depan `extension=pdo_mysql`, lalu Reload.

**Peringatan "Relasi dimuat malas" di log.** Bukan error — itu catatan bahwa ada query
N+1 yang bisa dioptimalkan. Halaman tetap tampil normal.

---

## Isi contoh

Sudah masuk otomatis: 16 berita, 3 opini, 4 kutipan media, 3 spanduk, album foto, video,
publikasi, dan 18 halaman statis. Semuanya **karangan untuk uji tampilan**. Hapus dengan
`php artisan migrate:fresh` sebelum situs dipakai sungguhan.

Akun contoh: `admin@papua-pegunungan.pks.id` / `rahasia123` — ganti sebelum daring.

---

## Panel admin (Filament v5)

Frontend jalan tanpa ini, tapi pengurus butuh cara menambah berita sendiri.

### Pasang

Dari **dalam** folder proyek Laravel, lewat Laragon Terminal:

```
cd C:\laragon\www\papua-pegunungan
pasang-admin.bat
```

Skrip itu memeriksa ekstensi `intl`, memasang Filament, menyiapkan panel,
membersihkan cache, dan meminta Anda membuat akun admin.

Manual, kalau lebih suka:

```bash
composer require filament/filament:"~5.0" -W
php artisan filament:install --panels
php artisan make:filament-user
```

Pakai `~5.0`, bukan `^5.0` — PowerShell membuang karakter `^` dan Composer akan
memasang versi 5.0.0 persis, bukan yang terbaru.

`filament:install` menimpa `app/Providers/Filament/AdminPanelProvider.php` dengan
versi bawaannya. Salin ulang berkas dari folder `pkspapeg` setelahnya, supaya warna
oranye PKS, kelompok navigasi, dan nama panel kembali terpasang.

Panel ada di `/admin`.

### Apa saja yang bisa dikelola

| Menu | Isi |
|---|---|
| **Berita & Artikel** | Rich editor, slug otomatis, sampul, jadwal terbit, tab Draf/Terjadwal/Pilihan |
| **Spanduk** | Dua ukuran gambar (lebar + ponsel), jadwal tayang, urutan geser |
| **Kategori** | Menentukan tulisan masuk blok Berita, Opini, atau Kutipan Media |
| **Album Foto** | Unggah sampai 40 foto sekaligus, keterangan bisa disunting langsung di tabel |
| **Video** | Tempel tautan YouTube apa pun — ID-nya diambil otomatis |
| **Publikasi** | Sampul 3:4 dan berkas PDF sampai 20 MB |
| **Halaman Statis** | Rich editor untuk profil, AD/ART, halaman DPD |
| **Pengguna** | Akun pengurus; tidak bisa menghapus akun sendiri |
| **Identitas Situs** | Alamat, telepon, surel yang tampil di footer |

Dasbor menampilkan jumlah tulisan terbit, draf, terjadwal, spanduk aktif, dan daftar
tulisan yang terakhir disunting.

### Catatan tentang rich editor

Gambar yang disisipkan di tengah artikel disimpan di `storage/app/public/artikel/lampiran`.
Kalau gambar tidak muncul setelah diunggah, `php artisan storage:link` belum berhasil —
lihat bagian pemecahan masalah di atas.

Isi artikel ditampilkan dengan `{!! !!}` di Blade, artinya HTML-nya dijalankan apa adanya.
Ini memang perlu supaya format tulisan tampil benar, tapi konsekuensinya: **isi artikel
hanya seaman orang yang boleh menulisnya**. Beri akses admin hanya kepada pengurus yang
dipercaya.

### Tailwind

Filament v5 mensyaratkan Tailwind v4, sedangkan situs ini pakai Tailwind v3. Keduanya
tidak bertabrakan: Filament membawa CSS-nya sendiri yang sudah jadi, terpisah dari
`resources/css/app.css` milik situs. Yang tidak bisa dilakukan tanpa Tailwind v4 adalah
membuat tema kustom untuk panelnya — dan itu tidak dibutuhkan di sini.

## Yang perlu Anda ganti

| Berkas | Isi sekarang | Ganti dengan |
|---|---|---|
| `public/img/logo-dpw.svg` | tanda sementara | logo resmi dari <https://pks.id/download/> |
| `public/img/og-default.jpg` | **belum ada** | gambar 1200×630 px untuk pratinjau media sosial |
| `config/menu.php` | struktur menu contoh | tautan sebenarnya |
| Kutipan di beranda | teks contoh | kutipan asli Ketua DPW |
| Alamat & kontak di footer | contoh | data sekretariat, lewat menu Identitas Situs di panel admin |

## Struktur yang dibuat

```
app/Models/           Post, Category, Banner, Album, Photo, Video, Publication, Page, Setting, User
app/Http/Controllers/ Home, Post, Gallery, Video, Publication, Page, Search
app/Filament/
  Resources/          Post, Category, Banner, Album (+ pengelola foto), Video, Publication, Page, User
  Pages/              PengaturanSitus
  Widgets/            RingkasanKonten, TulisanTerbaru
app/Providers/Filament/AdminPanelProvider.php
config/menu.php       susunan mega menu — diedit di sini, bukan di Blade
lang/id.json          terjemahan antarmuka panel
resources/views/
  components/         site-header, site-footer, post-card, banner-slider, section-head, page-hero
  home.blade.php      beranda
```

## Slider spanduk

Komponen: `resources/views/components/banner-slider.blade.php`, logikanya di
`resources/js/app.js` (`Alpine.data('spanduk')`).

```blade
<x-banner-slider :banners="$banners" />          {{-- 6 detik per spanduk --}}
<x-banner-slider :banners="$banners" :jeda="9000" />
```

Posisinya sekarang di atas hero, sama seperti pks.id. Untuk memindahkannya ke bawah
hero, geser satu baris pemanggilan itu di `resources/views/home.blade.php`.

Ukuran gambar: **1340 × 400 px** untuk layar lebar, dan **800 × 600 px** untuk kolom
`image_mobile_path`. Kolom kedua opsional, tapi tanpa itu spanduk lebar akan terpotong
di ponsel. Atribut `width`/`height` sudah dipasang di tag `<img>` supaya halaman tidak
melompat saat gambar selesai dimuat.

Kolom `starts_at` dan `ends_at` mengatur jadwal tayang — spanduk kegiatan bisa
dijadwalkan berhenti sendiri tanpa perlu dihapus manual.

## Catatan teknis

**Beranda tidak menyimpan objek Eloquent di cache.** Ini disengaja. Menyimpan model ke
cache berarti objeknya di-serialize lalu dibaca ulang, dan begitu pembacaan gagal, Blade
menerima objek tidak utuh (`__PHP_Incomplete_Class`) yang langsung memunculkan
"tried to call a method on an incomplete object". Beranda menjalankan sekitar sepuluh
query ringan yang semuanya sudah pakai `with()` dan `take()` — cukup cepat tanpa cache.

Kalau nanti lalu lintasnya besar, yang di-cache adalah **keluaran HTML-nya**, bukan
modelnya. Paket `spatie/laravel-responsecache` menangani ini dengan benar.

**Deteksi N+1.** `Model::preventLazyLoading` aktif di luar produksi. Kalau muncul error
`Attempted to lazy load`, itu memang disengaja: tambahkan `with()` pada query terkait.

**Tanggal berbahasa Indonesia.** Lewat `Carbon::setLocale('id')` dan `translatedFormat()`.
Jangan pakai `format()` untuk tanggal yang tampil ke pengguna — hasilnya akan Inggris.

**Halaman statis** beralamat `/halaman/{slug}`, bukan di akar. Ini disengaja: rute
tangkap-semua di akar akan menelan alamat lain, termasuk `/admin` milik Filament.

**Pencarian** masih `LIKE` biasa; cukup sampai beberapa ribu tulisan. Di atas itu,
pasang Laravel Scout + Meilisearch.

## Sebelum daring

- Subdomain `papua-pegunungan.pks.id` hanya bisa dibuat oleh pengelola DNS `pks.id`,
  yaitu Bidang KOMDIGI DPP. Ajukan lewat DPW sebelum menyiapkan server.
- `APP_DEBUG=false` dan `APP_ENV=production` di `.env` server.
- `php artisan optimize` serta `npm run build` saat deploy.
