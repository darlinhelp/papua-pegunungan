@php
    // Menu dikelola lewat panel admin (Menu Utama), bukan berkas kode.
    // Bentuk array-nya sengaja dibuat identik dengan config/menu.php yang
    // lama, supaya markup di bawah ini tidak perlu diubah sama sekali.
    $menu = \App\Models\MenuItem::topLevel()->with('children')->get()
        ->map(fn ($grup) => [
            'label' => $grup->label,
            'lebar' => $grup->wide,
            'items' => $grup->children->map(fn ($item) => [
                'label' => $item->label,
                'url'   => $item->url,
                'luar'  => $item->is_external,
            ])->all(),
        ])->all();

    // 'route:nama' -> URL rute bernama; selain itu dipakai apa adanya.
    $tautan = function (string $url): string {
        return str_starts_with($url, 'route:')
            ? route(substr($url, 6))
            : (str_starts_with($url, 'http') ? $url : url($url));
    };

    $sosial  = \App\Support\Sosial::aktif();
    $logoUrl = \App\Support\Kop::logoUrl();

    $namaSingkat = \App\Models\Setting::get('nama_singkat', 'Dewan Pengurus Wilayah');
    $namaLengkap = \App\Models\Setting::get('nama_lengkap', 'PKS Papua Pegunungan');
    $tagline     = \App\Models\Setting::get('tagline', 'Kenali PKS Papua Pegunungan lebih dekat');
    $skalaNama   = \App\Support\Font::ukuranNama();
@endphp

