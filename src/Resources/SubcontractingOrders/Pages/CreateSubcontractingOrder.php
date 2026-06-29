<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\Pages;

use Filament\Resources\Pages\CreateRecord;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\SubcontractingOrderResource;

class CreateSubcontractingOrder extends CreateRecord
{
    protected static string $resource = SubcontractingOrderResource::class;
}
