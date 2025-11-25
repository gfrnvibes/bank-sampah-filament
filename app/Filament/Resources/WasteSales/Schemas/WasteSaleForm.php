<?php

namespace App\Filament\Resources\WasteSales\Schemas;

use Closure;
use App\Models\WasteType;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater\TableColumn;

class WasteSaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Repeater::make('waste_items')
                    ->label('Jenis Sampah')
                    ->addActionLabel('Tambah Baris')
                    ->table([
                        TableColumn::make('Jenis Sampah'),
                        TableColumn::make('Berat'),
                        TableColumn::make('Harga / Kg'),
                    ])
                    ->reorderable(false)
                    ->schema([
                        Select::make('waste_type_id')
                            ->label('Jenis Sampah')
                            ->required()
                            ->options(WasteType::pluck('name', 'id'))
                            ->native(false),
                        TextInput::make('weight')
                            ->label('Berat Sampah (kg)')
                            ->suffix('Kg')
                            ->required()
                            ->numeric()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set, $get) {
                                self::calculateTotals($set, $get);
                            }),
                        TextInput::make('price')
                            ->label('Harga per Kg')
                            ->prefix('Rp')
                            ->required()
                            ->numeric()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set, $get) {
                                self::calculateTotals($set, $get);
                            }),
                    ])
                    ->required()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        self::calculateTotals($set, $get);
                    }),
                Grid::make(3)->schema([
                    TextInput::make('total_weight')
                        ->label('Total Berat')
                        ->suffix('Kg')
                        ->readOnly()
                        ->numeric()
                        ->reactive(),
                    TextInput::make('total_income')
                        ->label('Total Pendapatan')
                        ->prefix('Rp')
                        ->readOnly()
                        ->numeric()
                        ->reactive(),
                    TextInput::make('buyer')
                        ->label('Pembeli'),
                ])
            ])->columns(1);
    }

    protected static function calculateTotals($set, $get)
    {
        $items = $get('waste_items');
        $totalWeight = 0;
        $totalIncome = 0;

        if (is_array($items)) {
            foreach ($items as $item) {
                if (isset($item['weight'])) {
                    $totalWeight += (float) $item['weight'];
                }
                if (isset($item['weight']) && isset($item['price'])) {
                    $totalIncome += (float) $item['weight'] * (float) $item['price'];
                }
            }
        }

        $set('total_weight', $totalWeight);
        $set('total_income', $totalIncome);
    }
}