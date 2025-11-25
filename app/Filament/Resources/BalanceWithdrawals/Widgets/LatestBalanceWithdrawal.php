<?php

namespace App\Filament\Resources\BalanceWithdrawals\Widgets;

use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\BalanceWithdrawal;
use Filament\Widgets\TableWidget;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BalanceWithdrawals\BalanceWithdrawalResource;

class LatestBalanceWithdrawal extends TableWidget
{
    protected int|string|array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->heading('Penarikan Saldo Terbaru')
            ->paginated(false)
            ->query(fn (): Builder => BalanceWithdrawal::query()->latest()->take(10))
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime(),
                TextColumn::make('user.name')
                    ->label('Nasabah'),
                TextColumn::make('amount')
                    ->label('Jumlah Penarikan')
                    ->money('IDR', decimalPlaces: 0, locale: 'id_ID')
                    ->badge()
                    ->size('xl')
                    ->color('success'),
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
            ])
            ->filters([
                //
            ])
            ->recordActions([
                
            ])
            ->headerActions([
                Action::make('Lihat Semua')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url('/admin/penarikan-saldo'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
