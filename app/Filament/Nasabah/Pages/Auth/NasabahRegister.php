<?php

namespace App\Filament\Nasabah\Pages\Auth;

use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Auth\Pages\Register;
use Filament\Forms\Components\TextInput;

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
}
