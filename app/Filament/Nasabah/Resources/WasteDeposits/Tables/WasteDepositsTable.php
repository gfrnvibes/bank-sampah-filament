<?php

namespace App\Filament\Nasabah\Resources\WasteDeposits\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
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
            ->heading('Riwayat Penyetoran Sampah')
            ->modifyQueryUsing(
                fn($query) =>
                $query->where('user_id', auth()->id())
            )
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d/m/y, h:i')
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
                    ->color('danger')
                    ->suffix(' Kg')
                    ->alignCenter()
                    ->summarize(Sum::make()->suffix(' Kg'))
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Jumlah Pendapatan')
                    ->badge()
                    ->size('xl')
                    ->color('success')
                    ->money('IDR', decimalPlaces: 0, locale: 'id_ID')
                    ->summarize(Sum::make()->money('IDR', decimalPlaces: 0, locale: 'id_ID'))
                    ->sortable(),
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->default('-')
                    ->limit(50)
                    ->toggleable(),
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
                                    fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                                )
                                ->when(
                                    $data['created_until'] ?? null,
                                    fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                                );
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    // EditAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }

}
