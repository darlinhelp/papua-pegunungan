@props(['nama' => null, 'kelas' => 'h-5 w-5'])

@php $jalur = \App\Support\Ikon::jalur($nama); @endphp

@if ($jalur)
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"
         stroke-linecap="round" stroke-linejoin="round"
         class="{{ $kelas }}" aria-hidden="true">
        <path d="{{ $jalur }}"/>
    </svg>
@endif
