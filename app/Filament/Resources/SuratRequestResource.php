<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratRequestResource\Pages;
use App\Models\SuratRequest;
use App\Models\PejabatDesa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification; // Wajib diimpor untuk notifikasi

class SuratRequestResource extends Resource
{
    protected static ?string $model = SuratRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Permintaan Surat';
    protected static ?string $pluralLabel = 'Permintaan Surat';
    protected static ?string $navigationGroup = 'Pelayanan';

    // Optimasi NFR: Eager Load User dan Template
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user', 'template']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Info Pemohon')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name') 
                            ->label('Warga Pemohon')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn (?SuratRequest $record) => $record !== null),
                            
                        Forms\Components\TextInput::make('user.nik')
                            ->label('NIK')
                            ->disabled()
                            ->dehydrated(false) 
                            ->visible(fn (?SuratRequest $record) => $record !== null),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Surat')
                    ->schema([
                        Forms\Components\Select::make('template_id')
                            ->relationship('template', 'judul_surat')
                            ->label('Jenis Surat')
                            ->required()
                            ->reactive() 
                            ->disabled(fn (?SuratRequest $record) => $record && $record->status !== 'pending'),
                            
                        Forms\Components\KeyValue::make('data_input')
                            ->label('Data Isian Warga')
                            ->helperText('Klik "Add Row". Contoh -> Key: "keperluan", Value: "Beasiswa"')
                            ->required(),
                    ]),

                Forms\Components\Section::make('Area Persetujuan')
                    ->schema([
                        Forms\Components\Select::make('pejabat_id')
                            ->label('Penanda Tangan')
                            ->options(PejabatDesa::where('status_aktif', true)->pluck('nama_pejabat', 'id'))
                            ->visible(fn (?SuratRequest $record) => $record === null || $record->status === 'pending'),
                            
                        Forms\Components\Textarea::make('catatan_admin')
                            ->label('Catatan (Opsional)')
                            ->rows(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Tanggal Masuk')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->searchable()->label('Pemohon'),
                Tables\Columns\TextColumn::make('template.judul_surat')->label('Jenis Surat'),
                Tables\Columns\TextColumn::make('pejabat.jabatan')->label('Penanda Tangan')->default('N/A'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'in_queue',
                        'primary' => 'processing',
                        'success' => 'completed',
                        'danger' => 'rejected',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('view_pdf')
                    ->label('Lihat Surat')
                    ->icon('heroicon-o-document-magnifying-glass')
                    ->color('info')
                    ->url(fn (SuratRequest $record) => route('dokumen.show', $record)) 
                    ->openUrlInNewTab() 
                    ->visible(fn (SuratRequest $record) => $record->status === 'completed'),
                    
                // ACTION: SETUJUI (GATEKEEPER)
                Action::make('approve')
                    ->label('Setujui & Proses')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Select::make('pejabat_id')
                            ->label('Pilih Pejabat Penanda Tangan')
                            ->options(PejabatDesa::where('status_aktif', true)->pluck('nama_pejabat', 'id'))
                            ->required(),
                    ])
                    ->action(function (SuratRequest $record, array $data) {
                        $record->update([
                            'status' => 'in_queue',
                            'pejabat_id' => $data['pejabat_id']
                        ]);
                    })
                    ->visible(fn (SuratRequest $record) => $record->status === 'pending'),

                // ACTION: TOLAK
                Action::make('reject')
                    ->label('Tolak')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->form([
                        Forms\Components\Textarea::make('alasan')->required()->label('Alasan Penolakan'),
                    ])
                    ->action(function (SuratRequest $record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'catatan_admin' => $data['alasan']
                        ]);
                    })
                    ->visible(fn (SuratRequest $record) => $record->status === 'pending'),
                
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    
                    // 1. BULK ACTION: SETUJUI MASSAL
                    Tables\Actions\BulkAction::make('batch_approve')
                        ->label('Setujui Massal & Kirim ke Antrian')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->form([
                            Forms\Components\Select::make('pejabat_id')
                                ->label('Pilih Pejabat Penanda Tangan')
                                ->options(PejabatDesa::where('status_aktif', true)->pluck('nama_pejabat', 'id'))
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $processedCount = 0;
                            
                            $records->each(function (SuratRequest $record) use ($data, &$processedCount) {
                                if ($record->status === 'pending') {
                                    $record->update([
                                        'status' => 'in_queue',
                                        'pejabat_id' => $data['pejabat_id'],
                                    ]);
                                    $processedCount++;
                                }
                            });
                            
                            Notification::make()
                                ->title('Persetujuan Massal Berhasil')
                                ->body($processedCount . ' permintaan surat telah dikirim ke Antrian.')
                                ->success()
                                ->send();
                        })
                        // FIX: Hapus type hint Collection dan gunakan null-safe operator
                        ->visible(fn (?Collection $records) => 
                            $records?->contains(fn ($record) => $record->status === 'pending')
                        ),

                    // 2. BULK ACTION: TOLAK MASSAL
                    Tables\Actions\BulkAction::make('batch_reject')
                        ->label('Tolak Massal')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->form([
                            Forms\Components\Textarea::make('alasan')
                                ->required()
                                ->label('Alasan Penolakan Massal'),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $processedCount = 0;
                            
                            $records->each(function (SuratRequest $record) use ($data, &$processedCount) {
                                if ($record->status === 'pending') {
                                    $record->update([
                                        'status' => 'rejected',
                                        'catatan_admin' => $data['alasan']
                                    ]);
                                    $processedCount++;
                                }
                            });
                            
                            Notification::make()
                                ->title('Penolakan Massal Berhasil')
                                ->body($processedCount . ' permintaan surat berhasil ditolak.')
                                ->danger()
                                ->send();
                        })
                        // FIX: Hapus type hint Collection dan gunakan null-safe operator
                        ->visible(fn (?Collection $records) => 
                            $records?->contains(fn ($record) => $record->status === 'pending')
                        ),
                        
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuratRequests::route('/'),
            'create' => Pages\CreateSuratRequest::route('/create'),
            'edit' => Pages\EditSuratRequest::route('/{record}/edit'),
        ];
    }
}