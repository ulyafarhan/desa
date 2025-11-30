<?php

namespace App\Filament\Resources\SuratTemplateResource\Pages;

use App\Filament\Resources\SuratTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuratTemplates extends ListRecords
{
    protected static string $resource = SuratTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
