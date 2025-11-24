<?php

namespace App\Filament\Resources\WasteDeposits\Schemas;

use App\Models\User;
use App\Models\WasteType;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class WasteDepositForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    Select::make('user_id')
                        ->options(User::query()->where('id', '!=', 1)->pluck('name', 'id'))
                        ->label('Pilih Nasabah')
                        ->required()
                        ->searchable()
                        ->native(false),
                    TextInput::make('total_weight')
                        ->label('Total Berat')
                        ->numeric()
                        ->suffix(' Kg')
                        ->readonly()
                        ->reactive(),

                    TextInput::make('total_amount')
                        ->label('Total Pendapatan')
                        ->numeric()
                        ->prefix('Rp')
                        ->readonly()
                        ->reactive(),
                ]),
                Repeater::make('waste_items')
                    ->label('Jenis Sampah')
                    ->createItemButtonLabel('Tambah Jenis Sampah')
                    ->columns(4)
                    ->schema([
                        Select::make('waste_type_id')
                            ->label('Jenis Sampah')
                            ->required()
                            ->searchable()
                            ->options(fn() => WasteType::query()->pluck('name', 'id'))
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $wt = WasteType::find($state);
                                if ($wt) {
                                    $set('price_per_kg', (float) $wt->price_per_kg);
                                    $set('amount', ($get('weight') ?? 0) * (float) $wt->price_per_kg);
                                } else {
                                    $set('price_per_kg', 0);
                                    $set('amount', 0);
                                }
                            }),

                        TextInput::make('price_per_kg')
                            ->label('Harga per Kg')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled()
                            ->reactive(),

                        TextInput::make('weight')
                            ->label('Berat (kg)')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $price = $get('price_per_kg') ?? 0;
                                $set('amount', (float) $state * (float) $price);
                            }),

                        TextInput::make('amount')
                            ->label('Pendapatan')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->reactive(),
                    ])
                    ->afterStateUpdated(function ($state, callable $set) {
                        $totalWeight = 0;
                        $totalAmount = 0;
                        if (is_array($state)) {
                            foreach ($state as $item) {
                                $w = isset($item['weight']) ? (float) $item['weight'] : 0;
                                $a = isset($item['amount']) ? (float) $item['amount'] : 0;
                                $totalWeight += $w;
                                $totalAmount += $a;
                            }
                        }
                        $set('total_weight', $totalWeight);
                        $set('total_amount', $totalAmount);
                    }),
                Textarea::make('notes')
                    ->label('Catatan'),
            ])->columns(1);
    }
}