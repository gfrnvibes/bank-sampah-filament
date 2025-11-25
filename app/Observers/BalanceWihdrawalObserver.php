<?php

namespace App\Observers;

use App\Models\BalanceWithdrawal;
use Filament\Notifications\Notification;

class BalanceWihdrawalObserver
{
    /**
     * Handle the BalanceWithdrawal "created" event.
     */
    public function created(BalanceWithdrawal $balanceWithdrawal): void
    {
        $recipient = auth()->user();

        Notification::make()
            ->title('Saved successfully')
            ->sendToDatabase($recipient);
    }

    /**
     * Handle the BalanceWithdrawal "updated" event.
     */
    public function updated(BalanceWithdrawal $balanceWithdrawal): void
    {
        //
    }

    /**
     * Handle the BalanceWithdrawal "deleted" event.
     */
    public function deleted(BalanceWithdrawal $balanceWithdrawal): void
    {
        //
    }

    /**
     * Handle the BalanceWithdrawal "restored" event.
     */
    public function restored(BalanceWithdrawal $balanceWithdrawal): void
    {
        //
    }

    /**
     * Handle the BalanceWithdrawal "force deleted" event.
     */
    public function forceDeleted(BalanceWithdrawal $balanceWithdrawal): void
    {
        //
    }
}
