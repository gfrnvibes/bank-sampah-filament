<?php

namespace App\Livewire;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Models\BalanceWithdrawal;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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

class LaporanPenarikan extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithActions;

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Laporan Penarikan Saldo')
            ->query(BalanceWithdrawal::query())
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Nasabah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Jumlah Penarikan')
                    ->money('IDR', decimalPlaces: 0, locale: 'id_ID')
                    ->badge()
                    ->size('xl')
                    ->color('success')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->size('xl')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'accepted' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'completed' => 'Selesai',
                        default => ucfirst($state),
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'accepted' => 'primary',
                        'rejected' => 'danger',
                        'completed' => 'success',
                    })
                    ->icon(fn(string $state): ?string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'accepted' => 'heroicon-o-information-circle',
                        'rejected' => 'heroicon-o-x-circle',
                        'completed' => 'heroicon-o-check-circle',
                        default => null,
                    }),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            // ->recordUrl(fn(BalanceWithdrawal $record): string => BalanceWithdrawalResource::getUrl('view', ['record' => $record->id]))
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
                FilamentExportHeaderAction::make('export'),
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
                ]),
            ]);
    }

    public function render()
    {
        return view('livewire.laporan-penarikan');
    }
}
