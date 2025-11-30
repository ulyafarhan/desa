<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PanduanResource\Pages;
use App\Filament\Resources\PanduanResource\RelationManagers;
use App\Models\Panduan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PanduanResource extends Resource
{
    protected static ?string $model = Panduan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Konten Panduan')
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->label('Judul Panduan')
                            ->required()
                            ->placeholder('Contoh: Cara Membuat SKTM'),
                        
                        Forms\Components\Select::make('icon')
                            ->label('Ikon')
                            ->options([
                                'FileText' => 'Dokumen (FileText)',
                                'CreditCard' => 'Kartu/KTP (CreditCard)',
                                'User' => 'Orang (User)',
                                'Home' => 'Rumah (Home)',
                                'Briefcase' => 'Bisnis (Briefcase)',
                            ])
                            ->default('FileText')
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Tampilkan di Website')
                            ->default(true),

                        // REPEATER UNTUK LANGKAH-LANGKAH
                        Forms\Components\Repeater::make('langkah_langkah')
                            ->label('Daftar Langkah / Prosedur')
                            ->schema([
                                Forms\Components\Textarea::make('isi')
                                    ->label('Deskripsi Langkah')
                                    ->required()
                                    ->rows(2),
                            ])
                            ->addActionLabel('Tambah Langkah')
                            ->defaultItems(3)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->searchable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('langkah_langkah')
                    ->label('Jumlah Langkah')
                    ->formatStateUsing(function ($state) {
                        // Jika data berupa string (JSON), decode dulu
                        if (is_string($state)) {
                            $state = json_decode($state, true);
                        }
                        // Jika data array, hitung. Jika null/error, anggap 0.
                        $count = is_array($state) ? count($state) : 0;
                        return $count . ' Tahap';
                    }),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPanduans::route('/'),
            'create' => Pages\CreatePanduan::route('/create'),
            'edit' => Pages\EditPanduan::route('/{record}/edit'),
        ];
    }
}
