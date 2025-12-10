<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PejabatDesaResource\Pages;
use App\Models\PejabatDesa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PejabatDesaResource extends Resource
{
    protected static ?string $model = PejabatDesa::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Pejabat Desa';

    protected static ?string $pluralLabel = 'Pejabat Desa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_pejabat')->required(),
                Forms\Components\TextInput::make('nip')->label('NIP')->nullable(),
                Forms\Components\TextInput::make('jabatan')->required(),
                Forms\Components\FileUpload::make('path_gambar_ttd')
                    ->label('Tanda Tangan (PNG Transparan)')
                    ->directory('pejabat/ttd')
                    ->acceptedFileTypes(['image/png'])
                    ->image(),
                Forms\Components\FileUpload::make('path_gambar_stempel')
                    ->label('Stempel Desa (PNG Transparan)')
                    ->directory('pejabat/stempel')
                    ->acceptedFileTypes(['image/png'])
                    ->image(),
                Forms\Components\Toggle::make('status_aktif')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pejabat')->searchable(),
                Tables\Columns\TextColumn::make('jabatan'),
                Tables\Columns\ToggleColumn::make('status_aktif'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPejabatDesas::route('/'),
            'create' => Pages\CreatePejabatDesa::route('/create'),
            'edit' => Pages\EditPejabatDesa::route('/{record}/edit'),
        ];
    }
}