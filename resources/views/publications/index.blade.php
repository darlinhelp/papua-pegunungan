@extends('layouts.app')

@section('judul', 'Publikasi Digital')

@section('isi')
    <x-page-hero eyebrow="Bacaan kader" judul="Publikasi Digital" ikon="buku"
                 deskripsi="Buletin, majalah, dan materi kaderisasi yang bisa dibaca langsung di sini." />

    <section class="shell py-16">
        @if ($publications->isEmpty())
            <x-empty-state judul="Belum ada terbitan" :aksi="route('home')" />
        @else
            <div class="grid gap-8 grid-cols-2 lg:grid-cols-4">
                @foreach ($publications as $t)
                    {{-- Seluruh kartu satu tautan menuju halaman baca.
                         Tombol unduh sengaja tidak ditaruh di sini: tautan di
                         dalam tautan bukan HTML yang sah, dan unduhannya sudah
                         tersedia di halaman baca. --}}
                    <a href="{{ route('publications.show', $t) }}" class="group block">
                        <div class="relative bingkai overflow-hidden bg-white shadow-kartu
                                    aspect-[3/4] transition-shadow group-hover:shadow-angkat">
                            <img src="{{ $t->coverUrl() }}" alt="" width="600" height="800"
                                 loading="lazy" decoding="async"
                                 class="h-full w-full object-cover transition-transform
                                        duration-500 group-hover:scale-[1.03]">

                            <span class="absolute inset-0 grid place-items-center bg-arang-950/55
                                         opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="inline-flex items-center gap-2 bg-jingga px-4 py-2
                                             text-xs font-semibold text-white"
                                      style="border-radius: var(--sudut, 0)">
                                    <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none"
                                         stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round"
                                         aria-hidden="true">
                                        <path d="M4.5 5.5h6a2.5 2.5 0 0 1 2.5 2.5v11H7a2.5 2.5 0 0 1-2.5-2.5v-11zM13 8a2.5 2.5 0 0 1 2.5-2.5h4v11H16"/>
                                    </svg>
                                    Baca
                                </span>
                            </span>
                        </div>

                        @if ($t->series)
                            <p class="mt-3 label text-jingga">{{ $t->series }}</p>
                        @endif

                        <p class="mt-1 text-sm font-bold leading-snug text-arang seimbang">
                            <span class="tautan-judul">{{ $t->edition ?: $t->title }}</span>
                        </p>

                        <p class="mt-1 text-xs text-abu-500">
                            {{ $t->published_at?->translatedFormat('j F Y') }}
                            @unless ($t->punyaBerkas())
                                &middot; <span class="text-jingga">berkas belum ada</span>
                            @endunless
                        </p>
                    </a>
                @endforeach
            </div>

            <div class="mt-14">{{ $publications->links() }}</div>
        @endif
    </section>
@endsection
