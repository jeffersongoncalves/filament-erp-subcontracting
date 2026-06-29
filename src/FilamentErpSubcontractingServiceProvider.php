<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentErpSubcontractingServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-erp-subcontracting';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasTranslations();
    }
}
