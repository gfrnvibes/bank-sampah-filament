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
                TextEntry::make('waste_items')
                    ->label('Jenis Sampah')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) {
                            return '-';
                        }

                        if (!is_array($state)) {
                            $decoded = json_decode($state, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $state = $decoded;
                            } else {
                                return '-';
                            }
                        }

                        $ids = [];
                        foreach ($state as $item) {
                            if (!empty($item['waste_type_id'])) {
                                $ids[] = $item['waste_type_id'];
                            }
                        }

                        $ids = array_values(array_unique($ids));

                        if (!empty($ids)) {
                            $namesById = \App\Models\WasteType::whereIn('id', $ids)
                                ->pluck('name', 'id')
                                ->toArray();

                            $names = [];
                            foreach ($state as $item) {
                                if (!empty($item['waste_type_id']) && isset($namesById[$item['waste_type_id']])) {
                                    $names[] = $namesById[$item['waste_type_id']];
                                } elseif (!empty($item['waste_type'])) {
                                    $names[] = $item['waste_type'];
                                }
                            }

                            $names = array_values(array_unique($names));
                            return $names ? implode(', ', $names) : '-';
                        }

                        // Fallback: maybe names were stored directly in the repeater items
                        $direct = [];
                        foreach ($state as $item) {
                            if (!empty($item['waste_type'])) {
                                $direct[] = $item['waste_type'];
                            }
                        }

                        $direct = array_values(array_unique($direct));
                        return $direct ? implode(', ', $direct) : '-';
                    }),
                TextEntry::make('total_weight')
                    ->label('Total Berat')
                    ->numeric(),
                TextEntry::make('total_income')
                    ->label('Total Pemasukan')
                    ->money('IDR', decimalPlaces: 0, locale: 'id_ID')
                    ->badge()
                    ->size('xl'),
                TextEntry::make('buyer')
                    ->label('Pembeli'),
                TextEntry::make('created_at')
                    ->label('Dibuat pada')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('Diperbarui pada')
                    ->dateTime(),
            ]);
    }
}
