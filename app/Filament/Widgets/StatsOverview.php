<?php

namespace App\Filament\Widgets;

use App\Models\SuratRequest;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Warga', User::where('role', 'warga')->count())
                ->description('Warga terverifikasi')
                ->descriptionIcon('heroicon-m-users')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('Permohonan Masuk', SuratRequest::where('status', 'pending')->count())
                ->description('Perlu segera diproses')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color('warning'), 

            Stat::make('Surat Selesai', SuratRequest::where('status', 'selesai')->count())
                ->description('Bulan ini')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('primary'),
        ];
    }
}