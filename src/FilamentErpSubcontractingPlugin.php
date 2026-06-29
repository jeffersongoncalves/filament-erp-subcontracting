<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting;

use Filament\Contracts\Plugin;
use Filament\Panel;
use JeffersonGoncalves\FilamentErp\Subcontracting\Concerns\HasErpSubcontractingPluginConfig;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingBoms\SubcontractingBomResource;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\SubcontractingOrderResource;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingReceipts\SubcontractingReceiptResource;

class FilamentErpSubcontractingPlugin implements Plugin
{
    use HasErpSubcontractingPluginConfig;

    public function getId(): string
    {
        return 'filament-erp-subcontracting';
    }

    public function register(Panel $panel): void
    {
        $panel->resources($this->resolveResources([
            'subcontracting_bom' => SubcontractingBomResource::class,
            'subcontracting_order' => SubcontractingOrderResource::class,
            'subcontracting_receipt' => SubcontractingReceiptResource::class,
        ]));

        $panel->widgets($this->resolveWidgets());
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
