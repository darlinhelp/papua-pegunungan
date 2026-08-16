@extends('layouts.app')

@php
    $tajuk = $heading ?? $category->name;
    $ikon  = $category?->icon ?? 'bintang';
    // Tampilan kartu (grid) atau daftar ringkas (list), diatur dari
    // Pengaturan → Layout.
    $tampilan = \App\Support\Layout::tampilanDaftar();
@endphp

@section('judul', $tajuk)

@section('isi')
    <x-page-hero eyebrow="Terkini" :judul="$tajuk" :ikon="$ikon" />

    <section class="shell py-16">
        @if ($posts->isEmpty())
            <x-empty-state judul="Belum ada tulisan di kategori ini"
                           pesan="Begitu redaksi menerbitkan tulisan, daftarnya muncul di halaman ini."
                           :aksi="route('home')" />
        @elseif ($tampilan === 'list')
            <div class="max-w-3xl">
                @foreach ($posts as $p)
                    <x-post-card :post="$p" ukuran="kecil"
                                 class="{{ $loop->last ? '' : 'mb-6 pb-6 border-b border-abu-200' }}" />
                @endforeach
            </div>

            <div class="mt-14">{{ $posts->links() }}</div>
        @else
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $p)
                    <x-post-card :post="$p" data-naik class="opacity-0"
                                 style="animation-delay: {{ ($loop->index % 6) * 70 }}ms" />
                @endforeach
            </div>

            <div class="mt-14">{{ $posts->links() }}</div>
        @endif
    </section>
@endsection
