<?php

namespace App\Filament\Resources\WasteSales\Pages;

use App\Filament\Resources\WasteSales\WasteSaleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWasteSale extends CreateRecord
{
    protected static string $resource = WasteSaleResource::class;

    public function getTitle(): string
    {
        return 'Tambah Penjualan Sampah';
    }
}
