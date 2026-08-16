@extends('layouts.app')

@section('judul', 'Video')

@section('isi')
    <x-page-hero eyebrow="Tayangan" judul="Video" ikon="video"
                 deskripsi="Liputan kegiatan, pernyataan pengurus, dan tayangan kaderisasi." />

    <section class="shell py-16">
        @if ($videos->isEmpty())
            <x-empty-state judul="Belum ada video" :aksi="route('home')" />
        @else
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($videos as $v)
                    <a href="{{ route('videos.show', $v) }}" class="group block">
                        <div class="relative bingkai overflow-hidden bg-abu-100 aspect-video">
                            <img src="{{ $v->thumbnailUrl() }}" alt="" loading="lazy" decoding="async"
                                 class="h-full w-full object-cover transition-transform duration-700
                                        group-hover:scale-[1.04]">
                            <span class="absolute inset-0 grid place-items-center">
                                <span class="grid place-items-center h-12 w-12 bg-jingga/90
                                             transition-transform group-hover:scale-110">
                                    <svg viewBox="0 0 16 16" class="h-4 w-4 fill-white" aria-hidden="true">
                                        <path d="M5 3.5v9l8-4.5z"/>
                                    </svg>
                                </span>
                            </span>
                        </div>
                        <h2 class="mt-4 text-lg font-bold leading-snug seimbang">
                            <span class="tautan-judul">{{ $v->title }}</span>
                        </h2>
                        <p class="mt-2 text-xs text-arang/50">
                            {{ $v->published_at?->translatedFormat('j F Y') }}
                        </p>
                    </a>
                @endforeach
            </div>

            <div class="mt-14">{{ $videos->links() }}</div>
        @endif
    </section>
@endsection
