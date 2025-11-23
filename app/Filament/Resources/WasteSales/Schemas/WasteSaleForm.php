<?php

namespace App\Filament\Resources\WasteSales\Schemas;

use App\Models\WasteType;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class WasteSaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Gunakan Repeater untuk Jenis Sampah yang berisi Nama, Berat, dan Harga
                Repeater::make('waste_items')
                    ->label('Jenis Sampah')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('waste_type_id')
                                ->label('Jenis Sampah')
                                ->required()
                                ->options(WasteType::pluck('name', 'id'))
                                ->native(false),
                            // Perhitungan berat sampah harus reactive
                            TextInput::make('weight')
                                ->label('Berat Sampah (kg)')
                                ->required()
                                ->numeric(),
                            // Perhitungan total price harus reactive
                            TextInput::make('price')
                                ->label('Harga per Kg')
                                ->required()
                                ->numeric(),
                        ])
                    ])
                    ->required(),
                    Grid::make(3)->schema([
                        TextInput::make('total_weight')
                            ->label('Total Berat')
                            ->suffix('Kg')
                            ->disabled()
                            ->numeric()
                            ->reactive(),
                        TextInput::make('total_income')
                            ->label('Total Pendapatan')
                            ->prefix('Rp')
                            ->disabled()
                            ->numeric()
                            ->reactive(),
                        TextInput::make('buyer')
                            ->label('Pembeli'),
                    ])
            ])->columns(1);
    }
}
