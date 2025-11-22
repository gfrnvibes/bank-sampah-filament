<?php

namespace App\Filament\Nasabah\Resources\WasteDeposits\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class WasteDepositInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextEntry::make('user_id')
                //     ->numeric(),
                TextEntry::make('total_weight')
                    ->label('Total Berat')
                    ->suffix(' kg')
                    ->numeric(),
                TextEntry::make('total_amount')
                    ->label('Jumlah Pendapatan')
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

    public static function canEdit(): bool
    {
        return false;
    }

}
