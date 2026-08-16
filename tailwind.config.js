/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                // Warna aksen — diatur dari panel admin (Pengaturan → Identitas
                // Situs). rgb(var(...) / <alpha-value>) mempertahankan modifier
                // transparansi seperti bg-jingga/50; nilai variabelnya sendiri
                // dicetak di <head> lewat App\Support\Warna.
                jingga: {
                    DEFAULT: 'rgb(var(--warna-aksen) / <alpha-value>)',
                    500: 'rgb(var(--warna-aksen) / <alpha-value>)',
                    600: 'rgb(var(--warna-aksen-gelap) / <alpha-value>)',
                    700: 'rgb(var(--warna-aksen-lebih-gelap) / <alpha-value>)',
                    50:  '#FFF3ED',
                },
                // Netral: abu-50 (latar bagian selang-seling) ikut pengaturan;
                // sisanya tetap abu tetap — hanya dipakai untuk garis dan teks
                // pembantu, tidak relevan dengan "warna latar".
                abu: {
                    50:  'rgb(var(--warna-latar) / <alpha-value>)',
                    100: '#EFEFF1',
                    200: '#E4E4E7',
                    300: '#D4D4D8',
                    400: '#A1A1AA',
                    500: '#71717A',
                    600: '#52525B',
                },
                arang: {
                    // DEFAULT dipakai lewat kelas text-arang untuk judul dan isi
                    // teks — inilah "warna teks" yang diatur dari panel admin.
                    DEFAULT: 'rgb(var(--warna-teks) / <alpha-value>)',
                    // 800/900/950 tetap tetap: dipakai untuk latar gelap
                    // (footer, kutipan), bukan warna teks, jadi tidak ikut
                    // pengaturan supaya tidak berubah jadi warna aksen.
                    800: '#27272A',
                    900: '#18181B',
                    950: '#0F0F11',
                },
            },
            fontFamily: {
                // Nilainya diisi variabel CSS yang dicetak di <head>, supaya
                // font bisa diganti dari panel admin tanpa build ulang.
                sans: ['var(--font-situs)', 'system-ui', 'sans-serif'],
                baca: ['var(--font-situs)', 'system-ui', 'sans-serif'],
                display: ['var(--font-situs)', 'system-ui', 'sans-serif'],
            },
            fontSize: {
                '2xs': ['0.6875rem', { lineHeight: '1rem' }],
            },
            letterSpacing: {
                label: '0.1em',
            },
            maxWidth: {
                shell: '1200px',
                // Lebar spanduk mengikuti pks.id: 1340 px isi + padding kiri-kanan.
                spanduk: '1404px',
                baca: '70ch',
            },
            boxShadow: {
                kartu: '0 1px 3px rgba(24,24,27,.08)',
                angkat: '0 4px 16px -4px rgba(24,24,27,.18)',
            },
            keyframes: {
                naik: {
                    from: { opacity: '0', transform: 'translateY(8px)' },
                    to: { opacity: '1', transform: 'none' },
                },
                isi: {
                    from: { width: '0%' },
                    to: { width: '100%' },
                },
            },
            animation: {
                naik: 'naik .4s ease-out both',
                isi: 'isi linear forwards',
            },
        },
    },
    plugins: [],
};
