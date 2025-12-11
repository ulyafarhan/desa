<?php

namespace App\Filament\Widgets;

use App\Models\SuratRequest;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class SuratChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Pengajuan Surat (Tahun Ini)';
    protected static ?int $sort = 2; // Urutan tampilan di dashboard

    protected function getData(): array
    {
        $data = Trend::model(SuratRequest::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Surat',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#3b82f6', // Biru
                    'borderColor' => '#3b82f6',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line'; 
    }
}