<?php

namespace App\Filament\Resources\WasteDeposits\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;

class WasteDepositsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->listWithLineBreaks()
                    ->date()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Nasabah')
                    ->searchable()
                    ->sortable(),
                ColumnGroup::make('Detail Sampah', [
                    TextColumn::make('items.wasteType.name')
                        ->label('Nama')
                        ->bulleted()
                        ->limitList(3)
                        ->searchable()
                        ->expandableLimitedList(),
                    TextColumn::make('items.weight_kg')
                        ->label('Berat')
                        ->suffix(' Kg')
                        ->bulleted()
                        ->limitList(3)
                        ->expandableLimitedList(),
                    TextColumn::make('items.subtotal')
                        ->label('Subtotal')
                        ->money('IDR', decimalPlaces: 0, locale: 'id_ID')
                        ->bulleted()
                        ->limitList(3)
                        ->expandableLimitedList(),
                ]),
                TextColumn::make('total_weight')
                    ->label('Total Berat')
                    ->numeric()
                    ->suffix(' Kg')
                    ->color('danger')
                    ->alignCenter()
                    ->summarize(Sum::make()->label('Jumlah')->suffix(' Kg'))
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Total Pendapatan')
                    ->badge()
                    ->size('xl')
                    ->color('success')
                    ->money('IDR', decimalPlaces: 0, locale: 'id_ID')
                    ->summarize(Sum::make()->label('Jumlah')->money('IDR', decimalPlaces: 0, locale: 'id_ID'))
                    ->sortable(),
                TextColumn::make('notes')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('advanced')
                    ->schema([
                        DatePicker::make('created_from')->label('Created from'),
                        DatePicker::make('created_until')->label('Created until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->recordActions([
                // ViewAction::make(),
                ActionGroup::make([
                    ViewAction::make(),
                    // EditAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}