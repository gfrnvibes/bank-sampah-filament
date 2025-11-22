<?php

namespace App\Filament\Resources\BalanceWithdrawals\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\BalanceWithdrawals\BalanceWithdrawalResource;

class ManageBalanceWithdrawals extends ManageRecords
{
    protected static string $resource = BalanceWithdrawalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Buat Penarikan Saldo'),
        ];
    }

    public function beforeCreate(): void
    {
        $data = $this->form->getState();
        $user = \App\Models\User::find($data['user_id']);

        if ($data['status'] === 'accepted') {
            if ($user->balance < $data['amount']) {

                Notification::make()
                    ->danger()
                    ->title('Saldo tidak cukup')
                    ->body("Saldo nasabah hanya Rp {$user->balance}, tidak bisa menarik Rp {$data['amount']}.")
                    ->send();

                $this->halt();
            }
        }
    }

    public function afterCreate(): void
    {
        $record = $this->record;

        if ($record->status === 'accepted') {
            $user = $record->user;
            $user->balance -= $record->amount;
            $user->save();
        }
    }
}
