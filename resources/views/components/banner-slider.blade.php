@props([
    'banners',
    'jeda' => 6000,   // milidetik per spanduk
])

@if ($banners->isNotEmpty())
    @php
        $jumlah = $banners->count();

        // Rasio diatur dari panel admin. Kalau 'auto', tinggi mengikuti
        // gambar apa adanya — tidak ada bagian yang terpotong.
        $rasio        = \App\Support\Spanduk::rasio();
        $lebarAcuan   = \App\Support\Spanduk::LEBAR;
        $tinggiAcuan  = \App\Support\Spanduk::tinggi();
    @endphp

    @if ($rasio)
        {{-- Rasio layar besar. Ditulis di sini, bukan sebagai kelas Tailwind,
             karena nilainya berasal dari pengaturan dan tidak akan terbaca
             pemindai Tailwind saat build. --}}
        <style>
            @media (min-width: 640px) {
                .rasio-spanduk { aspect-ratio: {{ $rasio }} !important; }
            }
        </style>
    @endif

    <section class="bg-white"
             aria-label="Spanduk pengumuman"
             x-data="spanduk({{ $jumlah }}, {{ $jeda }})"
             @mouseenter="henti()" @mouseleave="mulai()"
             @focusin="henti()" @focusout="mulai()">

        {{-- Wadah spanduk sengaja lebih lebar dari isi halaman.
             pks.id menyajikan spanduk pada lebar 1340 px (terbaca dari
             parameter resize_w/1340 di alamat gambarnya), sementara kolom
             isi situs ini 1200 px. Tanpa pelebaran ini, spanduk tampil
             sekitar 12% lebih sempit dari aslinya. --}}
        <div class="mx-auto w-full max-w-spanduk px-0 sm:px-8 py-3 sm:py-4">
            <div class="relative group"
                 @touchstart.passive="sentuhMulai($event)"
                 @touchend.passive="sentuhSelesai($event)"
                 @keydown.arrow-left.prevent="sebelum()"
                 @keydown.arrow-right.prevent="berikut()"
                 tabindex="0"
                 role="region"
                 aria-roledescription="carousel"
                 :aria-label="`Spanduk ${aktif + 1} dari {{ $jumlah }}`">

                {{-- Rel geser --}}
                <div class="overflow-hidden">
                    <div class="flex transition-transform duration-700 ease-[cubic-bezier(.2,.7,.3,1)]"
                         :style="`transform: translateX(-${aktif * 100}%)`">
                        @foreach ($banners as $k => $banner)
                            <div class="w-full shrink-0"
                                 role="group"
                                 aria-roledescription="slide"
                                 aria-label="{{ $k + 1 }} dari {{ $jumlah }}"
                                 :aria-hidden="aktif !== {{ $k }}">

                                @php
                                    // Di layar kecil rasio dipatok 4:3 supaya spanduk lebar
                                    // tidak jadi pita tipis yang tak terbaca; di layar besar
                                    // barulah rasio pilihan admin berlaku.
                                    $gaya = $rasio
                                        ? 'aspect-ratio: 4 / 3'
                                        : null;
                                @endphp

                                @if ($banner->link)
                                    <a href="{{ $banner->link }}"
                                       class="block relative bingkai {{ $rasio ? 'rasio-spanduk' : '' }}"
                                       @if ($gaya) style="{{ $gaya }}" @endif
                                       :tabindex="aktif === {{ $k }} ? 0 : -1">
                                @else
                                    <div class="block relative bingkai {{ $rasio ? 'rasio-spanduk' : '' }}"
                                         @if ($gaya) style="{{ $gaya }}" @endif>
                                @endif

                                    <picture>
                                        <source media="(min-width: 640px)"
                                                srcset="{{ $banner->imageUrl() }}">
                                        <img src="{{ $banner->mobileImageUrl() }}"
                                             alt="{{ $banner->altText() }}"
                                             width="{{ $lebarAcuan }}"
                                             @if ($tinggiAcuan) height="{{ $tinggiAcuan }}" @endif
                                             loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                             fetchpriority="{{ $loop->first ? 'high' : 'auto' }}"
                                             decoding="async"
                                             @class([
                                                 'w-full',
                                                 // Tanpa rasio tetap: tinggi ikut gambar.
                                                 'h-auto' => ! $rasio,
                                                 // Dengan rasio tetap: gambar dipotong agar pas.
                                                 'object-cover h-full' => (bool) $rasio,
                                             ])>
                                    </picture>

                                    @if ($banner->subtitle)
                                        <span class="absolute inset-x-0 bottom-0 bg-gradient-to-t
                                                     from-arang-950/85 to-transparent p-5 pb-12 sm:p-8 sm:pb-14">
                                            <span class="block text-lg sm:text-2xl
                                                         font-extrabold text-white tracking-tight seimbang">
                                                {{ $banner->title }}
                                            </span>
                                            <span class="mt-1 block text-sm text-abu-200">
                                                {{ $banner->subtitle }}
                                            </span>
                                        </span>
                                    @endif

                                @if ($banner->link)
                                    </a>
                                @else
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                @if ($jumlah > 1)
                    {{-- Panah: tersembunyi sampai disentuh tetikus, selalu tampil di layar sentuh --}}
                    <button type="button" @click="sebelum()" aria-label="Spanduk sebelumnya"
                            class="absolute left-3 top-1/2 -translate-y-1/2 grid place-items-center
                                   h-11 w-11 bg-arang-950/55 text-white backdrop-blur-sm
                                   opacity-0 group-hover:opacity-100 focus-visible:opacity-100
                                   max-sm:opacity-100 hover:bg-jingga transition-all duration-200">
                        <svg viewBox="0 0 24 24" class="h-5 w-5" aria-hidden="true">
                            <path d="M15 5l-7 7 7 7" fill="none" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <button type="button" @click="berikut()" aria-label="Spanduk berikutnya"
                            class="absolute right-3 top-1/2 -translate-y-1/2 grid place-items-center
                                   h-11 w-11 bg-arang-950/55 text-white backdrop-blur-sm
                                   opacity-0 group-hover:opacity-100 focus-visible:opacity-100
                                   max-sm:opacity-100 hover:bg-jingga transition-all duration-200">
                        <svg viewBox="0 0 24 24" class="h-5 w-5" aria-hidden="true">
                            <path d="M9 5l7 7-7 7" fill="none" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    {{-- Indikator: batang aktif terisi mengikuti sisa waktu tayang --}}
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2">
                        @foreach ($banners as $k => $banner)
                            <button type="button" @click="ke({{ $k }})"
                                    aria-label="Tampilkan spanduk {{ $k + 1 }}"
                                    :aria-current="aktif === {{ $k }}"
                                    class="h-1.5 bg-white/35 transition-all duration-300"
                                    :class="aktif === {{ $k }} ? 'w-10' : 'w-3 hover:bg-white/60'">
                                <template x-if="aktif === {{ $k }}">
                                    <span class="block h-full bg-jingga origin-left"
                                          :class="jalan ? 'animate-isi' : 'w-full'"
                                          :style="`animation-duration: ${jeda}ms`"></span>
                                </template>
                            </button>
                        @endforeach

                        {{-- Tombol jeda: wajib ada untuk konten yang bergerak sendiri --}}
                        <button type="button" @click="jalan ? henti(true) : mulai(true)"
                                :aria-label="jalan ? 'Jeda pergantian spanduk' : 'Lanjutkan pergantian spanduk'"
                                class="ml-2 grid place-items-center h-6 w-6 bg-arang-950/55
                                       text-white hover:bg-jingga transition-colors">
                            <svg x-show="jalan" viewBox="0 0 16 16" class="h-3 w-3 fill-current"
                                 aria-hidden="true">
                                <path d="M5 3h2v10H5zM9 3h2v10H9z"/>
                            </svg>
                            <svg x-show="!jalan" x-cloak viewBox="0 0 16 16" class="h-3 w-3 fill-current"
                                 aria-hidden="true">
                                <path d="M5 3.5v9l8-4.5z"/>
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- Pemberitahuan untuk pembaca layar saat spanduk berganti --}}
        <p class="sr-only" aria-live="polite" x-text="`Spanduk ${aktif + 1} dari {{ $jumlah }}`"></p>
    </section>
@endif
