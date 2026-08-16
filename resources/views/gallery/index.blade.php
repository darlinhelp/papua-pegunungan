@extends('layouts.app')

@section('judul', 'Galeri Foto')

@section('isi')
    <x-page-hero eyebrow="Dokumentasi" judul="Galeri Foto" ikon="foto"
                 deskripsi="Rekaman kegiatan DPW dan DPD se-Papua Pegunungan." />

    <section class="shell py-16">
        @if ($albums->isEmpty())
            <x-empty-state judul="Belum ada album foto"
                           pesan="Album pertama akan tampil di sini setelah diunggah."
                           :aksi="route('home')" />
        @else
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($albums as $album)
                    <a href="{{ route('gallery.show', $album) }}" class="group block opacity-0" data-naik>
                        <div class="bingkai overflow-hidden bg-abu-100 aspect-[3/2]">
                            <img src="{{ $album->coverUrl() }}" alt="" loading="lazy" decoding="async"
                                 class="h-full w-full object-cover transition-transform duration-700
                                        group-hover:scale-[1.04]">
                        </div>
                        <h2 class="mt-4 text-lg font-bold leading-snug seimbang">
                            <span class="tautan-judul">{{ $album->title }}</span>
                        </h2>
                        <p class="mt-2 text-xs text-arang/50">
                            {{ $album->photos->count() }} foto
                            @if ($album->location) &middot; {{ $album->location }} @endif
                        </p>
                    </a>
                @endforeach
            </div>

            <div class="mt-14">{{ $albums->links() }}</div>
        @endif
    </section>
@endsection
