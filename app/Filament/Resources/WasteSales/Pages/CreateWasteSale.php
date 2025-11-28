<?php

namespace App\Filament\Resources\WasteSales\Pages;

use App\Filament\Resources\WasteSales\WasteSaleResource;
use App\Models\WasteSale;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Icons\Heroicon;

class CreateWasteSale extends CreateRecord
{
    protected static string $resource = WasteSaleResource::class;

    public function getTitle(): string
    {
        return 'Tambah Penjualan Sampah';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $recipient = auth()->user();

        $sale = $data['total_weight'] ?? 'Penjualan Sampah';

        Notification::make()
            ->title('Penjualan Sampah: ' . $sale . ' Kg')
            ->body('Penjualan Sampah Berhasil disimpan')
            ->icon(Heroicon::ShoppingBag)
            ->sendToDatabase($recipient);

        return $data;
    }
}