<header x-data="{ terbuka: null, laci: false, cari: false, digulir: false }"
        @scroll.window="digulir = window.scrollY > 40"
        @keydown.escape.window="terbuka = null; laci = false; cari = false"
        class="sticky top-0 z-50">

    {{-- Pita atas: identitas wilayah dan kanal resmi.
         Bisa disembunyikan lewat Pengaturan → Layout. Warnanya bisa diatur
         di Pengaturan → Identitas Situs → Warna (bagian Gaya Pita Atas). --}}
    @php $gayaPita = \App\Support\Warna::gayaPita(); @endphp
    @if (\App\Support\Layout::pitaAtasTampil())
    <div @class([
            'text-abu-200 transition-all duration-300',
            'bg-arang-950' => $gayaPita === 'hitam',
            'bg-jingga'    => $gayaPita === 'aksen',
         ])
         @if ($gayaPita === 'gradasi')
             style="background: linear-gradient(120deg, rgb(var(--warna-aksen)) 0%, #0F0F11 70%)"
         @endif
         :class="digulir ? 'h-0 overflow-hidden opacity-0' : 'opacity-100'">
        <div class="kop-dalam !h-auto py-2 justify-between gap-4">
            <p class="label text-abu-300">{{ $tagline }}</p>
            @if ($sosial)
                <div class="flex items-center gap-1">
                    @foreach ($sosial as $kanal)
                        <a href="{{ $kanal['url'] }}" target="_blank" rel="noopener"
                           title="{{ $kanal['nama'] }}" aria-label="{{ $kanal['nama'] }}"
                           class="p-1.5 text-abu-300 hover:text-jingga transition-colors">
                            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                <path d="{{ $kanal['ikon'] }}"/>
                            </svg>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Bilah utama --}}
    <div class="bg-white/95 backdrop-blur border-b border-abu-200 transition-shadow duration-300"
         :class="digulir && 'shadow-kartu'">
        <div class="kop-dalam" :data-kecil="digulir">

            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0 group">
                <img src="{{ $logoUrl }}" alt="Logo DPW PKS Papua Pegunungan"
                     class="logo-kop" :data-kecil="digulir">
                <span class="hidden sm:block leading-tight">
                    <span class="block label text-arang/55">{{ $namaSingkat }}</span>
                    <span class="block font-extrabold text-arang tracking-tight
                                 transition-all duration-300"
                          style="font-size: calc(1em * {{ $skalaNama }})"
                          :class="digulir ? 'text-base' : 'text-lg'">
                        {{ $namaLengkap }}
                    </span>
                </span>
            </a>

            {{-- Menu desktop --}}
            <nav class="hidden lg:flex items-stretch gap-1 ml-auto h-full"
                 aria-label="Menu utama"
                 @mouseleave="terbuka = null">
                @foreach ($menu as $i => $grup)
                    <div class="relative flex items-stretch">
                        <button type="button"
                                @mouseenter="terbuka = {{ $i }}"
                                @click="terbuka = terbuka === {{ $i }} ? null : {{ $i }}"
                                :aria-expanded="terbuka === {{ $i }}"
                                class="relative flex items-center gap-1.5 px-4 text-sm font-semibold
                                       text-arang/80 hover:text-arang transition-colors">
                            {{ $grup['label'] }}
                            <svg viewBox="0 0 12 12" class="h-2.5 w-2.5 transition-transform duration-200"
                                 :class="terbuka === {{ $i }} && 'rotate-180'" aria-hidden="true">
                                <path d="M2 4.5L6 8.5L10 4.5" fill="none" stroke="currentColor"
                                      stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="absolute inset-x-3 bottom-0 h-0.5 bg-jingga origin-left
                                         transition-transform duration-300"
                                  :class="terbuka === {{ $i }} ? 'scale-x-100' : 'scale-x-0'"></span>
                        </button>

                        {{-- Panel mega menu --}}
                        <div x-show="terbuka === {{ $i }}" x-cloak x-transition.opacity.duration.150ms
                             class="absolute top-full left-1/2 -translate-x-1/2 pt-px">
                            <div class="bg-white shadow-angkat border-t-2 border-jingga
                                        {{ ($grup['lebar'] ?? false) ? 'w-[34rem]' : 'w-72' }} p-2">
                                <ul class="{{ ($grup['lebar'] ?? false) ? 'grid grid-cols-2 gap-x-2' : '' }}">
                                    @foreach ($grup['items'] as $item)
                                        <li>
                                            <a href="{{ $tautan($item['url']) }}"
                                               @if ($item['luar'] ?? false) target="_blank" rel="noopener" @endif
                                               class="group flex items-center gap-2 px-3 py-2.5 text-sm
                                                      text-arang/75 hover:text-arang hover:bg-abu-50
                                                      transition-colors">
                                                <span class="h-1 w-1 bg-abu-300 group-hover:bg-jingga
                                                             transition-colors shrink-0"></span>
                                                {{ $item['label'] }}
                                                @if ($item['luar'] ?? false)
                                                    <svg viewBox="0 0 12 12" class="h-2.5 w-2.5 opacity-40"
                                                         aria-hidden="true">
                                                        <path d="M4 2h6v6M10 2L2.5 9.5" fill="none"
                                                              stroke="currentColor" stroke-width="1.5"
                                                              stroke-linecap="round"/>
                                                    </svg>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </nav>

            <div class="flex items-center gap-2 ml-auto lg:ml-0">
                <button type="button" @click="cari = !cari" :aria-expanded="cari"
                        aria-label="Cari di situs ini"
                        class="p-2.5 text-arang/70 hover:text-jingga transition-colors">
                    <svg viewBox="0 0 20 20" class="h-5 w-5" aria-hidden="true">
                        <circle cx="9" cy="9" r="6" fill="none" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M13.5 13.5L18 18" stroke="currentColor" stroke-width="1.8"
                              stroke-linecap="round"/>
                    </svg>
                </button>

                <a href="https://daftar.pks.id" target="_blank" rel="noopener"
                   class="hidden sm:inline-flex items-center bg-jingga px-5 py-2.5 text-sm font-semibold
                          text-white hover:bg-jingga-700 transition-colors">
                    Gabung
                </a>

                <button type="button" @click="laci = true" aria-label="Buka menu"
                        class="lg:hidden p-2.5 text-arang">
                    <svg viewBox="0 0 20 20" class="h-5 w-5" aria-hidden="true">
                        <path d="M3 5h14M3 10h14M3 15h14" stroke="currentColor" stroke-width="1.8"
                              stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Baris pencarian --}}
        <div x-show="cari" x-cloak x-collapse class="border-t border-abu-200 bg-abu-50">
            <form action="{{ route('search') }}" method="get"
                  class="kop-dalam !h-auto py-4 gap-3">
                <input type="search" name="q" placeholder="Cari berita, opini, atau halaman"
                       x-ref="kolomCari" x-effect="cari && $nextTick(() => $refs.kolomCari.focus())"
                       class="flex-1 border border-abu-200 bg-white px-4 py-3 text-sm
                              placeholder:text-abu-400 focus:border-jingga">
                <button type="submit"
                        class="bg-arang-800 px-6 py-3 text-sm font-semibold text-white
                               hover:bg-arang-800 transition-colors">
                    Cari
                </button>
            </form>
        </div>
    </div>

    {{-- Laci menu untuk layar kecil --}}
    <div x-show="laci" x-cloak class="lg:hidden fixed inset-0 z-50">
        <div class="absolute inset-0 bg-arang-950/60" @click="laci = false"
             x-transition.opacity></div>

        <div class="absolute right-0 top-0 h-full w-[min(22rem,88vw)] bg-white flex flex-col"
             x-transition:enter="transition duration-300"
             x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transition duration-200"
             x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

            <div class="flex items-center justify-between px-5 h-20 border-b border-abu-200">
                <span class="font-extrabold tracking-tight">Menu</span>
                <button type="button" @click="laci = false" aria-label="Tutup menu" class="p-2">
                    <svg viewBox="0 0 20 20" class="h-5 w-5" aria-hidden="true">
                        <path d="M5 5l10 10M15 5L5 15" stroke="currentColor" stroke-width="1.8"
                              stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto py-2" aria-label="Menu utama">
                @foreach ($menu as $i => $grup)
                    <div x-data="{ isi: false }" class="border-b border-abu-100">
                        <button type="button" @click="isi = !isi" :aria-expanded="isi"
                                class="w-full flex items-center justify-between px-5 py-4
                                       text-sm font-semibold text-left">
                            {{ $grup['label'] }}
                            <svg viewBox="0 0 12 12" class="h-3 w-3 transition-transform"
                                 :class="isi && 'rotate-180'" aria-hidden="true">
                                <path d="M2 4.5L6 8.5L10 4.5" fill="none" stroke="currentColor"
                                      stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <ul x-show="isi" x-collapse class="pb-2">
                            @foreach ($grup['items'] as $item)
                                <li>
                                    <a href="{{ $tautan($item['url']) }}"
                                       @if ($item['luar'] ?? false) target="_blank" rel="noopener" @endif
                                       class="block pl-8 pr-5 py-2.5 text-sm text-arang/70 hover:text-jingga">
                                        {{ $item['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </nav>

            <div class="p-5 border-t border-abu-200">
                <a href="https://daftar.pks.id" target="_blank" rel="noopener"
                   class="block bg-jingga py-3.5 text-center text-sm font-semibold text-white">
                    Gabung jadi anggota
                </a>
            </div>
        </div>
    </div>
</header>
