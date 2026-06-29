<?php

use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingBoms\SubcontractingBomResource;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\SubcontractingOrderResource;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingReceipts\SubcontractingReceiptResource;
use JeffersonGoncalves\FilamentErp\Subcontracting\Widgets\SubcontractingOrderStatsWidget;

return [

    /*
    |--------------------------------------------------------------------------
    | Navigation Group
    |--------------------------------------------------------------------------
    |
    | The navigation group under which all ERP subcontracting resources are
    | listed in the Filament panel. Override per-plugin with ->navigationGroup('...').
    |
    */

    'navigation_group' => 'ERP — Subcontracting',

    /*
    |--------------------------------------------------------------------------
    | Resources
    |--------------------------------------------------------------------------
    |
    | The Filament resource classes registered by the plugin. Each entry can be
    | swapped for a custom resource extending the default one.
    |
    */

    'resources' => [
        'subcontracting_bom' => SubcontractingBomResource::class,
        'subcontracting_order' => SubcontractingOrderResource::class,
        'subcontracting_receipt' => SubcontractingReceiptResource::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Widgets
    |--------------------------------------------------------------------------
    |
    | The Filament widgets registered by the plugin on the panel dashboard.
    |
    */

    'widgets' => [
        'subcontracting_order_stats' => SubcontractingOrderStatsWidget::class,
    ],

];
