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
                                ->label('Nama')
                                ->required(),
                            TextInput::make('age')
                                ->label('Usia')
                                ->numeric()
                                ->rule('integer'),
                        ]),
                        TextInput::make('nik')
                                ->label('NIK')
                                ->required()
                                ->numeric()
                                ->string()
                                ->rule('string')
                                ->rule('digits:16')
                                ->unique('users', 'nik', ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => 'NIK ini sudah terdaftar.',
                                    'digits' => 'NIK harus terdiri dari 16 digit.',
                                ]),

                        TextInput::make('phone')
                                ->label('No. Telepon')
                                ->tel()
                                ->numeric()
                                ->minLength(10)
                                ->maxLength(13)
                                ->unique('users', 'phone', ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => 'No. Telepon ini sudah terdaftar.',
                                ]),

                        Grid::make(3)->schema([
                            TextInput::make('dusun')
                                ->label('Dusun'),
                            TextInput::make('rt')
                                ->label('RT')
                                ->numeric()
                                ->maxLength(3),
                            TextInput::make('rw')
                                ->label('RW')
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
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique('users', 'email', ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'Email ini sudah terdaftar.',
                            ]),

                        // Password tetap menampilkan nilai lama
                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(
                                fn($state, $record) =>
                                $state ? bcrypt($state) : $record->password
                            )
                            ->required(fn($record) => $record === null),

                        // Toggle::make('is_active')
                        //         ->label('Status Akun')
                        //         ->required(),

                        FileUpload::make('avatar')
                            ->image() // WAJIB untuk preview
                            ->directory('avatars')
                            ->label('Foto Profil')
                            ->visibility('public')

                    ]),
            ])
            ->columns(2);

    }
}
