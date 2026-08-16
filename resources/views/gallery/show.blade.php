@extends('layouts.app')

@section('judul', $album->title)
@section('deskripsi', $album->description)

@section('isi')
    <x-page-hero eyebrow="Album foto" ikon="foto" :judul="$album->title" :deskripsi="$album->description" />

    <section class="shell py-16"
             x-data="{ buka: false, aktif: 0, foto: {{ $album->photos->count() }} }"
             @keydown.window.arrow-right="if (buka) aktif = (aktif + 1) % foto"
             @keydown.window.arrow-left="if (buka) aktif = (aktif - 1 + foto) % foto">

        @if ($album->photos->isEmpty())
            <x-empty-state judul="Album ini masih kosong" :aksi="route('gallery.index')"
                           aksiLabel="Lihat album lain" />
        @else
            <div class="grid gap-3 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @foreach ($album->photos as $k => $foto)
                    <button type="button" @click="aktif = {{ $k }}; buka = true"
                            class="group block bingkai overflow-hidden bg-abu-100 aspect-square">
                        <img src="{{ $foto->url() }}" alt="{{ $foto->caption }}"
                             loading="lazy" decoding="async"
                             class="h-full w-full object-cover transition-transform duration-500
                                    group-hover:scale-105">
                    </button>
                @endforeach
            </div>

            {{-- Penampil foto layar penuh --}}
            <div x-show="buka" x-cloak class="fixed inset-0 z-[60] bg-arang-950/95"
                 x-transition.opacity @click.self="buka = false">
                <button type="button" @click="buka = false" aria-label="Tutup"
                        class="absolute top-5 right-5 p-3 text-white/70 hover:text-white">
                    <svg viewBox="0 0 24 24" class="h-6 w-6" aria-hidden="true">
                        <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2"
                              stroke-linecap="round"/>
                    </svg>
                </button>

                @foreach ($album->photos as $k => $foto)
                    <figure x-show="aktif === {{ $k }}" x-cloak
                            class="absolute inset-0 grid place-items-center p-6 sm:p-16 pointer-events-none">
                        <img src="{{ $foto->url() }}" alt="{{ $foto->caption }}"
                             class="max-h-full max-w-full object-contain">
                        @if ($foto->caption)
                            <figcaption class="absolute bottom-6 inset-x-0 text-center text-sm
                                               text-abu-300 px-6">
                                {{ $foto->caption }}
                            </figcaption>
                        @endif
                    </figure>
                @endforeach

                <button type="button" @click="aktif = (aktif - 1 + foto) % foto" aria-label="Foto sebelumnya"
                        class="absolute left-3 top-1/2 -translate-y-1/2 p-4 text-white/70 hover:text-jingga">
                    <svg viewBox="0 0 24 24" class="h-7 w-7" aria-hidden="true">
                        <path d="M15 5l-7 7 7 7" fill="none" stroke="currentColor" stroke-width="2"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <button type="button" @click="aktif = (aktif + 1) % foto" aria-label="Foto berikutnya"
                        class="absolute right-3 top-1/2 -translate-y-1/2 p-4 text-white/70 hover:text-jingga">
                    <svg viewBox="0 0 24 24" class="h-7 w-7" aria-hidden="true">
                        <path d="M9 5l7 7-7 7" fill="none" stroke="currentColor" stroke-width="2"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        @endif
    </section>
@endsection
