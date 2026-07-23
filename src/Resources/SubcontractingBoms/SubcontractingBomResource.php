<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingBoms;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use JeffersonGoncalves\Erp\Subcontracting\Support\ModelResolver;
use JeffersonGoncalves\FilamentErp\Subcontracting\FilamentErpSubcontractingPlugin;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingBoms\Pages\CreateSubcontractingBom;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingBoms\Pages\EditSubcontractingBom;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingBoms\Pages\ListSubcontractingBoms;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingBoms\RelationManagers\ItemsRelationManager;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingBoms\Schemas\SubcontractingBomForm;
use JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingBoms\Tables\SubcontractingBomsTable;

class SubcontractingBomResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'finished_good';

    public static function getModel(): string
    {
        return ModelResolver::subcontractingBom();
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
        return SubcontractingBomForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return SubcontractingBomsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubcontractingBoms::route('/'),
            'create' => CreateSubcontractingBom::route('/create'),
            'edit' => EditSubcontractingBom::route('/{record}/edit'),
        ];
    }
}
