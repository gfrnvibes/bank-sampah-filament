<?php

namespace App\Filament\Resources\WasteDeposits\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class WasteDepositsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Nasabah')
                    ->searchable()
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
                TextColumn::make('total_weight')
                    ->label('Total Berat')
                    ->numeric()
                    ->suffix(' Kg')
                    ->color('danger')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Total Pendapatan')
                    ->badge()
                    ->size('xl')
                    ->color('success')
                    ->money('IDR', decimalPlaces: 0, locale: 'id_ID')
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
            ])
            ->recordActions([
                // ViewAction::make(),
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
