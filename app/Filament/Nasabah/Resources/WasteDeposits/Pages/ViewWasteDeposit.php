<?php

namespace App\Filament\Nasabah\Resources\WasteDeposits\Pages;

use App\Filament\Nasabah\Resources\WasteDeposits\WasteDepositResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewWasteDeposit extends ViewRecord
{
    protected static string $resource = WasteDepositResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
        ];
    }

    public function getHeading(): string
    {
        return 'Detail Setoran Sampah';
    }

}