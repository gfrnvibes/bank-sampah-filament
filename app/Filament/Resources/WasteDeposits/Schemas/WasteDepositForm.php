<?php

namespace App\Filament\Resources\WasteDeposits\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class WasteDepositForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Pilih Nasabah')
                    ->required()
                    ->searchable()
                    ->native(false),
                TextInput::make('waste_items')
                    ->label('Jenis Sampah')
                    ->required(),
                TextInput::make('total_weight')
                    ->label('Total Berat')
                    ->required()
                    ->numeric(),
                TextInput::make('total_amount')
                    ->label('Total Pendapatan')
                    ->required()
                    ->numeric(),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->columnSpanFull(),
            ]);
    }
}
