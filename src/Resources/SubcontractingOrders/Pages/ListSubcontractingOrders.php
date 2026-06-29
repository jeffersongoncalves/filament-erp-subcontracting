<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\SubcontractingOrderResource;

class ListSubcontractingOrders extends ListRecords
{
    protected static string $resource = SubcontractingOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
