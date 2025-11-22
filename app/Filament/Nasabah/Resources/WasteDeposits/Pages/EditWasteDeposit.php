<?php

namespace App\Filament\Nasabah\Resources\WasteDeposits\Pages;

use App\Filament\Nasabah\Resources\WasteDeposits\WasteDepositResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWasteDeposit extends EditRecord
{
    protected static string $resource = WasteDepositResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
