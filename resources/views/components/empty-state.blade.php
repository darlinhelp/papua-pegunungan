@props(['judul' => 'Belum ada isi di sini', 'pesan' => null, 'aksi' => null, 'aksiLabel' => null])

<div class="text-center py-20 border border-dashed border-abu-300">
    <span class="inline-block h-1 w-10 bg-jingga mb-4" aria-hidden="true"></span>
    <p class="text-xl font-bold text-arang">{{ $judul }}</p>
    @if ($pesan)
        <p class="mt-2 text-sm text-abu-500 max-w-md mx-auto">{{ $pesan }}</p>
    @endif
    @if ($aksi)
        <a href="{{ $aksi }}"
           class="mt-6 inline-flex bg-jingga px-6 py-3 text-sm font-semibold text-white
                  hover:bg-jingga-700 transition-colors">
            {{ $aksiLabel ?? 'Kembali ke beranda' }}
        </a>
    @endif
</div>
