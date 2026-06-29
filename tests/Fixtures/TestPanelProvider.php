<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;
use JeffersonGoncalves\FilamentErp\Subcontracting\FilamentErpSubcontractingPlugin;

class TestPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->plugins([
                FilamentErpSubcontractingPlugin::make(),
            ]);
    }
}
