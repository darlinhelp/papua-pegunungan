@extends('layouts.app')

@section('judul', $post->title)
@section('deskripsi', $post->summary(180))
@section('gambar', $post->coverUrl())

@push('kepala')
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'NewsArticle',
        'headline' => $post->title,
        'datePublished' => $post->published_at?->toIso8601String(),
        'dateModified' => $post->updated_at?->toIso8601String(),
        'image' => [$post->coverUrl()],
        'author' => ['@type' => 'Organization', 'name' => 'DPW PKS Papua Pegunungan'],
        'publisher' => ['@type' => 'Organization', 'name' => 'DPW PKS Papua Pegunungan'],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('isi')
    <article>
        {{-- Kepala artikel --}}
        <header class="bg-arang-950 text-white">
            <div class="shell pt-12 pb-10 max-w-4xl">
                <nav class="flex items-center gap-2 text-xs text-abu-400 mb-6" aria-label="Remah roti">
                    <a href="{{ route('home') }}" class="hover:text-jingga transition-colors">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('posts.index', $post->category) }}"
                       class="hover:text-jingga transition-colors">{{ $post->category->name }}</a>
                </nav>

                <h1 class="judul-artikel seimbang">
                    {{ $post->title }}
                </h1>

                <div class="mt-6 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-abu-300">
                    <span>{{ $post->author_name ?: 'Redaksi DPW' }}</span>
                    <span class="h-1 w-1 rounded-full bg-abu-400/60"></span>
                    <time datetime="{{ $post->published_at?->toDateString() }}">
                        {{ $post->published_at?->translatedFormat('l, j F Y') }}
                    </time>
                    <span class="h-1 w-1 rounded-full bg-abu-400/60"></span>
                    <span>{{ $post->readingMinutes() }} menit baca</span>
                </div>

                @if ($post->source_name)
                    <p class="mt-4 text-sm">
                        <span class="text-abu-400">Sumber:</span>
                        @if ($post->source_url)
                            <a href="{{ $post->source_url }}" target="_blank" rel="noopener"
                               class="text-jingga hover:underline">{{ $post->source_name }}</a>
                        @else
                            <span class="text-white">{{ $post->source_name }}</span>
                        @endif
                    </p>
                @endif
            </div>
        </header>

        @if ($post->cover_path)
            <figure class="bg-arang-950">
                <div class="shell max-w-5xl">
                    <img src="{{ $post->coverUrl() }}" alt="{{ $post->cover_caption }}"
                         class="w-full" fetchpriority="high">
                </div>
                @if ($post->cover_caption)
                    <div class="shell max-w-5xl">
                        <figcaption class="pt-4 pb-8 text-sm text-abu-400">
                            {{ $post->cover_caption }}
                        </figcaption>
                    </div>
                @endif
            </figure>
        @endif

        {{-- Badan artikel --}}
        <div class="shell py-14">
            <div class="grid gap-12 lg:grid-cols-12">
                <div class="lg:col-span-8 lg:col-start-3">
                    <div class="artikel max-w-baca">
                        {!! $post->renderedBody() !!}
                    </div>

                    {{-- Berbagi --}}
                    <div class="mt-12 pt-6 border-t border-abu-200 flex items-center gap-3">
                        <span class="label text-arang/50">Bagikan</span>
                        @php
                            $u = urlencode(url()->current());
                            $t = urlencode($post->title);
                        @endphp
                        <a href="https://api.whatsapp.com/send?text={{ $t }}%20{{ $u }}"
                           target="_blank" rel="noopener"
                           class="px-4 py-2 text-sm font-semibold border border-abu-200
                                  hover:border-jingga hover:text-jingga transition-colors">WhatsApp</a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $u }}"
                           target="_blank" rel="noopener"
                           class="px-4 py-2 text-sm font-semibold border border-abu-200
                                  hover:border-jingga hover:text-jingga transition-colors">Facebook</a>
                        <a href="https://twitter.com/intent/tweet?text={{ $t }}&url={{ $u }}"
                           target="_blank" rel="noopener"
                           class="px-4 py-2 text-sm font-semibold border border-abu-200
                                  hover:border-jingga hover:text-jingga transition-colors">X</a>
                    </div>
                </div>
            </div>
        </div>
    </article>

    @if ($terkait->isNotEmpty())
        <section class="bg-abu-50 py-16">
            <div class="shell">
                <x-section-head eyebrow="Masih satu topik" judul="Baca juga" />
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($terkait as $p)
                        <x-post-card :post="$p" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
