<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingBoms\Tables;

use Filament\Tables\Actions;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SubcontractingBomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('finished_good')
                    ->label('Finished Good')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('finished_good_qty')
                    ->label('Qty')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('bom.item_code')
                    ->label('BOM')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('service_item')
                    ->label('Service Item')
                    ->toggleable()
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->toggleable(),
            ])
            ->defaultSort('finished_good')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Is Active'),
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
