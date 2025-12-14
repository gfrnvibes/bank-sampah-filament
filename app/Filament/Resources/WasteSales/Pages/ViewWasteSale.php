<?php

namespace App\Filament\Resources\WasteSales\Pages;

use App\Filament\Resources\WasteSales\WasteSaleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWasteSale extends ViewRecord
{
    protected static string $resource = WasteSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Detail Penjualan Sampah';
    }
}
