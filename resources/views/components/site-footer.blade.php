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

    $tautan = function (string $url): string {
        return str_starts_with($url, 'route:')
            ? route(substr($url, 6))
            : (str_starts_with($url, 'http') ? $url : url($url));
    };

    $sosial      = \App\Support\Sosial::aktif();
    $logoKaki    = \App\Support\Kop::logoFooterUrl();
    $footerGelap = \App\Support\Kop::footerGelap();
    $namaSingkat = \App\Models\Setting::get('nama_singkat', 'Dewan Pengurus Wilayah');
    $namaLengkap = \App\Models\Setting::get('nama_lengkap', 'PKS Papua Pegunungan');
    $ukuranNama  = \App\Support\Font::ukuranNama();

    $alamat  = \App\Models\Setting::get('alamat', 'Jl. Trikora, Wamena, Kabupaten Jayawijaya, Papua Pegunungan');
    $telepon = \App\Models\Setting::get('telepon', '+62 969 000 0000');
    $surel   = \App\Models\Setting::get('surel', 'sekretariat@papua-pegunungan.pks.id');
@endphp

<footer class="relative mt-16 {{ $footerGelap ? 'bg-arang-950 text-abu-200' : 'bg-abu-50 text-arang border-t border-abu-200' }}">

    <div class="shell pt-20 pb-10">
        <div class="grid gap-12 lg:grid-cols-12">

            <div class="lg:col-span-4">
                <div class="flex items-center gap-3">
                    {{-- Bantalan putih di belakang logo. Logo organisasi biasanya
                         dirancang untuk latar putih, jadi kalau footer memakai
                         tema gelap, logo apa pun tetap terlihat tanpa perlu
                         mengunggah versi kedua yang khusus untuk latar gelap. --}}
                    <span class="{{ $footerGelap ? 'bg-white p-2' : '' }} shrink-0"
                          style="border-radius: var(--sudut, 0)">
                        <img src="{{ $logoKaki }}" alt="" class="h-10 w-auto">
                    </span>
                    <span class="leading-tight">
                        <span class="block label {{ $footerGelap ? 'text-abu-400' : 'text-abu-500' }}">
                            {{ $namaSingkat }}
                        </span>
                        <span class="block font-extrabold tracking-tight
                                     {{ $footerGelap ? 'text-white' : 'text-arang' }}"
                              style="font-size: calc(1.125rem * {{ $ukuranNama }})">
                            {{ $namaLengkap }}
                        </span>
                    </span>
                </div>


                <span class="garis-seksi mt-6" aria-hidden="true"></span>

                <address class="mt-6 not-italic text-sm leading-relaxed {{ $footerGelap ? 'text-abu-300' : 'text-abu-600' }}">
                    {{ $alamat }}
                </address>

                @if ($sosial)
                    <p class="mt-8 label {{ $footerGelap ? 'text-abu-400' : 'text-abu-500' }}">Kunjungi akun resmi kami</p>
                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        @foreach ($sosial as $kanal)
                            <a href="{{ $kanal['url'] }}" target="_blank" rel="noopener"
                               title="{{ $kanal['nama'] }}" aria-label="{{ $kanal['nama'] }}"
                               @class([
                                   'grid place-items-center h-9 w-9 border transition-colors',
                                   'border-white/15 text-abu-300 hover:text-white hover:border-jingga hover:bg-jingga' => $footerGelap,
                                   'border-abu-300 text-abu-500 hover:text-white hover:border-jingga hover:bg-jingga' => ! $footerGelap,
                               ])>
                                <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                    <path d="{{ $kanal['ikon'] }}"/>
                                </svg>
                            </a>
                        @endforeach
                    </div>
                @endif

                <dl class="mt-5 space-y-2 text-sm">
                    <div class="flex gap-3">
                        <dt class="{{ $footerGelap ? 'text-abu-400' : 'text-abu-500' }} w-16 shrink-0">Telepon</dt>
                        <dd>
                            <a href="tel:{{ preg_replace('/\s+/', '', $telepon) }}"
                               class="hover:text-jingga transition-colors">{{ $telepon }}</a>
                        </dd>
                    </div>
                    <div class="flex gap-3">
                        <dt class="{{ $footerGelap ? 'text-abu-400' : 'text-abu-500' }} w-16 shrink-0">Surel</dt>
                        <dd>
                            <a href="mailto:{{ $surel }}"
                               class="hover:text-jingga transition-colors break-all">{{ $surel }}</a>
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="lg:col-span-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($menu as $grup)
                    <div>
                        <p class="label text-jingga mb-4">{{ $grup['label'] }}</p>
                        <ul class="space-y-2.5 text-sm">
                            @foreach (array_slice($grup['items'], 0, 6) as $item)
                                <li>
                                    <a href="{{ $tautan($item['url']) }}"
                                       @if ($item['luar'] ?? false) target="_blank" rel="noopener" @endif
                                       @class([
                                           'transition-colors',
                                           'text-abu-300 hover:text-white' => $footerGelap,
                                           'text-abu-600 hover:text-jingga' => ! $footerGelap,
                                       ])>
                                        {{ $item['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-16 pt-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4
                     {{ $footerGelap ? 'border-t border-white/10' : 'border-t border-abu-200' }}">
            <p class="text-xs {{ $footerGelap ? 'text-abu-400' : 'text-abu-500' }}">
                &copy; {{ date('Y') }} DPW Partai Keadilan Sejahtera Provinsi Papua Pegunungan.
                Dikelola oleh Bidang Humas &amp; Media DPW.
            </p>
            <p class="text-xs {{ $footerGelap ? 'text-abu-400' : 'text-abu-500' }}">
                Situs resmi DPP:
                <a href="https://pks.id" target="_blank" rel="noopener"
                   class="{{ $footerGelap ? 'text-abu-200' : 'text-arang' }} hover:text-jingga transition-colors">pks.id</a>
            </p>
        </div>
    </div>
</footer>
