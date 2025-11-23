<?php

namespace App\Filament\Resources\WasteSales\Pages;

use App\Filament\Resources\WasteSales\WasteSaleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWasteSales extends ListRecords
{
    protected static string $resource = WasteSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Penjualan Sampah')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
