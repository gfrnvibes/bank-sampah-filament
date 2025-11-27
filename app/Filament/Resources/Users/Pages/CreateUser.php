<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Icons\Heroicon;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        return 'Buat Nasabah Baru';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {   
        $recipient = auth()->user();

        Notification::make()
            ->title( 'Nasabah Baru: ' . $recipient->name)
            ->body('Berhasil Terdaftar')
            ->icon(Heroicon::User)
            ->sendToDatabase($recipient);

        return $data;
    }
}
