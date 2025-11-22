<?php

namespace App\Filament\Resources\WasteDeposits\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class WasteDepositInfolist
{

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('Nasabah'),
                TextEntry::make('waste_items')
                    ->label('Jenis Sampah')
                    ->formatStateUsing(fn ($state) => 'besi'),
                TextEntry::make('total_weight')
                    ->label('Total Berat')
                    ->suffix(' Kg')
                    ->color('danger')
                    ->numeric(),
                TextEntry::make('total_amount')
                    ->label('Total Pendapatan')
                    ->badge()
                    ->size('xl')
                    ->color('success')
                    ->money('IDR', decimalPlaces: 0, locale: 'id_ID'),
                TextEntry::make('created_at')
                    ->label('Dibuat pada')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('Diperbarui pada')
                    ->dateTime(),
            ]);
    }
}
