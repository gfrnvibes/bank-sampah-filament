<?php

namespace App\Filament\Widgets;

use App\Models\BalanceWithdrawal;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Models\WasteDeposit;
use App\Models\WasteSale;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class StatsOverview extends StatsOverviewWidget
{

    protected function getStats(): array
    {
        return [
            // Waste Sale Total Income - BalanceWithdrawal Total Amount            
            Stat::make('Saldo Bank Sampah', 
                'Rp ' . number_format(
                    max(0, WasteSale::sum('total_income') - BalanceWithdrawal::where('status', 'completed')->sum('amount')), 
                    0, '.', '.'
                ))
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),
            
            // Outcome dari transaction history
            Stat::make('Pengeluaran', 'Rp ' . number_format(BalanceWithdrawal::where('status', 'completed')->sum('amount'), 0, '.', '.'))
                // ->description('12k decrease')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger')
                ->descriptionIcon('heroicon-m-arrow-trending-down', IconPosition::Before),

            
            Stat::make('Total Transaksi', number_format(TransactionHistory::count(), 0, '.', '.'))
                // ->description('12k transaksi')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),

            // Total Berat Sampah Terjual, pakai suffix kg
            Stat::make('Berat Sampah Terjual', number_format(WasteSale::sum('total_weight'), 0, '.', '.').' Kg')
                ->color('success'),

            // Total Berat Sampah Terkumpul
            Stat::make('Berat Sampah Terkumpul', number_format(WasteDeposit::sum('total_weight'), 0, '.', '.').' Kg')
                ->color('danger'),
            
            // Total User Aktif
            Stat::make('Nasabah Aktif', User::where('id', '!=', 1)->count())
                ->color('primary'),
        ];
    }
}
