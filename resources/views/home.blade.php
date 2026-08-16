@extends('layouts.app')

@section('deskripsi', 'Kabar, sikap, dan kegiatan Dewan Pengurus Wilayah Partai Keadilan Sejahtera Provinsi Papua Pegunungan.')

@php
    // Blok mana yang tampil di beranda, diatur dari Pengaturan → Layout.
    $blokAktif = \App\Support\Layout::blokAktif();
@endphp

@section('isi')

    {{-- ══════════ Spanduk pengumuman ══════════ --}}
    <x-banner-slider :banners="$banners" />

    {{-- ══════════ Kanal resmi ══════════
         Deretan media sosial tepat di bawah spanduk, mengikuti pola DPP. --}}
    @php $sosial = \App\Support\Sosial::aktif(); @endphp

    @if ($sosial)
        <section class="border-b border-abu-200 bg-white">
            <div class="shell py-4 flex flex-wrap items-center justify-center gap-x-3 gap-y-2">
                <span class="label text-abu-400 mr-1">Kanal resmi kami</span>
                @foreach ($sosial as $kanal)
                    <a href="{{ $kanal['url'] }}" target="_blank" rel="noopener"
                       title="{{ $kanal['nama'] }}" aria-label="{{ $kanal['nama'] }}"
                       class="grid place-items-center h-9 w-9 border border-abu-200 text-abu-500
                              hover:text-white hover:bg-jingga hover:border-jingga transition-colors">
                        <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                            <path d="{{ $kanal['ikon'] }}"/>
                        </svg>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ══════════ Kabar DPW: satu berita besar, tiga judul di sampingnya ══════════ --}}
    @if (in_array('kabar', $blokAktif))
    <x-news-block
        judul="Kabar DPW"
        ikon="berita"
        :posts="$kabar"
        :href="url('/kategori/kabar-dpw')"
        hrefLabel="Berita lainnya" />
    @endif

    {{-- ══════════ Berita pilihan ══════════ --}}
    @if ($pilihan->isNotEmpty() && in_array('pilihan', $blokAktif))
        <section class="bg-abu-50 py-12">
            <div class="shell">
                <x-section-head judul="Berita Pilihan" ikon="bintang"
                                :href="route('posts.featured')" hrefLabel="Pilihan lainnya" />

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($pilihan as $p)
                        <x-post-card :post="$p" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ══════════ Foto & Video ══════════ --}}
    @if (($album || $video->isNotEmpty()) && in_array('foto_video', $blokAktif))
        <section class="py-12">
            <div class="shell grid gap-10 lg:grid-cols-2">

                @if ($album)
                    <div>
                        <x-section-head judul="Galeri Foto" ikon="foto"
                                        :href="route('gallery.index')" hrefLabel="Foto lainnya" />

                        <a href="{{ route('gallery.show', $album) }}" class="group block">
                            <div class="bingkai overflow-hidden bg-abu-100 aspect-[16/9]">
                                <img src="{{ $album->coverUrl() }}" alt="" width="1200" height="675"
                                     loading="lazy" decoding="async"
                                     class="h-full w-full object-cover transition-transform
                                            duration-500 group-hover:scale-[1.03]">
                            </div>
                            <h3 class="mt-4 text-lg font-bold leading-snug text-arang seimbang">
                                <span class="tautan-judul">{{ $album->title }}</span>
                            </h3>
                            <p class="mt-1.5 text-xs text-abu-500">
                                {{ $album->photos->count() }} foto
                                @if ($album->location) &middot; {{ $album->location }} @endif
                            </p>
                        </a>

                        @if ($album->photos->count() > 1)
                            <div class="mt-3 grid grid-cols-4 gap-2">
                                @foreach ($album->photos->skip(1)->take(4) as $foto)
                                    <a href="{{ route('gallery.show', $album) }}"
                                       class="block bingkai overflow-hidden bg-abu-100 aspect-square">
                                        <img src="{{ $foto->url() }}" alt="" width="300" height="300"
                                             loading="lazy" decoding="async"
                                             class="h-full w-full object-cover hover:opacity-80
                                                    transition-opacity">
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                @if ($video->isNotEmpty())
                    <div>
                        <x-section-head judul="Video" ikon="video"
                                        :href="route('videos.index')" hrefLabel="Video lainnya" />

                        @php $videoUtama = $video->first(); @endphp

                        <a href="{{ route('videos.show', $videoUtama) }}" class="group block">
                            <div class="relative bingkai overflow-hidden bg-abu-100 aspect-[16/9]">
                                <img src="{{ $videoUtama->thumbnailUrl('hqdefault') }}" alt=""
                                     width="480" height="360" loading="lazy" decoding="async"
                                     class="h-full w-full object-cover transition-transform
                                            duration-500 group-hover:scale-[1.03]">
                                <span class="absolute inset-0 grid place-items-center">
                                    <span class="grid place-items-center h-14 w-14 bg-jingga
                                                 transition-transform group-hover:scale-110">
                                        <svg viewBox="0 0 16 16" class="h-5 w-5 fill-white"
                                             aria-hidden="true">
                                            <path d="M5 3.5v9l8-4.5z"/>
                                        </svg>
                                    </span>
                                </span>
                            </div>
                            <h3 class="mt-4 text-lg font-bold leading-snug text-arang seimbang">
                                <span class="tautan-judul">{{ $videoUtama->title }}</span>
                            </h3>
                            <p class="mt-1.5 text-xs text-abu-500">
                                {{ $videoUtama->published_at?->translatedFormat('j F Y') }}
                            </p>
                        </a>

                        @if ($video->count() > 1)
                            <div class="mt-4 border-t border-abu-200">
                                @foreach ($video->skip(1) as $v)
                                    <a href="{{ route('videos.show', $v) }}"
                                       class="group flex gap-3 py-3 border-b border-abu-200">
                                        <span class="shrink-0 w-24 aspect-video bingkai overflow-hidden bg-abu-100">
                                            <img src="{{ $v->thumbnailUrl('mqdefault') }}" alt=""
                                                 width="320" height="180" loading="lazy" decoding="async"
                                                 class="h-full w-full object-cover">
                                        </span>
                                        <span class="min-w-0 text-sm font-semibold leading-snug
                                                     text-arang seimbang">
                                            <span class="tautan-judul">{{ $v->title }}</span>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- ══════════ Kabar DPD ══════════ --}}
    @if (in_array('dpd', $blokAktif))
    <x-news-block
        judul="Kabar DPD Kabupaten"
        ikon="peta"
        :posts="$dpd"
        :href="url('/kategori/kabar-dpd')"
        hrefLabel="Kabar DPD lainnya"
        :latar="true" />
    @endif

    {{-- ══════════ Opini ══════════ --}}
    @if ($opini->isNotEmpty() && in_array('opini', $blokAktif))
        <section class="py-12">
            <div class="shell">
                <x-section-head judul="Opini" ikon="gagasan"
                                :href="url('/kategori/opini')" hrefLabel="Opini lainnya" />

                <div class="grid gap-8 md:grid-cols-3">
                    @foreach ($opini as $p)
                        <article class="group border-t-2 border-jingga pt-4">
                            <a href="{{ route('posts.show', $p) }}" class="block">
                                <h3 class="text-lg font-bold leading-snug text-arang seimbang">
                                    <span class="tautan-judul">{{ $p->title }}</span>
                                </h3>
                                <p class="mt-2 text-sm text-abu-600 leading-relaxed">
                                    {{ $p->summary(140) }}
                                </p>
                                <p class="mt-3 text-xs text-abu-500">
                                    {{ $p->author_name ?: 'Redaksi DPW' }}
                                    &middot; {{ $p->published_at?->translatedFormat('j F Y') }}
                                </p>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ══════════ Kutipan media ══════════ --}}
    @if (in_array('media', $blokAktif))
    <x-news-block
        judul="Kutipan Media"
        ikon="kutipan"
        :posts="$media"
        :href="url('/kategori/kutipan-media')"
        hrefLabel="Kutipan lainnya"
        :latar="true" />
    @endif

    {{-- ══════════ Publikasi digital ══════════ --}}
    @if ($terbitan->isNotEmpty() && in_array('publikasi', $blokAktif))
        <section class="py-12">
            <div class="shell">
                <x-section-head judul="Publikasi Digital" ikon="buku"
                                :href="route('publications.index')"
                                hrefLabel="Publikasi lainnya" />

                <div class="grid gap-6 grid-cols-2 lg:grid-cols-4">
                    @foreach ($terbitan as $t)
                        <a href="{{ route('publications.show', $t) }}" class="group block">
                            <div class="bingkai overflow-hidden bg-abu-100 aspect-[3/4] border border-abu-200
                                        transition-shadow group-hover:shadow-angkat">
                                <img src="{{ $t->coverUrl() }}" alt="" width="600" height="800"
                                     loading="lazy" decoding="async"
                                     class="h-full w-full object-cover transition-transform
                                            duration-500 group-hover:scale-[1.03]">
                            </div>
                            @if ($t->series)
                                <p class="mt-3 label text-jingga">{{ $t->series }}</p>
                            @endif
                            <p class="mt-1 text-sm font-bold leading-snug text-arang seimbang">
                                <span class="tautan-judul">{{ $t->edition ?: $t->title }}</span>
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ══════════ Kutipan pengurus ══════════ --}}
    <section class="bg-arang-950 text-white">
        <div class="shell py-14 lg:py-16">
            <figure class="max-w-4xl mx-auto text-center">
                <svg viewBox="0 0 40 32" class="h-7 w-9 fill-jingga mx-auto mb-6" aria-hidden="true">
                    <path d="M0 32V18C0 8 5 2 15 0l2 5c-6 2-9 5-9 10h8v17H0zm22 0V18c0-10 5-16 15-18l2 5c-6 2-9 5-9 10h8v17H22z"/>
                </svg>
                <blockquote class="text-xl sm:text-2xl lg:text-[1.75rem] font-semibold
                                   leading-[1.45] seimbang">
                    Berkhidmat di tanah pegunungan berarti hadir di honai, di kebun, dan di jalan
                    yang belum beraspal — bukan hanya di ruang rapat.
                </blockquote>
                <figcaption class="mt-7">
                    <span class="block font-bold text-jingga">Ketua DPW PKS Papua Pegunungan</span>
                    <span class="block mt-1 text-sm text-abu-400">
                        Musyawarah Kerja Wilayah, Wamena
                    </span>
                </figcaption>
            </figure>
        </div>
    </section>

    {{-- ══════════ Lima ajakan bertindak ══════════
         Isinya diatur lewat Pengaturan → Identitas Situs → Kartu Ajakan. --}}
    <section class="shell py-12">
        <div class="grid gap-px bg-abu-200 sm:grid-cols-2 lg:grid-cols-5 border border-abu-200">
            @php $ajakan = \App\Support\Ajakan::daftar(); @endphp

            @foreach ($ajakan as $kartu)
                @php
                    $href = $kartu['luar'] ? $kartu['href'] : url($kartu['href']);
                @endphp
                <a href="{{ $href }}"
                   @if ($kartu['luar']) target="_blank" rel="noopener" @endif
                   class="group flex items-start gap-4 bg-white p-6 hover:bg-jingga transition-colors duration-200">
                    @if ($kartu['ikon'] ?? null)
                        <span class="grid place-items-center h-11 w-11 shrink-0 rounded-full
                                     bg-jingga/10 text-jingga transition-colors duration-200
                                     group-hover:bg-white/20 group-hover:text-white">
                            <x-ikon :nama="$kartu['ikon']" kelas="h-5 w-5" />
                        </span>
                    @endif
                    <span>
                        <p class="text-lg font-extrabold tracking-tight leading-tight
                                  text-abu-500 group-hover:text-white/70 transition-colors">
                            {{ $kartu['baris1'] }}
                            <span class="block text-arang group-hover:text-white transition-colors">
                                {{ $kartu['baris2'] }}
                            </span>
                        </p>
                        <p class="mt-3 text-xs leading-relaxed text-abu-500
                                  group-hover:text-white/90 transition-colors">
                            {{ $kartu['isi'] }}
                        </p>
                    </span>
                </a>
            @endforeach
        </div>
    </section>

@endsection
