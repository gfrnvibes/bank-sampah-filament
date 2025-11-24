<?php

namespace App\Filament\Nasabah\Resources\TransactionHistories\Pages;

use App\Filament\Nasabah\Resources\TransactionHistories\TransactionHistoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTransactionHistories extends ManageRecords
{
    protected static string $resource = TransactionHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
