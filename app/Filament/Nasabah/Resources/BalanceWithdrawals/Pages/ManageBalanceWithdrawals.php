<?php

namespace App\Filament\Nasabah\Resources\BalanceWithdrawals\Pages;

use App\Filament\Nasabah\Resources\BalanceWithdrawals\BalanceWithdrawalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Text;
use Illuminate\Support\Facades\Auth;

class ManageBalanceWithdrawals extends ManageRecords
{
    protected static string $resource = BalanceWithdrawalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tarik Saldo')
                ->modalHeading('Tarik Saldo')
                ->modalWidth('md')
                ->createAnother(false)
                ->icon('heroicon-o-plus-circle')
                ->modalSubmitActionLabel('Tarik Saldo')

        ];
    }
}
