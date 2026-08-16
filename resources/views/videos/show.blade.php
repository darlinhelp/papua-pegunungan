@extends('layouts.app')

@section('judul', $video->title)
@section('deskripsi', $video->description)

@section('isi')
    <section class="bg-arang-950 py-10">
        <div class="shell max-w-5xl">
            <div class="aspect-video bg-black">
                <iframe src="{{ $video->embedUrl() }}" title="{{ $video->title }}"
                        class="h-full w-full" loading="lazy" allowfullscreen
                        allow="accelerometer; clipboard-write; encrypted-media; picture-in-picture">
                </iframe>
            </div>

            <h1 class="mt-8 text-2xl sm:text-3xl font-extrabold tracking-tight
                       text-white seimbang">
                {{ $video->title }}
            </h1>
            <p class="mt-3 text-sm text-abu-400">
                {{ $video->published_at?->translatedFormat('j F Y') }}
            </p>
            @if ($video->description)
                <p class="mt-5 text-abu-200 leading-relaxed max-w-3xl">{{ $video->description }}</p>
            @endif
        </div>
    </section>

    @if ($lainnya->isNotEmpty())
        <section class="shell py-16">
            <x-section-head eyebrow="Tayangan lain" judul="Video lainnya"
                            :href="route('videos.index')" />
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($lainnya as $v)
                    <a href="{{ route('videos.show', $v) }}" class="group block">
                        <div class="relative bingkai overflow-hidden bg-abu-100 aspect-video">
                            <img src="{{ $v->thumbnailUrl() }}" alt="" loading="lazy" decoding="async"
                                 class="h-full w-full object-cover transition-transform duration-700
                                        group-hover:scale-[1.04]">
                        </div>
                        <h3 class="mt-4 text-base font-bold leading-snug seimbang">
                            <span class="tautan-judul">{{ $v->title }}</span>
                        </h3>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
@endsection
