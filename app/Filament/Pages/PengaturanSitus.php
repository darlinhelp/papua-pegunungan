<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Support\Ajakan;
use App\Support\Font;
use App\Support\Kop;
use App\Support\Layout;
use App\Support\Sosial;
use App\Support\Spanduk;
use App\Support\Sudut;
use App\Support\Warna;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class PengaturanSitus extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Identitas Situs';

    protected static ?string $title = 'Identitas Situs';

    protected string $view = 'filament.pages.pengaturan-situs';

    // Warna, font, layout, dan identitas situs berlaku ke seluruh
    // pengunjung — hanya Administrator yang boleh mengubahnya. Editor dan
    // Penulis tidak melihat menu ini sama sekali di sidebar.
    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    /** @var array<string, mixed> */
    public ?array $data = [];

    /**
     * Semua kunci yang boleh diubah lewat halaman ini:
     * kontak sekretariat + satu kolom untuk tiap kanal media sosial.
     *
     * @return list<string>
     */
    protected function kunci(): array
    {
        $sosial = array_map(
            fn (string $k) => 'sosial_' . $k,
            array_keys(Sosial::daftar())
        );

        return array_merge(['alamat', 'telepon', 'surel', 'font_situs', 'skala_teks', 'rasio_spanduk', 'sudut_gambar',
            'nama_singkat', 'nama_lengkap', 'tagline', 'ukuran_nama_situs', 'footer_tema',
            'warna_aksen', 'warna_latar', 'warna_teks',
            'skala_judul_beranda', 'skala_judul_artikel',
            'logo_situs', 'logo_footer', 'logo_tinggi', 'logo_tinggi_kecil',
            'kop_tinggi', 'kop_tinggi_kecil', 'kop_lebar',
            'layout_beranda', 'layout_header_pita', 'layout_daftar',
            'kartu_ajakan',
            'gaya_pita_atas',
            'gaya_tautan_judul',
        ], $sosial);
    }

    public function mount(): void
    {
        $isi = [];

        foreach ($this->kunci() as $kunci) {
            $isi[$kunci] = Setting::get($kunci);
        }

        // layout_beranda disimpan sebagai teks JSON (array tidak bisa disimpan
        // langsung di kolom pengaturan yang berbentuk teks), jadi diuraikan
        // dulu di sini sebelum masuk ke formulir.
        $terurai = json_decode($isi['layout_beranda'] ?? '', true);
        $isi['layout_beranda'] = is_array($terurai) && $terurai !== []
            ? $terurai
            : Layout::blokBawaan();

        // Sama untuk kartu ajakan — juga sebuah daftar, bukan nilai tunggal.
        $isi['kartu_ajakan'] = Ajakan::daftar();

        $this->form->fill($isi);
    }

    public function form(Schema $schema): Schema
    {
        // Satu kolom URL untuk tiap kanal, dibuat dari daftar di App\Support\Sosial
        // supaya menambah kanal baru cukup diedit di satu tempat.
        $kolomSosial = [];

        foreach (Sosial::daftar() as $kunci => $kanal) {
            $kolomSosial[] = TextInput::make('sosial_' . $kunci)
                ->label($kanal['nama'])
                ->url()
                ->maxLength(300)
                ->placeholder($kanal['contoh'])
                ->prefixIcon('heroicon-o-link');
        }

        return $schema
            ->components([
                Section::make('Identitas')
                    ->description('Tampil di bilah kepala, footer, dan judul tab browser.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama_singkat')
                            ->label('Baris kecil di atas nama')
                            ->maxLength(60)
                            ->placeholder('Dewan Pengurus Wilayah')
                            ->live(onBlur: true),

                        TextInput::make('nama_lengkap')
                            ->label('Nama situs')
                            ->maxLength(80)
                            ->required()
                            ->placeholder('PKS Papua Pegunungan')
                            ->live(onBlur: true),

                        TextInput::make('tagline')
                            ->label('Tagline pita atas')
                            ->maxLength(100)
                            ->placeholder('Kenali PKS Papua Pegunungan lebih dekat')
                            ->columnSpanFull(),

                        Select::make('ukuran_nama_situs')
                            ->label('Ukuran font nama situs')
                            ->options(Font::pilihanUkuranNama())
                            ->default(Font::NAMA_UKURAN_BAWAAN)
                            ->selectablePlaceholder(false)
                            ->native(false)
                            ->required()
                            ->live()
                            ->helperText('Hanya mengubah ukuran teks nama, terpisah dari pengaturan ukuran teks umum.'),

                        Select::make('footer_tema')
                            ->label('Tema footer')
                            ->options(Kop::pilihanFooterTema())
                            ->default(Kop::FOOTER_TEMA_BAWAAN)
                            ->selectablePlaceholder(false)
                            ->native(false)
                            ->required()
                            ->live()
                            ->helperText('Pilih Terang kalau logo Anda berwarna gelap dan sulit terlihat di footer gelap. Logo tetap diberi bantalan putih di kedua tema.'),
                    ]),

                Section::make('Kontak Sekretariat')
                    ->description('Tampil di bagian bawah setiap halaman.')
                    ->schema([
                        TextInput::make('alamat')
                            ->label('Alamat')
                            ->maxLength(300)
                            ->required(),

                        TextInput::make('telepon')
                            ->label('Telepon')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('surel')
                            ->label('Surel')
                            ->email()
                            ->maxLength(150),
                    ]),

                Section::make('Logo')
                    ->description('Kosongkan untuk memakai logo bawaan yang ada di public/img.')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('logo_situs')
                            ->label('Logo bilah kepala')
                            ->image()
                            ->disk('public')
                            ->directory('logo')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->panelLayout('integrated')
                            ->removeUploadedFileButtonPosition('right')
                            ->live()
                            ->helperText('Latar bilah kepala putih. Format PNG transparan atau SVG.'),

                        FileUpload::make('logo_footer')
                            ->label('Logo footer')
                            ->image()
                            ->disk('public')
                            ->directory('logo')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->panelLayout('integrated')
                            ->removeUploadedFileButtonPosition('right')
                            ->helperText('Latar footer gelap, jadi biasanya perlu versi putih. Kosong = pakai logo bilah kepala.'),
                    ]),

                Section::make('Ukuran Bilah Kepala')
                    ->description('Bilah kepala menyusut saat halaman digulir. Dua nilai di tiap baris: sebelum dan sesudah menyusut.')
                    ->schema([
                        View::make('filament.pages.partials.pratinjau-kop'),

                        Section::make()
                            ->columns(2)
                            ->schema([
                                Slider::make('logo_tinggi')
                                    ->label('Tinggi logo — sebelum digulir')
                                    ->range(minValue: 24, maxValue: 96)
                                    ->step(2)
                                    ->default(Kop::LOGO_TINGGI)
                                    ->live(),

                                Slider::make('logo_tinggi_kecil')
                                    ->label('Tinggi logo — setelah digulir')
                                    ->range(minValue: 20, maxValue: 80)
                                    ->step(2)
                                    ->default(Kop::LOGO_TINGGI_KECIL)
                                    ->live(),

                                Slider::make('kop_tinggi')
                                    ->label('Tinggi bilah — sebelum digulir')
                                    ->range(minValue: 56, maxValue: 140)
                                    ->step(4)
                                    ->default(Kop::KOP_TINGGI)
                                    ->live(),

                                Slider::make('kop_tinggi_kecil')
                                    ->label('Tinggi bilah — setelah digulir')
                                    ->range(minValue: 44, maxValue: 120)
                                    ->step(4)
                                    ->default(Kop::KOP_TINGGI_KECIL)
                                    ->live(),

                                Select::make('kop_lebar')
                                    ->label('Lebar bilah kepala')
                                    ->options(Kop::pilihanLebar())
                                    ->default(Kop::KOP_LEBAR)
                                    ->selectablePlaceholder(false)
                                    ->native(false)
                                    ->required()
                                    ->helperText('Menentukan sampai mana logo dan menu merentang.')
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Section::make('Tampilan')
                    ->description('Berlaku langsung setelah disimpan — tidak perlu build ulang.')
                    ->schema([
                        Select::make('font_situs')
                            ->label('Jenis font')
                            ->options(Font::pilihan())
                            ->default(Font::BAWAAN)
                            ->selectablePlaceholder(false)
                            ->native(false)
                            ->required()
                            ->helperText('Pilihan terakhir tidak mengunduh font apa pun, jadi halaman terbuka paling cepat.'),

                        Select::make('skala_teks')
                            ->label('Ukuran teks')
                            ->options(Font::pilihanSkala())
                            ->default(Font::SKALA_BAWAAN)
                            ->selectablePlaceholder(false)
                            ->native(false)
                            ->required()
                            ->helperText('Menggeser semua teks sekaligus. Lebar halaman tidak ikut berubah.'),

                        Select::make('sudut_gambar')
                            ->label('Sudut gambar & kartu')
                            ->options(Sudut::pilihan())
                            ->default(Sudut::BAWAAN)
                            ->selectablePlaceholder(false)
                            ->native(false)
                            ->required()
                            ->helperText('Berlaku ke semua gambar sekaligus: sampul berita, foto album, video, publikasi, spanduk, dan gambar di dalam artikel.'),

                        Select::make('rasio_spanduk')
                            ->label('Ukuran spanduk beranda')
                            ->options(Spanduk::pilihan())
                            ->default(Spanduk::BAWAAN)
                            ->selectablePlaceholder(false)
                            ->native(false)
                            ->required()
                            ->helperText('Lebar 1340 px, sama seperti pks.id. Situs DPP sendiri tidak mematok tinggi — gambarnya diperkecil menurut lebar saja, tingginya ikut. Karena itu pilihan pertama yang paling menyerupai.'),

                        Select::make('skala_judul_beranda')
                            ->label('Ukuran judul di beranda')
                            ->options(Font::pilihanSkalaJudul())
                            ->default(Font::SKALA_JUDUL_BAWAAN)
                            ->selectablePlaceholder(false)
                            ->native(false)
                            ->required()
                            ->helperText('Judul tiap seksi di beranda — Kabar DPW, Berita Pilihan, dan seterusnya.'),

                        Select::make('skala_judul_artikel')
                            ->label('Ukuran judul di halaman berita')
                            ->options(Font::pilihanSkalaJudul())
                            ->default(Font::SKALA_JUDUL_BAWAAN)
                            ->selectablePlaceholder(false)
                            ->native(false)
                            ->required()
                            ->helperText('Judul besar di bagian atas saat satu berita dibuka.'),
                    ]),

                Section::make('Warna')
                    ->description('Bawaannya sudah warna resmi PKS ('.Warna::AKSEN.'). Berlaku ke seluruh situs sekaligus, tanpa build ulang. Tombol "Warna Bawaan PKS" di kanan atas mengembalikannya kapan saja.')
                    ->columns(3)
                    ->schema([
                        ColorPicker::make('warna_aksen')
                            ->label('Warna aksen')
                            ->default(Warna::AKSEN)
                            ->required()
                            ->helperText('Tombol, tautan aktif, garis penanda. Versi lebih gelapnya untuk hover dibuat otomatis.'),

                        ColorPicker::make('warna_latar')
                            ->label('Warna latar bagian')
                            ->default(Warna::LATAR)
                            ->required()
                            ->helperText('Latar bagian yang berselang-seling dengan putih, mis. Berita Pilihan.'),

                        ColorPicker::make('warna_teks')
                            ->label('Warna judul & isi teks')
                            ->default(Warna::TEKS)
                            ->required()
                            ->helperText('Tidak berlaku ke latar gelap (footer, kutipan) — itu tetap tetap.'),

                        Select::make('gaya_pita_atas')
                            ->label('Gaya Pita Atas')
                            ->options(Warna::pilihanGayaPita())
                            ->default(Warna::GAYA_PITA_BAWAAN)
                            ->selectablePlaceholder(false)
                            ->native(false)
                            ->required()
                            ->columnSpan(3)
                            ->helperText('Warna baris tipis paling atas berisi tagline "Kenali PKS Papua Pegunungan lebih dekat" dan ikon media sosial.'),
                    ]),

                Section::make('Layout')
                    ->description('Berlaku langsung setelah disimpan, tanpa build ulang.')
                    ->schema([
                        CheckboxList::make('layout_beranda')
                            ->label('Blok yang tampil di beranda')
                            ->options(Layout::daftarBlokBeranda())
                            ->default(Layout::blokBawaan())
                            ->columns(2)
                            ->bulkToggleable()
                            ->helperText('Blok yang dicentang saja yang tampil. Urutannya tetap mengikuti susunan bawaan — pengaturan urutan bebas belum tersedia di versi ini.'),

                        Toggle::make('layout_header_pita')
                            ->label('Tampilkan pita atas')
                            ->helperText('Baris hitam paling atas berisi tagline dan ikon media sosial.')
                            ->default(true)
                            ->afterStateHydrated(fn ($component, $state) => $component->state($state !== 'sembunyi'))
                            ->dehydrateStateUsing(fn ($state) => $state ? 'tampil' : 'sembunyi'),

                        Select::make('layout_daftar')
                            ->label('Tampilan daftar berita')
                            ->options(Layout::pilihanDaftar())
                            ->default(Layout::DAFTAR_BAWAAN)
                            ->selectablePlaceholder(false)
                            ->native(false)
                            ->required()
                            ->helperText('Berlaku di halaman kategori dan hasil pencarian.'),

                        Select::make('gaya_tautan_judul')
                            ->label('Gaya judul saat disentuh (hover)')
                            ->options(Layout::pilihanGayaTautan())
                            ->default(Layout::GAYA_TAUTAN_BAWAAN)
                            ->selectablePlaceholder(false)
                            ->native(false)
                            ->required()
                            ->helperText('Berlaku ke semua judul berita, video, foto, dan publikasi di seluruh situs.'),
                    ]),

                Section::make('Kartu Ajakan')
                    ->description('Lima kartu di bagian paling bawah beranda (Gabung, Sumbangan, dst).')
                    ->schema([
                        Repeater::make('kartu_ajakan')
                            ->label('')
                            ->default(Ajakan::bawaan())
                            ->minItems(2)
                            ->maxItems(6)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => trim(
                                ($state['baris1'] ?? '').' '.($state['baris2'] ?? '')
                            ) ?: 'Kartu baru')
                            ->addActionLabel('Tambah kartu')
                            ->schema([
                                TextInput::make('baris1')
                                    ->label('Baris pertama')
                                    ->required()
                                    ->maxLength(30)
                                    ->placeholder('GABUNG')
                                    ->helperText('Tampil abu-abu, biasanya kata tindakan.'),

                                TextInput::make('baris2')
                                    ->label('Baris kedua')
                                    ->required()
                                    ->maxLength(30)
                                    ->placeholder('DPW')
                                    ->helperText('Tampil tebal hitam, di bawah baris pertama.'),

                                Select::make('ikon')
                                    ->label('Ikon')
                                    ->options(\App\Support\Ikon::pilihan())
                                    ->searchable()
                                    ->native(false)
                                    ->placeholder('Tanpa ikon')
                                    ->helperText('Tampil dalam lingkaran di samping teks.'),

                                Textarea::make('isi')
                                    ->label('Keterangan singkat')
                                    ->required()
                                    ->rows(2)
                                    ->maxLength(200)
                                    ->columnSpanFull(),

                                TextInput::make('href')
                                    ->label('Tautan tujuan')
                                    ->required()
                                    ->maxLength(300)
                                    ->placeholder('/halaman/sumbangan  atau  https://daftar.pks.id')
                                    ->columnSpanFull(),

                                Toggle::make('luar')
                                    ->label('Buka di tab baru')
                                    ->helperText('Aktifkan untuk tautan yang menuju situs lain.'),
                            ])
                            ->columns(2),
                    ]),

                Section::make('Media Sosial')
                    ->description('Kanal yang dikosongkan tidak akan muncul di situs — tidak ada ikon mati.')
                    ->columns(2)
                    ->schema($kolomSosial),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('resetWarna')
                ->label('Warna Bawaan PKS')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Kembalikan ke warna bawaan PKS?')
                ->modalDescription('Warna aksen, latar, dan teks akan diisi ulang dengan warna resmi PKS ('.Warna::AKSEN.'). Formulirnya belum tersimpan sampai Anda menekan tombol Simpan di bawah.')
                ->modalSubmitActionLabel('Ya, isi ulang')
                ->action(function () {
                    $this->data['warna_aksen'] = Warna::AKSEN;
                    $this->data['warna_latar'] = Warna::LATAR;
                    $this->data['warna_teks']  = Warna::TEKS;

                    // Mengisi ulang formulir supaya ketiga color picker langsung
                    // menampilkan warna barunya, tanpa perlu memuat ulang halaman.
                    $this->form->fill($this->data);

                    Notification::make()
                        ->title('Warna diisi ulang')
                        ->body('Tekan Simpan di bagian bawah untuk menerapkannya ke situs.')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('simpan')
                ->label('Simpan')
                ->submit('simpan'),
        ];
    }

    public function simpan(): void
    {
        $isi = $this->form->getState();

        foreach ($this->kunci() as $kunci) {
            $nilai = $isi[$kunci] ?? null;

            if ($kunci === 'layout_beranda') {
                $nilai = json_encode(array_values($nilai ?: Layout::blokBawaan()));
            }

            if ($kunci === 'kartu_ajakan') {
                $nilai = json_encode(array_values($nilai ?: Ajakan::bawaan()));
            }

            Setting::put($kunci, $nilai);
        }

        Notification::make()
            ->title('Pengaturan tersimpan')
            ->success()
            ->send();
    }
}
