@props([
    'judul',
    'ikon' => null,
    'href' => null,
    'hrefLabel' => 'Lainnya',
    'terang' => false,
])

<div class="flex items-end justify-between gap-6 mb-6">
    <div>
        <span class="garis-seksi" aria-hidden="true"></span>

        <h2 class="judul-beranda flex items-center gap-2.5 seimbang
                   {{ $terang ? 'text-white' : 'text-arang' }}">
            @if ($ikon)
                <x-ikon :nama="$ikon" kelas="h-6 w-6 shrink-0 text-jingga" />
            @endif
            {{ $judul }}
        </h2>
    </div>

    @if ($href)
        <a href="{{ $href }}"
           class="group flex items-center gap-1.5 text-sm font-semibold shrink-0
                  {{ $terang ? 'text-white/70 hover:text-jingga' : 'text-abu-500 hover:text-jingga' }}
                  transition-colors">
            {{ $hrefLabel }}
            <svg viewBox="0 0 16 16" class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5"
                 aria-hidden="true">
                <path d="M3 8h10M9 4l4 4-4 4" fill="none" stroke="currentColor" stroke-width="1.8"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
    @endif
</div>
