<?php

namespace App\Filament\Resources\SuratTemplateResource\Pages;

use App\Filament\Resources\SuratTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuratTemplate extends EditRecord
{
    protected static string $resource = SuratTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
