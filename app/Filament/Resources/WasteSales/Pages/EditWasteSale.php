<?php

namespace App\Filament\Resources\WasteSales\Pages;

use App\Filament\Resources\WasteSales\WasteSaleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWasteSale extends EditRecord
{
    protected static string $resource = WasteSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()->label('Detail'),
            DeleteAction::make()->label('Hapus'),
        ];
    }

    public function getTitle(): string
    {
        return 'Edit Penjualan Sampah';
    }
}
