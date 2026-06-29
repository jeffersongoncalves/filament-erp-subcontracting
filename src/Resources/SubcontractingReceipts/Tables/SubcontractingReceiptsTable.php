<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingReceipts\Tables;

use Filament\Actions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use JeffersonGoncalves\Erp\Core\Enums\DocStatus;
use JeffersonGoncalves\FilamentErp\Core\Concerns\SubmittableRecordActions;

class SubcontractingReceiptsTable
{
    use SubmittableRecordActions;

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('supplier_name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subcontractingOrder.supplier_name')
                    ->label('Order')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('posting_date')
                    ->label('Posting Date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('total_qty')
                    ->label('Total Qty')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('docstatus')
                    ->label('Doc Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof DocStatus ? $state->name : $state)
                    ->color(fn ($state): string => match ($state) {
                        DocStatus::Draft => 'gray',
                        DocStatus::Submitted => 'success',
                        DocStatus::Cancelled => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('company.name')
                    ->label('Company')
                    ->toggleable()
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('docstatus')
                    ->label('Doc Status')
                    ->options([
                        0 => 'Draft',
                        1 => 'Submitted',
                        2 => 'Cancelled',
                    ]),
            ])
            ->recordActions([
                Actions\EditAction::make()
                    ->visible(fn ($record): bool => $record->docstatus === DocStatus::Draft),
                ...self::submittableRecordActions(),
                Actions\DeleteAction::make()
                    ->visible(fn ($record): bool => $record->docstatus === DocStatus::Draft),
            ]);
    }
}
