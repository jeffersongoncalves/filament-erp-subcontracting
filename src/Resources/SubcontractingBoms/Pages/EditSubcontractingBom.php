<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingBoms\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingBoms\SubcontractingBomResource;

class EditSubcontractingBom extends EditRecord
{
    protected static string $resource = SubcontractingBomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
