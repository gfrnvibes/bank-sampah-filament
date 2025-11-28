<?php

namespace App\Filament\Nasabah\Widgets;

use App\Models\TransactionHistory;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestTransactionHistory extends TableWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->heading(heading: 'Riwayat Transaksi Terbaru')
            ->description('Lima transaksi terakhir pada akun Anda.')
            ->paginated(false)
            ->query(fn (): Builder => TransactionHistory::query()
                ->where('user_id', auth()->id())
                ->latest()
                ->limit(5))
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal & Waktu')
                    ->dateTime()
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
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('Lihat Semua')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url('/nasabah/riwayat-transaksi'),
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
