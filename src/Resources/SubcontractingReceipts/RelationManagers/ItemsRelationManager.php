<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingReceipts\RelationManagers;

use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Finished Goods';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('item_code')
                    ->label('Finished Good')
                    ->required()
                    ->maxLength(255),
                TextInput::make('qty')
                    ->label('Qty')
                    ->numeric()
                    ->default(1),
                TextInput::make('rate')
                    ->label('Rate')
                    ->numeric()
                    ->default(0),
                TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false),
                Select::make('warehouse_id')
                    ->label('Warehouse')
                    ->relationship('warehouse', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Select::make('bom_id')
                    ->label('BOM')
                    ->relationship('bom', 'item_code')
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('item_code')
            ->columns([
                TextColumn::make('item_code')
                    ->label('Finished Good')
                    ->searchable(),
                TextColumn::make('qty')
                    ->numeric(),
                TextColumn::make('rate')
                    ->numeric(),
                TextColumn::make('amount')
                    ->numeric(),
                TextColumn::make('warehouse.name')
                    ->label('Warehouse')
                    ->toggleable(),
                TextColumn::make('bom.item_code')
                    ->label('BOM')
                    ->toggleable(),
            ])
            ->headerActions([
                Actions\CreateAction::make(),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
