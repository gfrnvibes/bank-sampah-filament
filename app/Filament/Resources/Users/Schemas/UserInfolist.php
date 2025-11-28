<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Identitas Diri')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('name')
                                ->label('Nama'),
                            TextEntry::make('age')
                                ->label('Usia'),
                            TextEntry::make('balance')
                                ->label('Saldo')
                                ->badge()
                                ->size('xl')
                                ->color('success')
                                ->money('IDR', decimalPlaces: 0, locale: 'id_ID'),
                        ]),
                        Grid::make(2)->schema([
                            TextEntry::make('nik')
                                ->label('NIK')
                                // format agar NIK tampil tidak 
                                ,
                            TextEntry::make('phone')
                                ->label('No. Telepon'),
                        ]),
                        Grid::make(3)->schema([
                            TextEntry::make('dusun')
                                ->label('Dusun'),
                            TextEntry::make('rt')
                                ->label('RT'),
                            TextEntry::make('rw')
                                ->label('RW'),
                        ]),
                    ]),

                Section::make('Akun')
                    ->schema([
                         Grid::make(2)->schema([
                             TextEntry::make('email')
                                 ->label('Email'),
     
                             IconEntry::make('is_active')
                                ->label('Status Akun')
                                 ->boolean(),
                         ]),
                        ImageEntry::make('avatar')
                                ->label('Foto Profil'),
                        Grid::make(2)->schema([
                            TextEntry::make('created_at')
                                ->label('Dibuat pada')
                                ->dateTime(),
                            TextEntry::make('updated_at')
                                ->label('Diperbarui pada')
                                ->dateTime(),
                        ]),
                    ]),
            ]);
    }
}
