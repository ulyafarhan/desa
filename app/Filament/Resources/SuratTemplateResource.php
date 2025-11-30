<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratTemplateResource\Pages;
use App\Models\SuratTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SuratTemplateResource extends Resource
{
    protected static ?string $model = SuratTemplate::class;
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('judul_surat')->required(),
                Forms\Components\TextInput::make('kode_surat')->unique(ignoreRecord: true)->required(),
                Forms\Components\Textarea::make('deskripsi')->nullable(),
                Forms\Components\TextInput::make('view_template')
                    ->label('Nama File Blade')
                    ->helperText('Contoh: templates.sktm. Template harus berada di resources/views/templates/'),
                Forms\Components\Textarea::make('form_schema')
                    ->label('Skema Input (JSON)')
                    ->helperText('Definisi input form tambahan yang dibutuhkan warga (dalam format JSON)'),
                Forms\Components\Toggle::make('is_active')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul_surat')->searchable(),
                Tables\Columns\TextColumn::make('kode_surat'),
                Tables\Columns\ToggleColumn::make('is_active'),
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
            'index' => Pages\ListSuratTemplates::route('/'),
            'create' => Pages\CreateSuratTemplate::route('/create'),
            'edit' => Pages\EditSuratTemplate::route('/{record}/edit'),
        ];
    }
}