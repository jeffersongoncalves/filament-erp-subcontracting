<?php

use JeffersonGoncalves\Erp\Subcontracting\Models\SubcontractingBom;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingBoms\Pages\CreateSubcontractingBom;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingBoms\Pages\EditSubcontractingBom;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingBoms\Pages\ListSubcontractingBoms;
use Livewire\Livewire;

beforeEach(function () {
    filament()->setCurrentPanel(filament()->getPanel('admin'));
});

it('can render the subcontracting bom list page', function () {
    Livewire::test(ListSubcontractingBoms::class)->assertSuccessful();
});

it('can render the subcontracting bom create page', function () {
    Livewire::test(CreateSubcontractingBom::class)->assertSuccessful();
});

it('can render the subcontracting bom edit page', function () {
    $bom = SubcontractingBom::factory()->create();

    Livewire::test(EditSubcontractingBom::class, ['record' => $bom->getRouteKey()])
        ->assertSuccessful();
});

it('can create a subcontracting bom through the form', function () {
    Livewire::test(CreateSubcontractingBom::class)
        ->fillForm([
            'finished_good' => 'FG-CHAIR',
            'finished_good_qty' => 2,
            'service_item' => 'ASSEMBLY-SERVICE',
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(SubcontractingBom::query()->where('finished_good', 'FG-CHAIR')->exists())->toBeTrue();
});
