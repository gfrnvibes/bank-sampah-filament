<?php

namespace App\Filament\Nasabah\Resources\BalanceWithdrawals\Pages;

use App\Models\User;
use Filament\Facades\Filament;
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
                ->after(function (CreateAction $action) {
                    $record = $action->getRecord();

                    // ambil user pemilik saldo dari relasi
                    $user = $record->user; // pastikan relasinya ada di model
        
                    // ✅ SAFETY CHECK: kalau user nggak ada atau saldo kurang, stop di sini
                    if (!$user || $user->balance < $record->amount) {
                        return; // JANGAN kirim notif
                    }

                    // admin dengan id = 1
                    $admin = User::find(1);

                    // user yang lagi login (bisa admin, bisa user biasa tergantung panel)
                    $loggedInUser = Filament::auth()->user();

                    // kirim ke admin id 1 + user login + pemilik saldo
                    $recipients = collect([$admin, $loggedInUser, $user])
                        ->filter()
                        ->unique('id'); // biar nggak dobel kalau sama
        
                    $nominal = 'Rp ' . number_format($record->amount, 0, ',', '.');

                    if ($admin){
                        Notification::make()
                            ->title($user->name . ' mengajukan penarikan ' . $nominal)
                            ->body('Segera konfirmasikan penarikan saldo ini.')
                            ->success()
                            ->sendToDatabase($admin);
                    } 
                    if ($user) {
                        Notification::make()
                            ->title('Pengajuan Penarikan: ' . $nominal)
                            ->body('Pengajuan penarikan saldo berhasil. Tunggu konfirmasi admin.')
                            ->success()
                            ->sendToDatabase($user);
                    }
                })

        ];
    }
}
