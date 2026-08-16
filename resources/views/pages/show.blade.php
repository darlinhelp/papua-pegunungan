@extends('layouts.app')

@section('judul', $page->title)

@section('isi')
    <x-page-hero :judul="$page->title" />

    <section class="shell py-14">
        <div class="grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-8 lg:col-start-3">
                <div class="artikel max-w-baca">
                    {!! $page->renderedBody() !!}
                </div>
            </div>
        </div>
    </section>
@endsection
