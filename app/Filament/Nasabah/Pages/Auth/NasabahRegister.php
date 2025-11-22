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
                    ->numeric()
                    ->rule('digits:16')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(16),

                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                
            ]);
    }
}
