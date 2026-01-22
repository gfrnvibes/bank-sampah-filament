<?php

namespace App\Livewire;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\WasteDeposits\WasteDepositResource;
use App\Models\TransactionHistory;
use App\Models\WasteDeposit;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class LaporanTransaksi extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithActions;

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Laporan Transaksi')
            ->description('Penyetoran dan Penarikan Saldo Nasabah')
            ->query(TransactionHistory::query())
            ->columns([
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('user.name')
                    ->label('Nasabah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        TransactionHistory::TYPE_DEPOSIT => 'success',
                        TransactionHistory::TYPE_WITHDRAWAL => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        TransactionHistory::TYPE_DEPOSIT => 'Penyetoran',
                        TransactionHistory::TYPE_WITHDRAWAL => 'Penarikan',
                    })
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR', locale: 'id_ID', decimalPlaces: 0)
                    ->prefix(fn($record) => $record->type === TransactionHistory::TYPE_DEPOSIT ? '+ ' : '- ')
                    ->color(fn($record) => match ($record->type) {
                        TransactionHistory::TYPE_DEPOSIT => 'success',
                        TransactionHistory::TYPE_WITHDRAWAL => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('balance_before')
                    ->label('Saldo Sebelum')
                    ->money('IDR', locale: 'id_ID', decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('balance_after')
                    ->label('Saldo Setelah')
                    ->money('IDR', locale: 'id_ID', decimalPlaces: 0)
                    ->sortable(),
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
        return view('livewire.laporan-transaksi');
    }
}
