<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingReceipts\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingReceipts\SubcontractingReceiptResource;

class ListSubcontractingReceipts extends ListRecords
{
    protected static string $resource = SubcontractingReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
