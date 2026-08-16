@extends('layouts.app')

@section('judul', $q !== '' ? 'Hasil pencarian: ' . $q : 'Pencarian')

@section('isi')
    <x-page-hero eyebrow="Pencarian"
                 :judul="$q !== '' ? 'Hasil untuk “' . $q . '”' : 'Cari di situs ini'" />

    <section class="shell py-16">
        <form action="{{ route('search') }}" method="get" class="flex gap-3 max-w-2xl mb-12">
            <input type="search" name="q" value="{{ $q }}"
                   placeholder="Ketik kata kunci, misalnya: musyawarah kerja"
                   class="flex-1 border border-abu-200 px-4 py-3 text-sm
                          placeholder:text-arang/40 focus:border-jingga">
            <button type="submit"
                    class="bg-arang-800 px-6 py-3 text-sm font-semibold text-white
                           hover:bg-arang-800 transition-colors">
                Cari
            </button>
        </form>

        @if ($results === null)
            <p class="text-arang/60">Masukkan kata kunci untuk mulai mencari.</p>
        @elseif ($results->isEmpty())
            <x-empty-state judul="Tidak ada yang cocok"
                           pesan="Coba kata kunci yang lebih pendek, atau telusuri lewat menu Terkini."
                           :aksi="route('home')" />
        @else
            <p class="text-sm text-arang/60 mb-8">{{ $results->total() }} tulisan ditemukan.</p>

            @if (\App\Support\Layout::tampilanDaftar() === 'list')
                <div class="max-w-3xl">
                    @foreach ($results as $p)
                        <x-post-card :post="$p" ukuran="kecil"
                                     class="{{ $loop->last ? '' : 'mb-6 pb-6 border-b border-abu-200' }}" />
                    @endforeach
                </div>
            @else
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($results as $p)
                        <x-post-card :post="$p" />
                    @endforeach
                </div>
            @endif

            <div class="mt-14">{{ $results->links() }}</div>
        @endif
    </section>
@endsection
