<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\Tables;

use Filament\Actions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use JeffersonGoncalves\Erp\Core\Enums\DocStatus;
use JeffersonGoncalves\Erp\Subcontracting\Enums\SubcontractingOrderStatus;
use JeffersonGoncalves\FilamentErp\Core\Concerns\SubmittableRecordActions;
use JeffersonGoncalves\FilamentErp\Subcontracting\Concerns\CreatesSubcontractingReceipts;

class SubcontractingOrdersTable
{
    use CreatesSubcontractingReceipts;
    use SubmittableRecordActions;

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('supplier_name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('transaction_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state instanceof SubcontractingOrderStatus ? $state->value : (string) $state)
                    ->color(fn ($state): string => match ($state) {
                        SubcontractingOrderStatus::Draft => 'gray',
                        SubcontractingOrderStatus::Open => 'warning',
                        SubcontractingOrderStatus::PartiallyReceived => 'info',
                        SubcontractingOrderStatus::Completed => 'success',
                        SubcontractingOrderStatus::Cancelled => 'danger',
                        SubcontractingOrderStatus::Closed => 'danger',
                        default => 'gray',
                    }),
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
                TextColumn::make('grand_total')
                    ->label('Grand Total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('company.name')
                    ->label('Company')
                    ->toggleable()
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(self::statusOptions()),
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
                self::createReceiptAction(),
                Actions\DeleteAction::make()
                    ->visible(fn ($record): bool => $record->docstatus === DocStatus::Draft),
            ]);
    }

    /** @return array<string, string> */
    protected static function statusOptions(): array
    {
        $options = [];

        foreach (SubcontractingOrderStatus::cases() as $case) {
            $options[$case->value] = $case->value;
        }

        return $options;
    }
}
