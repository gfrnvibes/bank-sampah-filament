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
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Jumlah Pendapatan')
                    ->badge()
                    ->size('xl')
                    ->color('success')
                    ->money('IDR', decimalPlaces: 0, locale: 'id_ID')
                    ->summarize(Sum::make()->money('IDR', decimalPlaces: 0, locale: 'id_ID'))
                    ->sortable(),
                TextColumn::make('total_weight')
                        ->label('Total Berat')
                        ->numeric()
                        ->color('danger')
                        ->suffix(' Kg')
                        ->alignCenter()
                        ->summarize(Sum::make()->suffix(' Kg'))
                        ->sortable(),
                TextColumn::make('waste_items')
                    ->label('Jenis Sampah')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) {
                            return '-';
                        }

                        if (!is_array($state)) {
                            $decoded = json_decode($state, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $state = $decoded;
                            } else {
                                return '-';
                            }
                        }

                        $ids = [];
                        foreach ($state as $item) {
                            if (!empty($item['waste_type_id'])) {
                                $ids[] = $item['waste_type_id'];
                            }
                        }

                        $ids = array_values(array_unique($ids));

                        if (!empty($ids)) {
                            $namesById = \App\Models\WasteType::whereIn('id', $ids)
                                ->pluck('name', 'id')
                                ->toArray();

                            $names = [];
                            foreach ($state as $item) {
                                if (!empty($item['waste_type_id']) && isset($namesById[$item['waste_type_id']])) {
                                    $names[] = $namesById[$item['waste_type_id']];
                                } elseif (!empty($item['waste_type'])) {
                                    $names[] = $item['waste_type'];
                                }
                            }

                            $names = array_values(array_unique($names));
                            return $names ? implode(', ', $names) : '-';
                        }

                        // Fallback: maybe names were stored directly in the repeater items
                        $direct = [];
                        foreach ($state as $item) {
                            if (!empty($item['waste_type'])) {
                                $direct[] = $item['waste_type'];
                            }
                        }

                        $direct = array_values(array_unique($direct));
                        return $direct ? implode(', ', $direct) : '-';
                    }),
                TextColumn::make('notes')
                    ->label('Catatan')
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
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

}
