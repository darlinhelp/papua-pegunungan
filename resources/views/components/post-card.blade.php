@props([
    'post',
    'ukuran' => 'sedang',  // besar | sedang | kecil | teks
])

@php
    $url = route('posts.show', $post);
    $tanggal = $post->published_at?->translatedFormat('j F Y');
@endphp

@if ($ukuran === 'teks')
    {{-- Judul saja: daftar pendamping di sebelah berita utama --}}
    <article {{ $attributes->class(['group py-4 border-b border-abu-200 last:border-0']) }}>
        <a href="{{ $url }}" class="block">
            <h3 class="text-base font-bold leading-snug text-arang seimbang">
                <span class="tautan-judul">{{ $post->title }}</span>
            </h3>
            <p class="mt-2 text-xs text-abu-500">{{ $tanggal }}</p>
        </a>
    </article>

@elseif ($ukuran === 'besar')
    <article {{ $attributes->class(['group']) }}>
        <a href="{{ $url }}" class="block bingkai overflow-hidden bg-abu-100 aspect-[16/9]">
            <img src="{{ $post->coverUrl() }}" alt="" width="1200" height="675"
                 loading="lazy" decoding="async"
                 class="h-full w-full object-cover transition-transform duration-500
                        group-hover:scale-[1.03]">
        </a>
        <div class="mt-4">
            <p class="flex items-center gap-1.5 label text-jingga mb-2">
                <x-ikon :nama="$post->category->icon" kelas="h-3.5 w-3.5" />
                {{ $post->category->name }}
            </p>
            <h3 class="text-xl sm:text-2xl font-extrabold leading-tight text-arang seimbang">
                <a href="{{ $url }}"><span class="tautan-judul">{{ $post->title }}</span></a>
            </h3>
            <p class="mt-3 text-sm text-abu-600 leading-relaxed">{{ $post->summary(180) }}</p>
            <p class="mt-3 text-xs text-abu-500">{{ $tanggal }}</p>
        </div>
    </article>

@elseif ($ukuran === 'kecil')
    <article {{ $attributes->class(['group flex gap-4']) }}>
        <a href="{{ $url }}" class="shrink-0 bingkai overflow-hidden bg-abu-100 w-24 h-20 sm:w-28 sm:h-24">
            <img src="{{ $post->coverUrl() }}" alt="" width="400" height="300"
                 loading="lazy" decoding="async"
                 class="h-full w-full object-cover transition-transform duration-500
                        group-hover:scale-105">
        </a>
        <div class="min-w-0">
            <p class="flex items-center gap-1.5 label text-jingga mb-1">
                <x-ikon :nama="$post->category->icon" kelas="h-3.5 w-3.5" />
                {{ $post->category->name }}
            </p>
            <h3 class="text-base font-bold leading-snug text-arang seimbang">
                <a href="{{ $url }}"><span class="tautan-judul">{{ $post->title }}</span></a>
            </h3>
            <p class="mt-1.5 text-xs text-abu-500">{{ $tanggal }}</p>
        </div>
    </article>

@else
    <article {{ $attributes->class(['group kartu flex flex-col h-full']) }}>
        <a href="{{ $url }}" class="block bingkai overflow-hidden bg-abu-100 aspect-[3/2]">
            <img src="{{ $post->coverUrl() }}" alt="" width="1200" height="800"
                 loading="lazy" decoding="async"
                 class="h-full w-full object-cover transition-transform duration-500
                        group-hover:scale-[1.03]">
        </a>
        <div class="flex flex-col flex-1 p-4">
            <p class="flex items-center gap-1.5 label text-jingga mb-2">
                <x-ikon :nama="$post->category->icon" kelas="h-3.5 w-3.5" />
                {{ $post->category->name }}
            </p>
            <h3 class="text-base font-bold leading-snug text-arang seimbang">
                <a href="{{ $url }}"><span class="tautan-judul">{{ $post->title }}</span></a>
            </h3>
            <p class="mt-2 text-sm text-abu-600 leading-relaxed">{{ $post->summary(110) }}</p>
            <p class="mt-auto pt-3 text-xs text-abu-500">{{ $tanggal }}</p>
        </div>
    </article>
@endif
