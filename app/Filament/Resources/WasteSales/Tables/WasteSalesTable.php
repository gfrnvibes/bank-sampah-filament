<?php

namespace App\Filament\Resources\WasteSales\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WasteSalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
            TextColumn::make('waste_item')
                ->label('Jenis Sampah'),
            TextColumn::make('total_weight')
                ->label('Total Berat')
                ->numeric()
                ->suffix(' Kg')
                ->color('danger')
                ->sortable(),
            TextColumn::make('total_income')
                ->label('Total Pemasukan')
                ->money('IDR', decimalPlaces: 0, locale: 'id_ID')
                ->badge()
                ->size('xl')
                ->sortable(),
            TextColumn::make('buyer')
                ->label('Pembeli')
                ->searchable(),
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
                 ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
