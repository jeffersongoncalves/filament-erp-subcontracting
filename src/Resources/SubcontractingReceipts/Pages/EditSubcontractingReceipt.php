<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingReceipts\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingReceipts\SubcontractingReceiptResource;

class EditSubcontractingReceipt extends EditRecord
{
    protected static string $resource = SubcontractingReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
