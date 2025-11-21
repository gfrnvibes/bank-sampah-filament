<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Identitas Diri')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->required(),
                            TextInput::make('age')
                                ->numeric()
                                ->rule('integer'),
                        ]),
                        TextInput::make('nik')
                            ->required()
                            ->numeric()
                            ->rule('digits:16')
                            ->maxLength(16),

                        TextInput::make('phone')
                            ->tel()
                            ->numeric()
                            ->rule('integer')
                            ->minLength(10)
                            ->maxLength(13),

                        // Dusun + RT + RW dalam 1 baris
                        Grid::make(3)->schema([
                            TextInput::make('dusun'),
                            TextInput::make('rt')
                                ->numeric()
                                ->maxLength(3),
                            TextInput::make('rw')
                                ->numeric()
                                ->maxLength(3),
                        ]),

                        // TextInput::make('no_rek')
                        //     ->numeric()
                        //     ->rule('integer'),
                    ]),

                Section::make('Akun')
                    ->schema([
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required(),

                        // Password tetap menampilkan nilai lama
                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(
                                fn($state, $record) =>
                                $state ? bcrypt($state) : $record->password
                            )
                            ->required(fn($record) => $record === null),

                        Toggle::make('is_active')
                            ->required(),

                        FileUpload::make('avatar')
                            ->image() // WAJIB untuk preview
                            ->directory('avatars')
                            ->visibility('public')

                    ]),
            ])
            ->columns(2);

    }
}
