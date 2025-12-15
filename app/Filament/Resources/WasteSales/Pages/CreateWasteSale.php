<?php

namespace App\Filament\Resources\WasteSales\Pages;

use App\Models\WasteSale;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\WasteSales\WasteSaleResource;

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

    public static function getRecordRouteBindingEloquentQuery(): Builder
{
    return parent::getRecordRouteBindingEloquentQuery()
        ->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
}
}
