<?php

use Filament\Actions\Testing\TestAction;
use JeffersonGoncalves\Erp\Core\Enums\DocStatus;
use JeffersonGoncalves\Erp\Core\Models\Company;
use JeffersonGoncalves\Erp\Stock\Models\Warehouse;
use JeffersonGoncalves\Erp\Subcontracting\Models\SubcontractingOrder;
use JeffersonGoncalves\Erp\Subcontracting\Models\SubcontractingReceipt;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\Pages\CreateSubcontractingOrder;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\Pages\EditSubcontractingOrder;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\Pages\ListSubcontractingOrders;
use Livewire\Livewire;

beforeEach(function () {
    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->company = Company::factory()->create();
    $this->supplierWarehouse = Warehouse::factory()->create(['company_id' => $this->company->id]);
    $this->fgWarehouse = Warehouse::factory()->create(['company_id' => $this->company->id]);
});

it('can render the subcontracting order list page', function () {
    Livewire::test(ListSubcontractingOrders::class)->assertSuccessful();
});

it('can render the subcontracting order create page', function () {
    Livewire::test(CreateSubcontractingOrder::class)->assertSuccessful();
});

it('can render the subcontracting order edit page', function () {
    $order = SubcontractingOrder::factory()->create(['company_id' => $this->company->id]);

    Livewire::test(EditSubcontractingOrder::class, ['record' => $order->getRouteKey()])
        ->assertSuccessful();
});

it('can create a subcontracting order through the form', function () {
    Livewire::test(CreateSubcontractingOrder::class)
        ->fillForm([
            'supplier_name' => 'Acme Subcontractors',
            'transaction_date' => now()->toDateString(),
            'company_id' => $this->company->id,
            'supplier_warehouse_id' => $this->supplierWarehouse->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(SubcontractingOrder::query()->where('supplier_name', 'Acme Subcontractors')->exists())->toBeTrue();
});

it('submits a subcontracting order through the UI', function () {
    $order = SubcontractingOrder::factory()->create([
        'company_id' => $this->company->id,
        'supplier_warehouse_id' => $this->supplierWarehouse->id,
    ]);

    $order->items()->create([
        'item_code' => 'FG-WIDGET',
        'qty' => 8,
        'rate' => 12,
        'fg_warehouse_id' => $this->fgWarehouse->id,
    ]);

    Livewire::test(ListSubcontractingOrders::class)
        ->callAction(TestAction::make('submit')->table($order));

    expect($order->refresh()->docstatus)->toBe(DocStatus::Submitted);
});

it('creates a subcontracting receipt from a submitted order through the UI action', function () {
    $order = SubcontractingOrder::factory()->create([
        'company_id' => $this->company->id,
        'supplier_warehouse_id' => $this->supplierWarehouse->id,
    ]);

    $order->items()->create([
        'item_code' => 'FG-WIDGET',
        'qty' => 8,
        'rate' => 12,
        'fg_warehouse_id' => $this->fgWarehouse->id,
    ]);

    $order->suppliedItems()->create([
        'main_item_code' => 'FG-WIDGET',
        'rm_item_code' => 'RM-STEEL',
        'required_qty' => 16,
    ]);

    Livewire::test(ListSubcontractingOrders::class)
        ->callAction(TestAction::make('submit')->table($order));

    expect($order->refresh()->docstatus)->toBe(DocStatus::Submitted);

    Livewire::test(ListSubcontractingOrders::class)
        ->callAction(TestAction::make('createReceipt')->table($order));

    $receipt = SubcontractingReceipt::query()
        ->where('subcontracting_order_id', $order->id)
        ->latest('id')
        ->first();

    expect($receipt)->not->toBeNull()
        ->and($receipt->supplier_warehouse_id)->toBe($this->supplierWarehouse->id)
        ->and($receipt->items)->toHaveCount(1)
        ->and($receipt->suppliedItems)->toHaveCount(1);

    $item = $receipt->items->first();

    expect($item->item_code)->toBe('FG-WIDGET')
        ->and($item->qty)->toBe(8.0)
        ->and($item->warehouse_id)->toBe($this->fgWarehouse->id);
});
