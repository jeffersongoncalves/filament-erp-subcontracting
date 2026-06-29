<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingReceipts;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use JeffersonGoncalves\Erp\Subcontracting\Support\ModelResolver;
use JeffersonGoncalves\FilamentErp\Subcontracting\FilamentErpSubcontractingPlugin;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingReceipts\Pages\CreateSubcontractingReceipt;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingReceipts\Pages\EditSubcontractingReceipt;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingReceipts\Pages\ListSubcontractingReceipts;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingReceipts\RelationManagers\ItemsRelationManager;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingReceipts\RelationManagers\SuppliedItemsRelationManager;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingReceipts\Schemas\SubcontractingReceiptForm;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingReceipts\Tables\SubcontractingReceiptsTable;

class SubcontractingReceiptResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'supplier_name';

    public static function getModel(): string
    {
        return ModelResolver::subcontractingReceipt();
    }

    public static function getNavigationGroup(): ?string
    {
        try {
            return FilamentErpSubcontractingPlugin::get()->getNavigationGroup();
        } catch (\Throwable) {
            return config('filament-erp-subcontracting.navigation_group', 'ERP — Subcontracting');
        }
    }

    public static function form(Form $form): Form
    {
        return SubcontractingReceiptForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return SubcontractingReceiptsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
            SuppliedItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubcontractingReceipts::route('/'),
            'create' => CreateSubcontractingReceipt::route('/create'),
            'edit' => EditSubcontractingReceipt::route('/{record}/edit'),
        ];
    }
}
