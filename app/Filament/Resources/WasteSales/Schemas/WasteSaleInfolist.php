<?php

namespace App\Filament\Resources\WasteSales\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class WasteSaleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('total_weight')
                    ->label('Total Berat')
                    ->numeric(),
                TextEntry::make('total_income')
                    ->label('Total Pemasukan')
                    ->money('IDR', decimalPlaces: 0, locale: 'id_ID')
                    ->badge()
                    ->size('xl'),
                TextEntry::make('created_at')
                    ->label('Dibuat pada')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('Diperbarui pada')
                    ->dateTime(),
            ]);
    }
}
