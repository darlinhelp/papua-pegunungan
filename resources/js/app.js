import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);

const kurangiGerak = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/**
 * Slider spanduk.
 * Berputar sendiri, berhenti saat disentuh tetikus / difokus keyboard,
 * dan tidak pernah berputar sendiri kalau pengguna membatasi gerak.
 */
Alpine.data('spanduk', (jumlah, jeda = 6000) => ({
    aktif: 0,
    jumlah,
    jeda,
    jalan: false,
    pencacah: null,
    dijedaManual: false,
    xAwal: 0,

    init() {
        this.mulai();

        // Hemat baterai: berhenti saat tab tidak dilihat.
        document.addEventListener('visibilitychange', () => {
            document.hidden ? this.henti() : this.mulai();
        });
    },

    mulai(manual = false) {
        if (manual) this.dijedaManual = false;
        if (this.jumlah < 2 || kurangiGerak || this.dijedaManual || this.pencacah) return;

        this.jalan = true;
        this.pencacah = setInterval(() => this.ke(this.aktif + 1), this.jeda);
    },

    henti(manual = false) {
        if (manual) this.dijedaManual = true;

        clearInterval(this.pencacah);
        this.pencacah = null;
        this.jalan = false;
    },

    /** Melingkar: dari akhir kembali ke awal, dan sebaliknya. */
    ke(tujuan) {
        this.aktif = (tujuan + this.jumlah) % this.jumlah;

        // Setel ulang hitungan supaya klik manual tidak langsung disusul pergantian otomatis.
        if (this.jalan) {
            clearInterval(this.pencacah);
            this.pencacah = setInterval(() => this.ke(this.aktif + 1), this.jeda);
        }
    },

    berikut() {
        this.ke(this.aktif + 1);
    },

    sebelum() {
        this.ke(this.aktif - 1);
    },

    sentuhMulai(e) {
        this.xAwal = e.changedTouches[0].clientX;
    },

    sentuhSelesai(e) {
        const geser = e.changedTouches[0].clientX - this.xAwal;

        // Di bawah 45px dianggap sentuhan biasa, bukan usapan.
        if (Math.abs(geser) < 45) return;

        geser < 0 ? this.berikut() : this.sebelum();
    },
}));

window.Alpine = Alpine;
Alpine.start();

/**
 * Muncul saat digulir.
 * Elemen ber-atribut data-naik akan naik pelan sekali saja saat masuk layar.
 */
document.addEventListener('DOMContentLoaded', () => {
    const target = document.querySelectorAll('[data-naik]');

    if (kurangiGerak || !('IntersectionObserver' in window)) {
        target.forEach((el) => el.classList.remove('opacity-0'));
        return;
    }

    const pengamat = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.remove('opacity-0');
                entry.target.classList.add('animate-naik');
                pengamat.unobserve(entry.target);
            });
        },
        { rootMargin: '0px 0px -10% 0px', threshold: 0.05 }
    );

    target.forEach((el) => pengamat.observe(el));
});
