<?php

namespace App\Filament\Resources\BalanceWithdrawals\Pages;

use App\Filament\Resources\BalanceWithdrawals\BalanceWithdrawalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageBalanceWithdrawals extends ManageRecords
{
    protected static string $resource = BalanceWithdrawalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
