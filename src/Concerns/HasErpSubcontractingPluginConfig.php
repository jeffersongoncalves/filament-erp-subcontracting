<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Concerns;

use JeffersonGoncalves\FilamentErp\Core\Concerns\HasErpPluginConfig;

trait HasErpSubcontractingPluginConfig
{
    use HasErpPluginConfig;

    protected function getConfigKey(): string
    {
        return 'filament-erp-subcontracting';
    }

    protected function getDefaultNavigationGroup(): string
    {
        return 'ERP — Subcontracting';
    }
}
