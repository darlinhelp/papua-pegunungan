@php
    $namaSitus = \App\Models\Setting::get('nama_lengkap', 'PKS Papua Pegunungan');
    $judul = trim($__env->yieldContent('judul'));
    $judulPenuh = $judul !== ''
        ? $judul . ' — DPW ' . $namaSitus
        : 'DPW ' . $namaSitus . ' — Berkhidmat untuk Tanah Pegunungan';
    $deskripsi = trim($__env->yieldContent('deskripsi'))
        ?: 'Situs resmi Dewan Pengurus Wilayah Partai Keadilan Sejahtera Provinsi Papua Pegunungan.';
    $gambar = trim($__env->yieldContent('gambar')) ?: asset('img/og-default.jpg');
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-pt-28">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0E2B24">

    <title>{{ $judulPenuh }}</title>
    <meta name="description" content="{{ $deskripsi }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="DPW {{ $namaSitus }}">
    <meta property="og:title" content="{{ $judulPenuh }}">
    <meta property="og:description" content="{{ $deskripsi }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $gambar }}">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="icon" href="{{ asset('img/favicon.svg') }}" type="image/svg+xml">

    {{-- Font situs.
         Keluarganya dipilih dari panel admin (Pengaturan → Identitas Situs).
         Nilainya dicetak sebagai variabel CSS, jadi mengganti font tidak
         perlu build ulang — cukup simpan pengaturannya.

         Dicetak dengan {!! !!} karena nilai stack CSS mengandung tanda kutip.
         Aman: isinya hanya berasal dari daftar tertutup di App\Support\Font,
         tidak pernah dari teks bebas. --}}
    @php
        $fontSitus  = \App\Support\Font::terpilih();
        $skalaTeks  = \App\Support\Font::skala();
        $sudut      = \App\Support\Sudut::nilai();
        $kop        = \App\Support\Kop::class;
        $warna      = \App\Support\Warna::class;

        $skalaJudulBeranda = \App\Support\Font::skalaJudulBeranda();
        $skalaJudulArtikel = \App\Support\Font::skalaJudulArtikel();
    @endphp

    @if ($fontSitus['google'])
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet"
              href="https://fonts.googleapis.com/css2?family={{ $fontSitus['google'] }}&display=swap">
    @endif

    <style>
        :root {
            --font-situs: {!! $fontSitus['stack'] !!};
            --skala-teks: {{ $skalaTeks }};
            --sudut: {{ $sudut }};

            /* Bilah kepala dan logo — diatur di panel admin. */
            --kop-lebar: {{ $kop::lebarCss() }};
            --kop-tinggi: {{ $kop::kopTinggi() }}px;
            --kop-tinggi-kecil: {{ $kop::kopTinggiKecil() }}px;
            --logo-tinggi: {{ $kop::logoTinggi() }}px;
            --logo-tinggi-kecil: {{ $kop::logoTinggiKecil() }}px;

            /* Warna situs. Dicetak "R G B" (bukan heks) supaya Tailwind bisa
               menambahkan transparansi lewat modifier seperti bg-jingga/50 —
               lihat rgb(var(--x) / <alpha-value>) di tailwind.config.js. */
            --warna-aksen: {{ $warna::rgb($warna::aksen()) }};
            --warna-aksen-gelap: {{ $warna::gelapkan($warna::aksen(), 0.85) }};
            --warna-aksen-lebih-gelap: {{ $warna::gelapkan($warna::aksen(), 0.72) }};
            --warna-latar: {{ $warna::rgb($warna::latar()) }};
            --warna-teks: {{ $warna::rgb($warna::teks()) }};

            /* Ukuran judul, terpisah untuk beranda dan halaman artikel. */
            --skala-judul-beranda: {{ $skalaJudulBeranda }};
            --skala-judul-artikel: {{ $skalaJudulArtikel }};
        }

        /* Seluruh tampilan memakai satuan rem, jadi menggeser ukuran dasar
           di sini membesarkan semua teks sekaligus — judul, isi, dan
           keterangan tetap seimbang satu sama lain. */
        html { font-size: calc(100% * var(--skala-teks)); }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('kepala')
</head>
<body class="min-h-dvh flex flex-col gaya-tautan-{{ \App\Support\Layout::gayaTautan() }}">

    <a href="#isi"
       class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[100]
              focus:bg-jingga focus:text-white focus:px-4 focus:py-2 focus:font-semibold">
        Langsung ke isi
    </a>

    <x-site-header />

    <main id="isi" class="flex-1">
        @yield('isi')
    </main>

    <x-site-footer />

    @stack('kaki')
</body>
</html>
