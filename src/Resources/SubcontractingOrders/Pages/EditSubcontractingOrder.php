<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\SubcontractingOrderResource;

class EditSubcontractingOrder extends EditRecord
{
    protected static string $resource = SubcontractingOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
