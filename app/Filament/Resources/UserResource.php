<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\BulkAction; // Import BulkAction
use Illuminate\Database\Eloquent\Collection; // Import Collection
use Filament\Notifications\Notification; // Import Notification
use Illuminate\Support\Carbon; // Import Carbon untuk timestamp

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Manajemen Warga';
    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?string $pluralLabel = 'Pengguna';

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
                            ->rows(2),
                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir'),
                        Forms\Components\TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir'),
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
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create'),
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    
                    // BULK ACTION: VERIFIKASI MASSAL
                    BulkAction::make('verifyUsers')
                        ->label('Verifikasi Akun Massal')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $processedCount = 0;
                            
                            $records->each(function (User $record) use (&$processedCount) {
                                // Hanya verifikasi yang statusnya pending
                                if ($record->status_akun === 'pending') {
                                    $record->update([
                                        'status_akun' => 'verified',
                                        'email_verified_at' => Carbon::now(), // Set email verified
                                    ]);
                                    $processedCount++;
                                }
                            });
                            
                            Notification::make()
                                ->title('Verifikasi Massal Berhasil')
                                ->body($processedCount . ' akun pengguna berhasil diverifikasi.')
                                ->success()
                                ->send();
                        })
                        // FIX: Agar tidak error null, gunakan null-safe operator
                        ->visible(fn (?Collection $records) => 
                            $records?->contains(fn ($record) => $record->status_akun === 'pending')
                        ),
                        
                    // BULK ACTION: BLOKIR MASSAL
                    BulkAction::make('blockUsers')
                        ->label('Blokir Akun Massal')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $processedCount = 0;
                            $records->each(function (User $record) use (&$processedCount) {
                                if ($record->status_akun !== 'blocked') {
                                    $record->update(['status_akun' => 'blocked']);
                                    $processedCount++;
                                }
                            });
                            
                            Notification::make()
                                ->title('Blokir Massal Berhasil')
                                ->body($processedCount . ' akun pengguna berhasil diblokir.')
                                ->danger()
                                ->send();
                        }),
                        
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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