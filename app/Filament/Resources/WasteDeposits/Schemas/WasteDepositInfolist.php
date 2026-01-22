<?php

namespace App\Filament\Resources\WasteDeposits\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class WasteDepositInfolist
{

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                TextEntry::make('user.name')
                    ->label('Nasabah'),

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

                TextEntry::make('items.wasteType.name')
                    ->label('Jenis Sampah')
                    ->bulleted()
                    ->columnSpan(1),

                TextEntry::make('items.weight_kg')
                    ->label('Berat')
                    ->suffix(' Kg')
                    ->bulleted()
                    ->columnSpan(1),

                TextEntry::make('items.subtotal')
                    ->label('Subtotal')
                    ->money('IDR', decimalPlaces: 0, locale: 'id_ID')
                    ->bulleted()
                    ->columnSpan(1),
                
                TextEntry::make('notes')
                    ->label('Catatan')
                    ->placeholder('Tidak ada catatan')
                   ,

                TextEntry::make('created_at')
                    ->label('Dibuat pada'),

                TextEntry::make('updated_at')
                    ->label('Diperbarui pada'),

                ImageEntry::make('receipt')
                    ->label('Bukti Transaksi'),
            ]);
    }

}
