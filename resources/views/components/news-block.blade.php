@props([
    'judul',
    'posts',
    'ikon' => null,
    'href' => null,
    'hrefLabel' => 'Lainnya',
    'latar' => false,   // beri latar abu tipis
])

@if ($posts->isNotEmpty())
    @php
        $utama       = $posts->first();
        $pendamping  = $posts->skip(1);
    @endphp

    <section class="{{ $latar ? 'bg-abu-50 py-12' : 'py-12' }}">
        <div class="shell">
            <x-section-head :judul="$judul" :ikon="$ikon" :href="$href" :hrefLabel="$hrefLabel" />

            <div class="grid gap-8 lg:grid-cols-12">
                {{-- Berita utama --}}
                <div class="lg:col-span-7">
                    <x-post-card :post="$utama" ukuran="besar" />
                </div>

                {{-- Judul-judul pendamping, pola portal berita DPP --}}
                @if ($pendamping->isNotEmpty())
                    <div class="lg:col-span-5 lg:border-l lg:border-abu-200 lg:pl-8">
                        @foreach ($pendamping as $p)
                            <x-post-card :post="$p" ukuran="teks" />
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
