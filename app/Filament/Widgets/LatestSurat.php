<?php

namespace App\Filament\Widgets;

use App\Models\SuratRequest;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestSurat extends BaseWidget
{
    protected static ?int $sort = 3; // Tampil di paling bawah
    protected int | string | array $columnSpan = 'full'; // Agar tabel lebar penuh

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Ambil 5 surat terakhir
                SuratRequest::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pemohon')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('template.nama_surat')
                    ->label('Jenis Surat'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'selesai' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('Lihat')
                    ->url(fn (SuratRequest $record): string => route('filament.admin.resources.surat-requests.edit', $record))
                    ->icon('heroicon-m-eye')
                    ->button()
                    ->outlined()
                    ->size('xs'),
            ]);
    }
}