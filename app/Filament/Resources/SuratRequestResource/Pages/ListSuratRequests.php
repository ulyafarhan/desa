<?php

namespace App\Filament\Resources\SuratRequestResource\Pages;

use App\Filament\Resources\SuratRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuratRequests extends ListRecords
{
    protected static string $resource = SuratRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
