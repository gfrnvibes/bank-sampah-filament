<?php

namespace App\Filament\Nasabah\Pages\Auth;

use App\Models\User;
use Filament\Auth\Pages\Register;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;

class NasabahRegister extends Register
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),

                TextInput::make('nik')
                    ->label('NIK')
                    ->rule('regex:/^[0-9]{16}$/')
                    ->required()
                    ->mask('9999999999999999')
                    ->unique(ignoreRecord: true)
                    ->extraInputAttributes(['inputmode' => 'numeric']) // biar keyboard HP muncul angka semua
                    ->maxLength(16),

                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                
            ]);
    }

    protected function handleRegistration(array $data): Model
    {
        $user = User::create($data);

        // Ambil admin default id 1
        $admin = User::find(1);

        // Notif ke admin (kalau ketemu)
        if ($admin) {
            Notification::make()
                ->title('Nasabah baru terdaftar')
                ->body('Akun ' . $user->name . ' baru saja mendaftar.')
                ->icon('heroicon-o-user-plus')
                ->sendToDatabase($admin);
        }

        // Notif ke user yang baru daftar
        if ($user) {
            Notification::make()
                ->title('Pendaftaran berhasil')
                ->body('Halo ' . $user->name . ', akun kamu sudah berhasil dibuat.')
                ->icon('heroicon-o-check-circle')
                ->sendToDatabase($user);
        }

        return $user;
    }
}
