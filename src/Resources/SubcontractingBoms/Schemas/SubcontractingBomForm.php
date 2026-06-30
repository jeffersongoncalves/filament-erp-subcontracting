<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Resources\SubcontractingBoms\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;

class SubcontractingBomForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->columns(null)
            ->schema([
                Section::make('Details')
                    ->schema([
                        TextInput::make('finished_good')
                            ->label('Finished Good')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('finished_good_qty')
                            ->label('Finished Good Qty')
                            ->numeric()
                            ->default(1),
                        Select::make('bom_id')
                            ->label('BOM')
                            ->relationship('bom', 'item_code')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        TextInput::make('service_item')
                            ->label('Service Item')
                            ->maxLength(255),
                    ])->columns(2),
                Section::make('Options')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Is Active')
                            ->default(true),
                    ]),
            ]);
    }
}
