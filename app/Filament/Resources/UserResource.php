<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Support\Peran;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengguna';

    protected static ?string $modelLabel = 'Pengguna';

    protected static ?string $pluralModelLabel = 'Pengguna';

    protected static ?string $recordTitleAttribute = 'name';

    // Manajemen pengguna hanya untuk Administrator. Editor dan Penulis tidak
    // melihat menu ini sama sekali di sidebar.
    public static function canViewAny(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->schema([

                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(100),

                TextInput::make('email')
                    ->label('Surel')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(150),

                Select::make('role')
                    ->label('Peran')
                    ->options(Peran::daftar())
                    // Nilai bawaan di formulir sengaja Penulis — hak paling
                    // kecil — supaya akun baru tidak sengaja diberi akses
                    // penuh kalau pengurus lupa mengubah pilihannya.
                    ->default(Peran::PENULIS)
                    ->required()
                    ->native(false)
                    ->disabled(fn (?User $record) => $record?->getKey() === auth()->id())
                    ->helperText(fn (?User $record) => $record?->getKey() === auth()->id()
                        ? 'Anda tidak bisa mengubah peran akun sendiri.'
                        : 'Administrator: akses penuh. Editor: kelola semua konten. Penulis: hanya berita sendiri.')
                    ->columnSpanFull(),

                TextInput::make('password')
                    ->label('Kata sandi')
                    ->password()
                    ->revealable()
                    ->minLength(8)
                    // Wajib saat membuat baru; saat menyunting, kosong berarti
                    // kata sandi lama dipertahankan.
                    ->required(fn (string $operation) => $operation === 'create')
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->dehydrateStateUsing(fn (string $state) => Hash::make($state))
                    ->helperText('Minimal 8 karakter. Kosong saat menyunting = tidak diganti.')
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Surel')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('role')
                    ->label('Peran')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Peran::label($state))
                    ->color(fn (string $state) => Peran::warna($state)),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date('j M Y')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                // Pengguna tidak bisa menghapus akunnya sendiri.
                DeleteAction::make()
                    ->visible(fn (User $record) => $record->getKey() !== auth()->id()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
