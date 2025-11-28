<?php

namespace App\Filament\Resources\BalanceWithdrawals\Pages;

use Filament\Facades\Filament;
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
            CreateAction::make()
                ->label('Buat Penarikan Saldo')
                ->modalWidth('md')
                ->modalSubmitActionLabel('Tarik Saldo')
                ->modalHeading('Buat Penarikan Saldo')
                ->icon('heroicon-o-plus-circle')
                ->after(function (CreateAction $action) {
                    $record = $action->getRecord();

                    $admin = Filament::auth()->user();   // admin yg lagi login
                    $user = $record->user;             // user yang ditarik saldonya (sesuaikan relasi)
        
                    $recipients = collect([$admin, $user])->filter(); // buang yang null
                    $nominal = 'Rp ' . number_format($record->amount, 0, ',', '.');

                    if ($admin) {
                        Notification::make()
                            ->title('Penarikan ' . $nominal . ' dibuat')
                            ->body('Nasabah: ' . $user->name)
                            ->success()
                            ->sendToDatabase($admin);
                    }
                    if ($user) {
                        Notification::make()
                            ->title('Penarikan ' . $nominal . ' berhasil dibuatkan Admin')
                            ->body('Jika anda tidak merasa menerima penarikan ini, silahkan hubungi admin.')
                            ->success()
                            ->sendToDatabase($user);
                    }
                }),
        ];
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
