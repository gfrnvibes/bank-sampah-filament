<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn($query) =>
                $query->where('id', '!=', 1)
            )
            ->columns([
                ImageColumn::make('avatar')
                    ->label('Foto Profil')
                    ->circular()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('balance')
                    ->label('Saldo')
                    ->money('IDR', decimalPlaces: 0, locale: 'id_ID')
                    ->badge()
                    ->size('xl')
                    ->color('success')
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone')
                    ->label('No. Telepon')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('dusun')
                    ->label('Dusun')
                    ->searchable(),
                TextColumn::make('rt')
                    ->label('RT')
                    ->searchable(),
                TextColumn::make('rw')
                    ->label('RW')
                    ->searchable(),
                TextColumn::make('age')
                    ->label('Usia')
                    ->searchable(),
                TextColumn::make('no_rek')
                    ->label('No. Rekening')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->alignCenter()
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diupdate Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])->striped();
    }
}
