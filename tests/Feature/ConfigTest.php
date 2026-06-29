<?php

it('loads the filament-erp-subcontracting config file', function () {
    expect(config('filament-erp-subcontracting'))->toBeArray();
});

it('has a default navigation group', function () {
    expect(config('filament-erp-subcontracting.navigation_group'))->toBe('ERP — Subcontracting');
});

it('registers all resources in config', function () {
    $resources = config('filament-erp-subcontracting.resources');

    expect($resources)->toBeArray()
        ->toHaveKeys([
            'subcontracting_bom',
            'subcontracting_order',
            'subcontracting_receipt',
        ]);
});

it('registers the dashboard widgets in config', function () {
    expect(config('filament-erp-subcontracting.widgets'))->toBeArray()
        ->toHaveKeys(['subcontracting_order_stats']);
});
