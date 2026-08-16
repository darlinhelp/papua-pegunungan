@php
    // Nilai dibaca dari state formulir yang sedang disunting ($get),
    // bukan dari basis data — supaya pratinjau mengikuti angka yang
    // sedang Anda coba, sebelum tombol Simpan ditekan.
    $logoTinggi       = (int) ($get('logo_tinggi') ?: \App\Support\Kop::LOGO_TINGGI);
    $logoTinggiKecil  = (int) ($get('logo_tinggi_kecil') ?: \App\Support\Kop::LOGO_TINGGI_KECIL);
    $kopTinggi        = (int) ($get('kop_tinggi') ?: \App\Support\Kop::KOP_TINGGI);
    $kopTinggiKecil   = (int) ($get('kop_tinggi_kecil') ?: \App\Support\Kop::KOP_TINGGI_KECIL);

    $berkasLogo = $get('logo_situs');
    $logoUrl = $berkasLogo
        ? (is_string($berkasLogo) ? \Illuminate\Support\Facades\Storage::disk('public')->url($berkasLogo) : null)
        : null;
    $logoUrl = $logoUrl ?: \App\Support\Kop::logoUrl();

    $namaSingkat = $get('nama_singkat') ?: 'Dewan Pengurus Wilayah';
    $namaLengkap = $get('nama_lengkap') ?: 'PKS Papua Pegunungan';
    $skalaNama   = \App\Support\Font::daftarUkuranNama()[$get('ukuran_nama_situs') ?: 'sedang']['skala'] ?? '1';
    $footerGelap = ($get('footer_tema') ?: 'gelap') === 'gelap';
@endphp

<div x-data="{ kecil: false }" class="border border-gray-200 dark:border-white/10 rounded-lg overflow-hidden">

    {{-- Sakelar sebelum/sesudah digulir --}}
    <div class="flex items-center gap-2 px-4 py-2.5 bg-gray-50 dark:bg-white/5
                border-b border-gray-200 dark:border-white/10">
        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Pratinjau:</span>

        <button type="button" @click="kecil = false"
                :class="!kecil ? 'bg-white dark:bg-gray-700 shadow-sm' : 'text-gray-500'"
                class="px-3 py-1 text-xs font-semibold rounded-md transition-colors">
            Sebelum digulir
        </button>

        <button type="button" @click="kecil = true"
                :class="kecil ? 'bg-white dark:bg-gray-700 shadow-sm' : 'text-gray-500'"
                class="px-3 py-1 text-xs font-semibold rounded-md transition-colors">
            Setelah digulir
        </button>
    </div>

    {{-- Bilah kepala tiruan --}}
    <div class="bg-white flex items-center px-6 transition-all duration-300"
         :style="`height: ${kecil ? {{ $kopTinggiKecil }} : {{ $kopTinggi }}}px`">

        <div class="flex items-center gap-3">
            <img src="{{ $logoUrl }}" alt=""
                 class="w-auto transition-all duration-300"
                 style="height: {{ $logoTinggi }}px"
                 :style="`height: ${kecil ? {{ $logoTinggiKecil }} : {{ $logoTinggi }}}px`">

            <span class="leading-tight">
                <span class="block text-[10px] font-semibold uppercase tracking-wider text-gray-400">
                    {{ $namaSingkat }}
                </span>
                <span class="block font-extrabold text-gray-900 tracking-tight"
                      style="font-size: calc(1em * {{ $skalaNama }})"
                      :class="kecil ? 'text-sm' : 'text-base'">
                    {{ $namaLengkap }}
                </span>
            </span>
        </div>
    </div>

    {{-- Cuplikan footer: memastikan logo tetap terlihat di tema yang dipilih --}}
    <div class="flex items-center gap-3 px-6 py-4 {{ $footerGelap ? 'bg-gray-900' : 'bg-gray-50' }}">
        <span class="{{ $footerGelap ? 'bg-white p-1.5' : '' }} rounded shrink-0">
            <img src="{{ $logoUrl }}" alt="" class="h-8 w-auto">
        </span>
        <span class="text-xs font-semibold {{ $footerGelap ? 'text-white' : 'text-gray-900' }}">
            Tampilan logo di footer ({{ $footerGelap ? 'tema gelap' : 'tema terang' }})
        </span>
    </div>
</div>

<p class="mt-2 text-xs text-gray-400">
    Pratinjau memperbarui setiap kali penggeser dilepas — bukan mengikuti gerakan jari
    secara langsung. Klik dua tombol di atas untuk membandingkan kedua ukuran.
</p>
