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
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('total_weight')
                    ->numeric(),
                TextEntry::make('total_amount')
                    ->badge()
                    ->size('xl')
                    ->color('success')
                    ->money('IDR', decimalPlaces: 0, locale: 'id_ID'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
