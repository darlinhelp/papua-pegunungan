@extends('layouts.app')

@section('judul', $publication->judulLengkap())
@section('deskripsi', $publication->description)
@section('gambar', $publication->coverUrl())

@section('isi')

    <article class="shell py-10 max-w-4xl">

        {{-- ── Kepala ── --}}
        <header>
            <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-arang seimbang">
                {{ $publication->judulLengkap() }}
            </h1>

            <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
                <p class="flex items-center gap-2 text-sm text-jingga font-semibold">
                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor"
                         stroke-width="1.75" aria-hidden="true">
                        <circle cx="12" cy="12" r="8.5"/>
                        <path d="M12 7.5V12l3 2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    {{ $publication->published_at?->translatedFormat('l, j F Y \p\u\k\u\l H.i') }} WIB
                </p>

                <x-share-buttons :judul="$publication->judulLengkap()" label="" />
            </div>

            @if ($publication->description)
                <p class="mt-6 text-arang/80 leading-relaxed">
                    {{ $publication->description }}
                </p>
            @endif
        </header>

        {{-- ── Pembaca ── --}}
        @if ($publication->punyaBerkas())
            <div class="mt-8" id="pembaca" data-berkas="{{ $publication->fileUrl() }}">

                {{-- Tampilan awal: sampul + tombol. Murni HTML, jadi selalu
                     ada yang berguna di layar bahkan sebelum apa pun dimuat
                     atau kalau JavaScript mati. --}}
                <div id="pembaca-ringkas"
                     class="bingkai bg-abu-50 border border-abu-200 p-6 sm:p-10 text-center">
                    <img src="{{ $publication->coverUrl() }}" alt=""
                         class="mx-auto max-h-[55vh] w-auto bingkai shadow-kartu">

                    <p id="pembaca-status" class="mt-5 text-sm text-abu-500">
                        Menyiapkan halaman…
                    </p>

                    <div class="mt-5 flex flex-wrap items-center justify-center gap-3">
                        <a href="{{ $publication->fileUrl() }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 border border-abu-300 px-5 py-2.5
                                  text-sm font-semibold text-arang hover:border-jingga
                                  hover:text-jingga transition-colors"
                           style="border-radius: var(--sudut, 0)">
                            Buka di tab baru
                        </a>

                        <a href="{{ $publication->fileUrl() }}" download
                           class="inline-flex items-center gap-2 bg-jingga px-5 py-2.5 text-sm
                                  font-semibold text-white hover:bg-jingga-700 transition-colors"
                           style="border-radius: var(--sudut, 0)">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor"
                                 stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"
                                 aria-hidden="true">
                                <path d="M12 4v10m0 0l-4-4m4 4l4-4M4.5 18.5h15"/>
                            </svg>
                            Unduh PDF
                        </a>
                    </div>
                </div>

                {{-- Buku. Baru ditampilkan setelah semua halaman siap. --}}
                <div id="pembaca-buku-wadah" class="hidden">
                    <div class="bingkai bg-arang-950 p-4 sm:p-8">
                        <div id="pembaca-buku" class="mx-auto"></div>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                        <a href="{{ $publication->fileUrl() }}" download
                           class="inline-flex items-center gap-2 bg-jingga px-4 py-2.5 text-sm
                                  font-semibold text-white hover:bg-jingga-700 transition-colors"
                           style="border-radius: var(--sudut, 0)">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor"
                                 stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"
                                 aria-hidden="true">
                                <path d="M12 4v10m0 0l-4-4m4 4l4-4M4.5 18.5h15"/>
                            </svg>
                            Unduh PDF
                        </a>

                        <div class="flex items-center gap-2">
                            <button type="button" id="pembaca-mundur"
                                    aria-label="Halaman sebelumnya"
                                    class="grid place-items-center h-10 w-10 rounded-full
                                           bg-abu-100 text-arang hover:bg-jingga hover:text-white
                                           transition-colors disabled:opacity-30">
                                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" aria-hidden="true">
                                    <path d="M15 5l-7 7 7 7"/>
                                </svg>
                            </button>

                            <span class="text-sm font-semibold text-abu-600 tabular-nums min-w-16
                                         text-center" id="pembaca-nomor" aria-live="polite">– / –</span>

                            <button type="button" id="pembaca-maju"
                                    aria-label="Halaman berikutnya"
                                    class="grid place-items-center h-10 w-10 rounded-full
                                           bg-abu-100 text-arang hover:bg-jingga hover:text-white
                                           transition-colors disabled:opacity-30">
                                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" aria-hidden="true">
                                    <path d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <p class="mt-3 text-xs text-abu-500 text-center">
                        Seret sudut halaman untuk membalik, atau pakai panah kiri–kanan di keyboard.
                    </p>
                </div>
            </div>
        @else
            <div class="mt-8 bingkai bg-abu-50 border border-abu-200 p-10 text-center">
                <img src="{{ $publication->coverUrl() }}" alt=""
                     class="mx-auto max-h-[50vh] w-auto bingkai">
                <p class="mt-5 text-sm text-abu-500">
                    Berkas terbitan ini belum diunggah.
                </p>
            </div>
        @endif

        {{-- ── Bagikan ── --}}
        <div class="mt-8 pt-6 border-t border-abu-200">
            <x-share-buttons :judul="$publication->judulLengkap()" />
        </div>
    </article>

    {{-- ── Terbitan lain ── --}}
    @if ($lainnya->isNotEmpty())
        <section class="bg-abu-50 py-12 mt-4">
            <div class="shell">
                <x-section-head judul="Terbitan Lainnya" ikon="buku"
                                :href="route('publications.index')" />

                <div class="grid gap-6 grid-cols-2 lg:grid-cols-4">
                    @foreach ($lainnya as $t)
                        <a href="{{ route('publications.show', $t) }}" class="group block">
                            <div class="bingkai overflow-hidden bg-abu-100 aspect-[3/4]
                                        border border-abu-200 transition-shadow
                                        group-hover:shadow-angkat">
                                <img src="{{ $t->coverUrl() }}" alt="" width="600" height="800"
                                     loading="lazy" decoding="async"
                                     class="h-full w-full object-cover transition-transform
                                            duration-500 group-hover:scale-[1.03]">
                            </div>
                            @if ($t->series)
                                <p class="mt-3 label text-jingga">{{ $t->series }}</p>
                            @endif
                            <p class="mt-1 text-sm font-bold leading-snug text-arang seimbang">
                                <span class="tautan-judul">{{ $t->edition ?: $t->title }}</span>
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection

