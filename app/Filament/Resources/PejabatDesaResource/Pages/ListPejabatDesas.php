<?php

namespace App\Filament\Resources\PejabatDesaResource\Pages;

use App\Filament\Resources\PejabatDesaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPejabatDesas extends ListRecords
{
    protected static string $resource = PejabatDesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
