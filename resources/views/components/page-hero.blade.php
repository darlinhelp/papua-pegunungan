@props(['eyebrow' => null, 'judul', 'deskripsi' => null, 'ikon' => null])

<section class="bg-abu-50 border-b border-abu-200">
    <div class="shell py-10 sm:py-14">
        @if ($eyebrow)
            <p class="label text-jingga mb-2">{{ $eyebrow }}</p>
        @endif

        <span class="garis-seksi" aria-hidden="true"></span>

        <h1 class="flex items-center gap-3 text-2xl sm:text-4xl font-extrabold tracking-tight
                   text-arang seimbang max-w-4xl">
            @if ($ikon)
                <x-ikon :nama="$ikon" kelas="h-8 w-8 sm:h-10 sm:w-10 shrink-0 text-jingga" />
            @endif
            {{ $judul }}
        </h1>

        @if ($deskripsi)
            <p class="mt-3 text-abu-600 max-w-2xl leading-relaxed">{{ $deskripsi }}</p>
        @endif
    </div>
</section>
