<div class="filament-hidden">

![Filament ERP Subcontracting](https://raw.githubusercontent.com/jeffersongoncalves/filament-erp-subcontracting/3.x/art/jeffersongoncalves-filament-erp-subcontracting.png)

</div>

# Filament ERP Subcontracting

Filament v5 panel resources for the [Laravel ERP subcontracting module](https://github.com/jeffersongoncalves/laravel-erp-subcontracting) — subcontracting BOMs, orders and receipts.

This package is the UI layer for the `jeffersongoncalves/laravel-erp-subcontracting` domain package (namespace `JeffersonGoncalves\Erp\Subcontracting\`). It wires the subcontracting models into Filament resources, with Submit/Cancel actions and a Create Receipt action that posts the cross-module stock and GL movements.

## Features

- **Subcontracting BOMs** — Finished good with a raw-materials relation manager
- **Transaction resources** — Subcontracting orders and receipts, each with finished-goods and supplied-items relation managers
- **Document lifecycle** — Submit/Cancel record actions on orders and receipts
- **Create Receipt action** — Drafts the downstream receipt from a submitted order's outstanding quantities
- **Dashboard widget** — `SubcontractingOrderStatsWidget` with open/submitted order counts and value

## Compatibility

| Package | PHP | Filament | Laravel |
|---------|-----|----------|---------|
| `^3.0`  | `^8.2` | `^5.0` | `^11.0 \| ^12.0 \| ^13.0` |

## Installation

Install the package via Composer:

```bash
composer require jeffersongoncalves/filament-erp-subcontracting
```

Register the plugin on a Filament panel:

```php
use JeffersonGoncalves\FilamentErp\Subcontracting\FilamentErpSubcontractingPlugin;

$panel->plugin(
    FilamentErpSubcontractingPlugin::make()
        ->navigationGroup('ERP — Subcontracting'),
);
```

## Resources

| Resource | Purpose |
|----------|---------|
| `SubcontractingBomResource` | Subcontracting BOMs (finished good + Raw Materials RM) |
| `SubcontractingOrderResource` | Subcontracting orders (+ Finished Goods RM, Supplied Items RM, Submit/Cancel, Create Receipt) |
| `SubcontractingReceiptResource` | Subcontracting receipts (+ Finished Goods RM, Supplied Items RM, Submit/Cancel) |

Transaction resources (orders, receipts) expose **Submit** and **Cancel** record actions that drive the domain document lifecycle. A submitted subcontracting order also exposes a **Create Receipt** action that drafts the downstream subcontracting receipt from the outstanding finished-good quantities and supplied raw materials. Submitting a subcontracting receipt posts the cross-module stock ledger movements (finished goods inbound, raw materials outbound) and the balanced GL entries.

## Widgets

| Widget | Purpose |
|--------|---------|
| `SubcontractingOrderStatsWidget` | Count and total value of open/submitted subcontracting orders |

## Configuration

Publish the config to swap resource classes, change the navigation group, or adjust widgets:

```bash
php artisan vendor:publish --tag="filament-erp-subcontracting-config"
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Jefferson Simão Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
