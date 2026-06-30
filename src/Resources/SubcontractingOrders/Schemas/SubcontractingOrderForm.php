<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingOrders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class SubcontractingOrderForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->columns(null)
            ->schema([
                Section::make('Details')
                    ->schema([
                        TextInput::make('supplier_name')
                            ->label('Supplier Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('party_id')
                            ->label('Supplier ID')
                            ->numeric()
                            ->nullable(),
                        DatePicker::make('transaction_date')
                            ->label('Transaction Date')
                            ->required()
                            ->default(now()),
                        Select::make('company_id')
                            ->label('Company')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Select::make('supplier_warehouse_id')
                            ->label('Supplier Warehouse')
                            ->relationship('supplierWarehouse', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ])->columns(2),
                Section::make('Totals')
                    ->schema([
                        TextInput::make('net_total')
                            ->label('Net Total')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('grand_total')
                            ->label('Grand Total')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2),
            ]);
    }
}
