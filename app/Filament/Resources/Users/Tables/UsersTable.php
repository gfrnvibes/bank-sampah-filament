<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\Action;
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
                    ->toggleable(),
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('phone')
                    ->label('No. Telepon')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('dusun')
                    ->label('Dusun')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('rt')
                    ->label('RT')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('rw')
                    ->label('RW')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('age')
                    ->label('Usia')
                    ->suffix(' Tahun')
                    ->searchable()
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label('Verifikasi')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),
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
                    Action::make('activate')
                        ->label('Verifikasi Nasabah')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->update(['is_active' => true]);
                        })
                        ->icon('heroicon-s-check-circle')
                        ->color('success')
                        ->visible(fn($record) => $record->is_active === false),
                    Action::make('activate')
                        ->label('Blokir Nasabah')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->update(['is_active' => false]);
                        })
                        ->icon('heroicon-s-exclamation-triangle')
                        ->color('danger')
                        ->visible(fn($record) => $record->is_active === true),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])->striped();
    }
}
