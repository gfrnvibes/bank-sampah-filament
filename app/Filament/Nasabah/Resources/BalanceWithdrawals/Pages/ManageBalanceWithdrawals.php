<?php

namespace App\Filament\Nasabah\Resources\BalanceWithdrawals\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Nasabah\Resources\BalanceWithdrawals\BalanceWithdrawalResource;

class ManageBalanceWithdrawals extends ManageRecords
{
    protected static string $resource = BalanceWithdrawalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Tarik Saldo')
            ->modalWidth('md')
            ->createAnother(false)
            ->icon('heroicon-o-plus-circle'),
        ];
    }
}
