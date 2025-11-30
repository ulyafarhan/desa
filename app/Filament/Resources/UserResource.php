<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Manajemen Warga';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Kependudukan Wajib')
                    ->schema([
                        Forms\Components\TextInput::make('nik')
                            ->label('NIK (Nomor Induk Kependudukan)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->numeric()
                            ->maxLength(16),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('alamat_lengkap')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->rows(2),
                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required(),
                        Forms\Components\TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Sistem & Akses')
                    ->schema([
                        Forms\Components\Select::make('role')
                            ->label('Peran Akun')
                            ->options([
                                'admin' => 'Administrator',
                                'staff' => 'Staf Desa',
                                'warga' => 'Warga (Pemohon)',
                            ])
                            ->required(),
                        Forms\Components\Select::make('status_akun')
                            ->label('Status Akun')
                            ->options([
                                'pending' => 'Pending (Menunggu Verifikasi)',
                                'verified' => 'Terverifikasi',
                                'blocked' => 'Diblokir',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('password')
                            ->label('Password (Isi jika ingin ubah)')
                            ->password()
                            ->dehydrated(fn (?string $state): bool => filled($state)) // Hanya simpan jika diisi
                            ->required(fn (string $operation): bool => $operation === 'create'), // Wajib saat membuat baru
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nik')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\BadgeColumn::make('role')->colors(['warning' => 'warga', 'success' => 'admin', 'primary' => 'staff']),
                Tables\Columns\BadgeColumn::make('status_akun')->colors(['warning' => 'pending', 'success' => 'verified', 'danger' => 'blocked']),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')->options(['admin' => 'Admin', 'warga' => 'Warga']),
                Tables\Filters\SelectFilter::make('status_akun')->options(['verified' => 'Terverifikasi', 'pending' => 'Pending']),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}