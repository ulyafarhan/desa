<?php

namespace App\Filament\Resources\PejabatDesaResource\Pages;

use App\Filament\Resources\PejabatDesaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPejabatDesa extends EditRecord
{
    protected static string $resource = PejabatDesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
