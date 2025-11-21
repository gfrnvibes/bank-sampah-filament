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
                            TextEntry::make('name'),
                            TextEntry::make('age'),
                            TextEntry::make('balance')
                                ->badge()
                                ->size('xl')
                                ->color('success')
                                ->money('IDR', decimalPlaces: 0, locale: 'id_ID'),
                        ]),
                        Grid::make(2)->schema([
                            TextEntry::make('nik'),
                            TextEntry::make('phone'),
                        ]),
                        Grid::make(3)->schema([
                            TextEntry::make('dusun'),
                            TextEntry::make('rt'),
                            TextEntry::make('rw'),
                        ]),
                    ]),


                Section::make('Akun')
                    ->schema([
                         Grid::make(2)->schema([
                             TextEntry::make('email')
                                 ->label('Email address'),
     
                             IconEntry::make('is_active')
                                 ->boolean(),
                         ]),
                        ImageEntry::make('avatar'),
                        Grid::make(2)->schema([
                            TextEntry::make('created_at')
                                ->dateTime(),
                            TextEntry::make('updated_at')
                                ->dateTime()
                        ]),
                    ]),
                    ]);

    }
}
