<?php

namespace App\Filament\Nasabah\Resources\BalanceWithdrawals\Pages;

use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\Auth;
use Filament\Schemas\Components\Text;
use Filament\Notifications\Notification;
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
                ->modalHeading('Tarik Saldo')
                ->modalWidth('md')
                ->createAnother(false)
                ->icon('heroicon-o-plus-circle')
                ->modalSubmitActionLabel('Tarik Saldo')

        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // notifikasi database
        $recipient = auth()->user();

        Notification::make()
            ->title('Saved successfully')
            ->sendToDatabase($recipient);
    }
}
