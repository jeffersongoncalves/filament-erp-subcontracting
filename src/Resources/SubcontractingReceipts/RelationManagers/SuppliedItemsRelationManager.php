<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingReceipts\RelationManagers;

use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SuppliedItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'suppliedItems';

    protected static ?string $title = 'Supplied Items';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('main_item_code')
                    ->label('Main Item')
                    ->required()
                    ->maxLength(255),
                TextInput::make('rm_item_code')
                    ->label('Raw Material')
                    ->required()
                    ->maxLength(255),
                TextInput::make('consumed_qty')
                    ->label('Consumed Qty')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('rm_item_code')
            ->columns([
                TextColumn::make('main_item_code')
                    ->label('Main Item')
                    ->searchable(),
                TextColumn::make('rm_item_code')
                    ->label('Raw Material')
                    ->searchable(),
                TextColumn::make('consumed_qty')
                    ->label('Consumed')
                    ->numeric(),
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
