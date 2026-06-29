<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SuppliedItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'suppliedItems';

    protected static ?string $title = 'Supplied Items';

    public function form(Form $form): Form
    {
        return $form
            ->columns(2)
            ->schema([
                TextInput::make('main_item_code')
                    ->label('Main Item')
                    ->required()
                    ->maxLength(255),
                TextInput::make('rm_item_code')
                    ->label('Raw Material')
                    ->required()
                    ->maxLength(255),
                TextInput::make('required_qty')
                    ->label('Required Qty')
                    ->numeric()
                    ->default(0),
                TextInput::make('supplied_qty')
                    ->label('Supplied Qty')
                    ->numeric()
                    ->default(0),
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
                TextColumn::make('required_qty')
                    ->label('Required')
                    ->numeric(),
                TextColumn::make('supplied_qty')
                    ->label('Supplied')
                    ->numeric(),
                TextColumn::make('consumed_qty')
                    ->label('Consumed')
                    ->numeric(),
            ])
            ->headerActions([
                Actions\CreateAction::make(),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
