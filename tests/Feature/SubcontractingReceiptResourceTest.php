<?php

use Filament\Actions\Testing\TestAction;
use JeffersonGoncalves\Erp\Accounting\Models\Account;
use JeffersonGoncalves\Erp\Accounting\Models\GlEntry;
use JeffersonGoncalves\Erp\Core\Enums\DocStatus;
use JeffersonGoncalves\Erp\Core\Models\Company;
use JeffersonGoncalves\Erp\Stock\Enums\StockEntryType;
use JeffersonGoncalves\Erp\Stock\Models\Item;
use JeffersonGoncalves\Erp\Stock\Models\StockEntry;
use JeffersonGoncalves\Erp\Stock\Models\StockLedgerEntry;
use JeffersonGoncalves\Erp\Stock\Models\Warehouse;
use JeffersonGoncalves\Erp\Subcontracting\Models\SubcontractingReceipt;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingReceipts\Pages\CreateSubcontractingReceipt;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingReceipts\Pages\EditSubcontractingReceipt;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingReceipts\Pages\ListSubcontractingReceipts;
use Livewire\Livewire;

beforeEach(function () {
    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->company = Company::factory()->create();
});

/**
 * Seed raw-material stock into the supplier warehouse via a Material Receipt so
 * the subcontracting receipt has valued stock to consume.
 */
function seedRawMaterial(Company $company, Warehouse $warehouse, Item $item, float $qty, float $rate): void
{
    $entry = StockEntry::factory()->create([
        'stock_entry_type' => StockEntryType::MaterialReceipt,
        'company_id' => $company->id,
        'to_warehouse_id' => $warehouse->id,
        'posting_date' => now(),
    ]);

    $entry->items()->create([
        'item_id' => $item->id,
        't_warehouse_id' => $warehouse->id,
        'qty' => $qty,
        'basic_rate' => $rate,
    ]);

    $entry->submit();
}

it('can render the subcontracting receipt list page', function () {
    Livewire::test(ListSubcontractingReceipts::class)->assertSuccessful();
});

it('can render the subcontracting receipt create page', function () {
    Livewire::test(CreateSubcontractingReceipt::class)->assertSuccessful();
});

it('can render the subcontracting receipt edit page', function () {
    $receipt = SubcontractingReceipt::factory()->create(['company_id' => $this->company->id]);

    Livewire::test(EditSubcontractingReceipt::class, ['record' => $receipt->getRouteKey()])
        ->assertSuccessful();
});

it('submits a subcontracting receipt through the UI, posting stock and balanced GL entries', function () {
    $fgAccount = Account::factory()->create(['company_id' => $this->company->id]);
    $supplierAccount = Account::factory()->create(['company_id' => $this->company->id]);
    $counterAccount = Account::factory()->create(['company_id' => $this->company->id]);

    config()->set('erp-subcontracting.default_subcontracting_counter_account', $counterAccount->id);

    $fgWarehouse = Warehouse::factory()->create(['company_id' => $this->company->id, 'account_id' => $fgAccount->id]);
    $supplierWarehouse = Warehouse::factory()->create(['company_id' => $this->company->id, 'account_id' => $supplierAccount->id]);

    $rmItem = Item::factory()->create();
    $fgItem = Item::factory()->create();

    // 10 units of raw material at rate 5 are sent to the supplier warehouse.
    seedRawMaterial($this->company, $supplierWarehouse, $rmItem, 10, 5);

    $receipt = SubcontractingReceipt::factory()->create([
        'company_id' => $this->company->id,
        'supplier_warehouse_id' => $supplierWarehouse->id,
        'posting_date' => now(),
    ]);

    $receipt->items()->create([
        'item_code' => $fgItem->item_code,
        'qty' => 5,
        'rate' => 20,
        'warehouse_id' => $fgWarehouse->id,
    ]);

    $receipt->suppliedItems()->create([
        'main_item_code' => $fgItem->item_code,
        'rm_item_code' => $rmItem->item_code,
        'consumed_qty' => 5,
    ]);

    Livewire::test(ListSubcontractingReceipts::class)
        ->callAction(TestAction::make('submit')->table($receipt));

    expect($receipt->refresh()->docstatus)->toBe(DocStatus::Submitted);

    $morph = $receipt->getMorphClass();

    $sles = StockLedgerEntry::query()
        ->where('voucherable_type', $morph)
        ->where('voucherable_id', $receipt->id)
        ->where('is_cancelled', false)
        ->get();

    // One finished-good inbound + one raw-material outbound.
    expect($sles)->toHaveCount(2);

    $fgSle = $sles->firstWhere('item_id', $fgItem->id);
    $rmSle = $sles->firstWhere('item_id', $rmItem->id);

    expect($fgSle->warehouse_id)->toBe($fgWarehouse->id)
        ->and($fgSle->actual_qty)->toBe(5.0)
        ->and($rmSle->warehouse_id)->toBe($supplierWarehouse->id)
        ->and($rmSle->actual_qty)->toBe(-5.0);

    // GL: net stock value increase (the service portion) is balanced.
    $glEntries = GlEntry::query()
        ->where('voucherable_type', $morph)
        ->where('voucherable_id', $receipt->id)
        ->where('is_cancelled', false)
        ->get();

    expect($glEntries)->toHaveCount(2)
        ->and(round((float) $glEntries->sum('debit'), 2))->toBe(round((float) $glEntries->sum('credit'), 2));

    $fgGl = $glEntries->firstWhere('account_id', $fgAccount->id);
    expect($fgGl)->not->toBeNull();
});
