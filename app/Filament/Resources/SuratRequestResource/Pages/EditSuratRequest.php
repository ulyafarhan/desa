<?php

namespace App\Filament\Resources\SuratRequestResource\Pages;

use App\Filament\Resources\SuratRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuratRequest extends EditRecord
{
    protected static string $resource = SuratRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