@push('kaki')
    {{-- Kedua pustaka ada di dalam proyek (public/vendor), bukan diambil dari
         CDN. pdf.js — Apache-2.0 · page-flip — MIT. --}}
    <script src="{{ asset('vendor/pdfjs/pdf.min.js') }}"></script>
    <script src="{{ asset('vendor/pageflip/page-flip.browser.js') }}"></script>

    <script>
    (function () {
        const wadah = document.getElementById('pembaca');
        if (!wadah) return;

        const ringkas = document.getElementById('pembaca-ringkas');
        const status  = document.getElementById('pembaca-status');
        const bukuBox = document.getElementById('pembaca-buku-wadah');
        const bukuEl  = document.getElementById('pembaca-buku');
        const nomorEl = document.getElementById('pembaca-nomor');
        const mundur  = document.getElementById('pembaca-mundur');
        const maju    = document.getElementById('pembaca-maju');

        const alamatPdfJs   = @json(asset('vendor/pdfjs/pdf.min.js'));
        const alamatWorker  = @json(asset('vendor/pdfjs/pdf.worker.min.js'));
        const alamatPageFlip= @json(asset('vendor/pageflip/page-flip.browser.js'));

        // Pesan kegagalan ditulis apa adanya di layar, bukan disamarkan jadi
        // kalimat umum. Tanpa ini penyebabnya cuma terlihat di Console —
        // dan pengurus tidak akan membukanya.
        function beritahu(judul, rincian) {
            status.innerHTML =
                '<span class="block font-semibold text-jingga">' + judul + '</span>' +
                '<span class="block mt-1 text-xs text-abu-500">' + rincian + '</span>';
            console.error('[pembaca publikasi]', judul, rincian);
        }

        const kurang = [];
        if (typeof pdfjsLib === 'undefined') kurang.push(alamatPdfJs);
        if (typeof St === 'undefined')       kurang.push(alamatPageFlip);

        if (kurang.length) {
            beritahu(
                'Berkas pembaca tidak ditemukan',
                'Alamat berikut tidak termuat: ' + kurang.join(' — ') +
                '. Pastikan folder public/vendor ikut tersalin ke proyek.'
            );
            return;
        }

        pdfjsLib.GlobalWorkerOptions.workerSrc = alamatWorker;

        const lebarTersedia = Math.min(bukuEl.parentElement.clientWidth - 16, 1000);
        const duaHalaman    = window.innerWidth >= 768;
        const lebarHalaman  = duaHalaman ? Math.floor(lebarTersedia / 2) : lebarTersedia;

        function gambarHalaman(pdf, n, lebar) {
            return pdf.getPage(n).then(function (hal) {
                const asli   = hal.getViewport({ scale: 1 });
                const skala  = lebar / asli.width;
                const rasio  = Math.min(window.devicePixelRatio || 1, 2);
                const tampil = hal.getViewport({ scale: skala });

                const kanvas = document.createElement('canvas');
                kanvas.width  = Math.floor(tampil.width * rasio);
                kanvas.height = Math.floor(tampil.height * rasio);

                return hal.render({
                    canvasContext: kanvas.getContext('2d'),
                    viewport: tampil,
                    transform: rasio !== 1 ? [rasio, 0, 0, rasio, 0, 0] : null,
                }).promise.then(function () {
                    return { gambar: kanvas.toDataURL('image/jpeg', 0.85), tinggi: tampil.height };
                });
            });
        }

        // Berkas PDF-nya diperiksa lebih dulu. Kalau 403 atau 404, penyebabnya
        // disebutkan langsung — biasanya symlink storage yang belum dibuat.
        fetch(wadah.dataset.berkas, { method: 'HEAD' })
            .then(function (jawab) {
                if (!jawab.ok) {
                    throw new Error('Berkas PDF menjawab ' + jawab.status);
                }
                return pdfjsLib.getDocument(wadah.dataset.berkas).promise;
            })
            .then(function (pdf) {
                const jumlah = pdf.numPages;
                status.textContent = 'Menyiapkan ' + jumlah + ' halaman…';

                const tugas = [];
                for (let n = 1; n <= jumlah; n++) {
                    tugas.push(gambarHalaman(pdf, n, lebarHalaman));
                }

                return Promise.all(tugas).then(function (hasil) {
                    const tinggiHalaman = Math.round(hasil[0].tinggi);

                    hasil.forEach(function (h) {
                        const lembar = document.createElement('div');
                        lembar.className = 'lembar-buku';
                        lembar.innerHTML = '<img src="' + h.gambar + '" alt="">';
                        bukuEl.appendChild(lembar);
                    });

                    const buku = new St.PageFlip(bukuEl, {
                        width: lebarHalaman,
                        height: tinggiHalaman,
                        size: 'stretch',
                        minWidth: 200,
                        maxWidth: 1000,
                        minHeight: 280,
                        maxHeight: 1400,
                        showCover: true,
                        usePortrait: ! duaHalaman,
                        maxShadowOpacity: 0.4,
                        mobileScrollSupport: true,
                        drawShadow: true,
                    });

                    buku.loadFromHTML(bukuEl.querySelectorAll('.lembar-buku'));

                    function perbarui() {
                        const kini = buku.getCurrentPageIndex() + 1;
                        nomorEl.textContent = kini + ' / ' + jumlah;
                        mundur.disabled = kini <= 1;
                        maju.disabled   = kini >= jumlah;
                    }

                    buku.on('flip', perbarui);
                    mundur.addEventListener('click', () => buku.flipPrev());
                    maju.addEventListener('click', () => buku.flipNext());

                    document.addEventListener('keydown', function (e) {
                        if (e.key === 'ArrowLeft')  buku.flipPrev();
                        if (e.key === 'ArrowRight') buku.flipNext();
                    });

                    perbarui();
                    ringkas.classList.add('hidden');
                    bukuBox.classList.remove('hidden');
                });
            })
            .catch(function (galat) {
                beritahu(
                    'Berkas tidak bisa dibaca di sini',
                    (galat && galat.message ? galat.message : 'Penyebab tidak diketahui') +
                    '. Silakan unduh berkasnya lewat tombol di bawah.'
                );
            });
    })();
    </script>
@endpush
