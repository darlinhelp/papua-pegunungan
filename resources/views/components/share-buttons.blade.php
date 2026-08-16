@props([
    'judul',
    'url' => null,
    'label' => 'Bagikan',
    'terang' => false,   // di atas latar gelap
])

@php
    $alamat = $url ?: url()->current();
    $u = urlencode($alamat);
    $t = urlencode($judul);

    $kanal = [
        [
            'nama' => 'WhatsApp',
            'url'  => "https://api.whatsapp.com/send?text={$t}%20{$u}",
            'ikon' => 'M12 2a10 10 0 00-8.6 15L2 22l5.2-1.4A10 10 0 1012 2zm0 2a8 8 0 016.7 12.3l-.3.5.6 2.1-2.2-.6-.5.3A8 8 0 1112 4zm-3.4 3.9c-.2 0-.5 0-.7.4-.2.4-.9.9-.9 2.1s.9 2.4 1 2.6c.1.2 1.7 2.7 4.2 3.7 2 .8 2.4.6 2.9.6.5 0 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2l-.6-.3-1.5-.7c-.2-.1-.4-.1-.5.1l-.7.9c-.1.2-.3.2-.5.1-.2-.1-1-.4-1.9-1.2-.7-.6-1.2-1.4-1.3-1.6-.1-.2 0-.4.1-.5l.4-.5c.1-.2.2-.3.2-.5l-.1-.5-.7-1.6c-.2-.4-.4-.4-.5-.4h-.4z',
        ],
        [
            'nama' => 'Telegram',
            'url'  => "https://t.me/share/url?url={$u}&text={$t}",
            'ikon' => 'M21.9 4.3l-3 14.2c-.2 1-.8 1.3-1.7.8l-4.6-3.4-2.2 2.1c-.2.2-.5.5-1 .5l.3-4.7 8.6-7.8c.4-.3-.1-.5-.6-.2L6.1 12.6l-4.5-1.4c-1-.3-1-1 .2-1.5l17.6-6.8c.8-.3 1.5.2 1.2 1.4z',
        ],
        [
            'nama' => 'X',
            'url'  => "https://twitter.com/intent/tweet?text={$t}&url={$u}",
            'ikon' => 'M17.2 3h3.3l-7.2 8.2L21.8 21h-6.6l-5.2-6.7L4.1 21H.8l7.7-8.8L.5 3h6.8l4.7 6.2L17.2 3zm-1.2 16h1.8L8.1 4.9H6.1L16 19z',
        ],
        [
            'nama' => 'Facebook',
            'url'  => "https://www.facebook.com/sharer/sharer.php?u={$u}",
            'ikon' => 'M13.5 21v-8h2.7l.4-3.1h-3.1V7.9c0-.9.25-1.5 1.55-1.5H16.7V3.6c-.3 0-1.3-.1-2.45-.1-2.4 0-4.05 1.5-4.05 4.2v2.2H7.5V13h2.7v8h3.3z',
        ],
        [
            'nama' => 'Surel',
            'url'  => "mailto:?subject={$t}&body={$u}",
            'ikon' => 'M3.5 5.5h17v13h-17v-13zM3.5 6.5l8.5 6.5 8.5-6.5',
        ],
    ];
@endphp

<div class="flex flex-wrap items-center gap-2">
    @if ($label)
        <span class="text-sm font-semibold mr-1 {{ $terang ? 'text-abu-300' : 'text-abu-500' }}">
            {{ $label }}
        </span>
    @endif

    @foreach ($kanal as $k)
        <a href="{{ $k['url'] }}" target="_blank" rel="noopener"
           title="Bagikan lewat {{ $k['nama'] }}" aria-label="Bagikan lewat {{ $k['nama'] }}"
           class="grid place-items-center h-9 w-9 rounded-full transition-colors
                  {{ $terang
                      ? 'bg-white/10 text-abu-200 hover:bg-jingga hover:text-white'
                      : 'bg-abu-100 text-abu-600 hover:bg-jingga hover:text-white' }}">
            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                <path d="{{ $k['ikon'] }}"/>
            </svg>
        </a>
    @endforeach
</div>
