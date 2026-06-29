<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingBoms\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingBoms\SubcontractingBomResource;

class ListSubcontractingBoms extends ListRecords
{
    protected static string $resource = SubcontractingBomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
