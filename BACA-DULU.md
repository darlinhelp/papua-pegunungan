# Tambalan: menu utama bisa dikelola dari panel admin

Timpa 11 berkas ini, lalu:

```
php artisan migrate
php artisan db:seed --class=Database\\Seeders\\MenuSeeder
php artisan optimize:clear
```

**Perhatian urutannya:**
`migrate` dulu (membuat tabel baru), baru `db:seed --class=MenuSeeder`
(mengisi menu bawaan yang sama persis dengan yang sekarang tampil). Jangan
pakai `migrate:fresh` — itu akan menghapus semua berita yang sudah Anda
tulis.

Kalau perintah seed di atas gagal karena tanda `\` di Windows, coba:

```
php artisan db:seed --class="Database\Seeders\MenuSeeder"
```

atau paling aman, lewat Tinker:

```
php artisan tinker
>>> (new \Database\Seeders\MenuSeeder)->run();
>>> exit
```

Tidak perlu build ulang — ini murni PHP dan basis data.

---

## Hasil audit yang Anda minta

Saya periksa seluruh situs untuk mencari tampilan yang belum punya
pengaturan. Ditemukan dua:

**1. Menu utama** (`config/menu.php`) — ini yang Anda sebut, dan sudah
diperbaiki lewat tambalan ini.

**2. Lima kartu ajakan di bagian bawah beranda** (Gabung, Sumbangan,
Website, Info Publik, Unduh) — teksnya masih tertulis langsung di
`home.blade.php`. Ini **belum** saya kerjakan di tambalan ini, supaya
menu bisa selesai dulu tanpa tercampur perubahan lain. Beri tahu saya
kalau ingin itu juga dibuatkan pengaturannya — pola yang sama seperti menu
ini bisa dipakai ulang.

---

## Yang bisa dikelola sekarang

**Menu Utama** (grup Halaman di sidebar panel admin, sejajar dengan Halaman
Statis). Ada dua tingkat:

**Kolom menu** — daftar utama, mis. "Seputar DPW", "Sikap & Kebijakan". Bisa
ditambah, dihapus, diganti nama, diurutkan (geser di tabel), dan ditandai
"Kolom lebar" untuk yang isinya banyak (seperti DPD Kabupaten, yang tersusun
dua baris).

**Tautan di dalam kolom** — klik "Kelola tautan" pada satu kolom untuk
membuka daftar isinya. Tiap tautan punya nama, alamat tujuan, sakelar "buka
di tab baru", dan urutan yang bisa digeser.

### Format alamat tujuan
Tiga bentuk yang dikenali, sama seperti sebelumnya di `config/menu.php`:
- `/halaman/profil-dpw` — halaman di situs ini
- `route:gallery.index` — rute baku Laravel (dari daftar di `routes/web.php`)
- `https://daftar.pks.id` — tautan ke situs lain (aktifkan "buka di tab baru")

---

## Cara kerjanya, dan kenapa risikonya kecil

Saya tidak menulis ulang `site-header.blade.php` atau `site-footer.blade.php`
secara besar-besaran. Kedua berkas itu sebelumnya membaca menu lewat satu
baris:

```php
$menu = config('menu');
```

Saya ganti **hanya baris itu** dengan query ke basis data yang menghasilkan
bentuk array persis sama — jadi seluruh markup mega menu, laci ponsel, dan
footer yang sudah ada **tidak disentuh sama sekali**. Ini mengurangi risiko
dibanding menulis ulang tampilannya dari nol.

`config/menu.php` sendiri saya biarkan ada, hanya sudah tidak dipakai lagi —
aman diabaikan, tidak mengganggu apa pun.

---

## Kalau menu tidak berubah setelah disunting di panel

Sama seperti pengaturan lain, ini seharusnya langsung tampil tanpa cache
khusus — tapi kalau tidak, jalankan `php artisan optimize:clear` sekali
lagi.
