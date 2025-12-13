<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\WasteSale;
use Filament\Tables\Table;
use App\Models\WasteDeposit;
use Filament\Actions\CreateAction;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Contracts\HasActions;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use App\Filament\Resources\WasteSales\WasteSaleResource;
use App\Filament\Resources\WasteDeposits\WasteDepositResource;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

class LaporanPenjualan extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithActions;

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Laporan Penjualan Sampah')
            ->description('Penjualan sampah ke Pengepul')
            ->query(WasteSale::query())
            ->columns([
            TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date()
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
            ->recordUrl(fn(WasteSale $record): string => WasteSaleResource::getUrl('view', ['record' => $record->id]))
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
            ->actions([
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\ViewAction::make()->url(fn (Milestone $record) => MilestoneResource::getUrl('view', ['record' => $record->id]))

            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->disableAdditionalColumns(),
                // CreateAction::make()
                //     ->mutateFormDataUsing(fn(array $data): array => WasteDeposit::mutateFormDataBeforeCreate($data))
                //     ->visible(url()->current() != WasteDepositResource::getUrl('index')),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    FilamentExportBulkAction::make('export')
                        ->disableAdditionalColumns(),
                ]),
            ]);
    }

    public function render()
    {
        return view('livewire.laporan-penjualan');
    }
}
