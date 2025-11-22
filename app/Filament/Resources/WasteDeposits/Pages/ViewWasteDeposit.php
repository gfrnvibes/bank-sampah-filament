<?php

namespace App\Filament\Resources\WasteDeposits\Pages;

use App\Filament\Resources\WasteDeposits\WasteDepositResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWasteDeposit extends ViewRecord
{
    protected static string $resource = WasteDepositResource::class;

    public function getHeading(): string
    {
        return 'Detail Setoran Sampah';
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
