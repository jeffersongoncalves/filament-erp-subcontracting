<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use JeffersonGoncalves\Erp\Subcontracting\Support\ModelResolver;
use JeffersonGoncalves\FilamentErp\Subcontracting\FilamentErpSubcontractingPlugin;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\Pages\CreateSubcontractingOrder;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\Pages\EditSubcontractingOrder;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\Pages\ListSubcontractingOrders;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\RelationManagers\ItemsRelationManager;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\RelationManagers\SuppliedItemsRelationManager;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\Schemas\SubcontractingOrderForm;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\Tables\SubcontractingOrdersTable;

class SubcontractingOrderResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'supplier_name';

    public static function getModel(): string
    {
        return ModelResolver::subcontractingOrder();
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
        return SubcontractingOrderForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return SubcontractingOrdersTable::configure($table);
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
            'index' => ListSubcontractingOrders::route('/'),
            'create' => CreateSubcontractingOrder::route('/create'),
            'edit' => EditSubcontractingOrder::route('/{record}/edit'),
        ];
    }
}
