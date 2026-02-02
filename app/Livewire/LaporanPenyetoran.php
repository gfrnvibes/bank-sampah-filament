<?php

namespace App\Livewire;

use Livewire\Component;
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
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use App\Filament\Resources\WasteDeposits\WasteDepositResource;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

class LaporanPenyetoran extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithActions;

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Laporan Penyetoran Sampah')
            ->description('Setoran Sampah dari Nasabah')
            ->query(WasteDeposit::query())
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal')
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
                        ->searchable(),
                    TextColumn::make('items.weight_kg')
                        ->label('Berat')
                        ->bulleted()
                        ->getStateUsing(function ($record) {
                            // Ambil data langsung dari relationship model
                            // Ini memastikan kita mendapatkan Collection/Array, bukan JSON string
                            return $record->items->pluck('weight_kg')->toArray();
                        })
                        ->formatStateUsing(function ($state) {
                            // Sekarang formatStateUsing akan menerima array hasil dari getStateUsing
                            if (is_array($state)) {
                                return collect($state)->map(fn ($value) => 
                                    number_format((float) $value, 1, ',', '.') . ' Kg'
                                );
                            }
                            
                            return number_format((float) $state, 1, ',', '.') . ' Kg';
                        }),
                TextColumn::make('items.subtotal')
                    ->label('Subtotal')
                    ->bulleted()
                    ->formatStateUsing(function ($state) {
                        // Jika $state ternyata masih array, ambil nilai pertamanya
                        // Ini mencegah error "array given" pada number_format
                        $value = is_array($state) ? ($state[0] ?? 0) : $state;

                        return 'Rp ' . number_format((float) $value, 0, ',', '.');
                    }),
                ]),
                TextColumn::make('total_weight')
                    ->label('Total Berat')
                    ->numeric()
                    ->suffix(' Kg')
                    ->color('danger')
                    ->alignCenter()
                    ->summarize(Sum::make()->suffix(' Kg'))
                    ->formatStateUsing(fn ($state) => number_format($state, 1, ',', '.'))
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Total Pendapatan')
                    ->badge()
                    ->size('xl')
                    ->color('success')
                    ->summarize(Sum::make()-> money('IDR', decimalPlaces: 0, locale: 'id_ID'))
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
            ->recordUrl(fn(WasteDeposit $record): string => WasteDepositResource::getUrl('view', ['record' => $record->id]))
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
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\ViewAction::make()->url(fn (Milestone $record) => MilestoneResource::getUrl('view', ['record' => $record->id]))

            ])
            ->headerActions([
                    FilamentExportHeaderAction::make('export')
    ->disableAdditionalColumns()
    // ->formatStates([
    //     'waste_names' => function ($state, $record) {
    //         if (!$record) return '';
            
    //         return $record->items->map(function ($item) {
    //             return '• ' . ($item->wasteType?->name ?? '-');
    //         })->implode("\n"); // Menggunakan \n
    //     },

    //     'weights' => function ($state, $record) {
    //         if (!$record) return '';

    //         return $record->items->map(function ($item) {
    //             return '• ' . number_format((float) $item->weight_kg, 1, ',', '.') . ' Kg';
    //         })->implode("\n");
    //     },

    //     'subtotals' => function ($state, $record) {
    //         if (!$record) return '';

    //         return $record->items->map(function ($item) {
    //             return '• Rp ' . number_format((float) $item->subtotal, 0, ',', '.');
    //         })->implode("\n");
    //     },
    // ])

                // CreateAction::make()
                //     ->mutateFormDataUsing(fn(array $data): array => WasteDeposit::mutateFormDataBeforeCreate($data))
                //     ->visible(url()->current() != WasteDepositResource::getUrl('index')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                    // ForceDeleteBulkAction::make(),
                    // RestoreBulkAction::make(),
                    FilamentExportBulkAction::make('export')
                        ->label('Expor yang dipilih')
                        ->disableAdditionalColumns(),
                ]),
            ]);
    }

    public function render()
    {
        return view('livewire.laporan-penyetoran');
    }
}
