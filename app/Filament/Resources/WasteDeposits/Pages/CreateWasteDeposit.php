<?php

namespace App\Filament\Resources\WasteDeposits\Pages;

use App\Filament\Resources\WasteDeposits\WasteDepositResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Icons\Heroicon;

class CreateWasteDeposit extends CreateRecord
{
    protected static string $resource = WasteDepositResource::class;

    public function getHeading(): string
    {
        return 'Tambah Setoran Sampah';
    }

    protected function afterCreate(): void
    {
        // Admin yang lagi login
        $admin = auth()->user();

        // Ambil nasabah yang dipilih saat create
        // Sesuaikan:
        // - Kalau model Setoran punya relasi `nasabah()`, pakai yg ini:
        // $nasabah = $this->record->nasabah;
        //
        // - Kalau cuma punya field `user_id`:
        $nasabah = User::find($this->record->user_id);

        // Safety: kalau nasabah ga ketemu, ya udah notif ke admin aja
        if (!$nasabah) {
            Notification::make()
                ->title('Setoran sampah baru dibuat')
                ->body('Data setoran sampah berhasil dibuat oleh ' . $admin->name)
                ->icon(Heroicon::Truck) // sesuaikan icon kamu
                ->sendToDatabase($admin);

            return;
        }

        // Kirim ke admin + nasabah sekaligus
        Notification::make()
            ->title('Berhasil Setor Sampah')
            ->body('Setoran sampah atas nama ' . $nasabah->name . ' berhasil dibuat.')
            ->icon(Heroicon::Truck) // ganti sesuai icon yg kamu pakai
            ->sendToDatabase([$admin, $nasabah]);
    }

}
