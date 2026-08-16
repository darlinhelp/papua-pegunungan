@extends('layouts.app')

@section('judul', 'Halaman tidak ditemukan')

@section('isi')
    <section class="shell py-28 text-center">
        <p class="label text-jingga-700">Galat 404</p>
        <h1 class="mt-4 text-4xl sm:text-5xl font-extrabold tracking-tight seimbang">
            Halaman ini tidak ada
        </h1>
        <p class="mt-4 text-arang/60 max-w-md mx-auto">
            Tautannya mungkin sudah berubah. Coba cari lewat kotak pencarian, atau kembali ke beranda.
        </p>
        <div class="mt-8 flex items-center justify-center gap-3">
            <a href="{{ route('home') }}"
               class="bg-jingga px-6 py-3 text-sm font-semibold text-white hover:bg-jingga-700
                      transition-colors">Beranda</a>
            <a href="{{ route('search') }}"
               class="border border-abu-200 px-6 py-3 text-sm font-semibold hover:border-jingga
                      hover:text-jingga transition-colors">Cari</a>
        </div>
        <span class="inline-block h-1 w-10 bg-jingga mt-14" aria-hidden="true"></span>
    </section>
@endsection
