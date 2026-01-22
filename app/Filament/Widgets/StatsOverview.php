<?php

namespace App\Filament\Widgets;

use App\Models\BalanceWithdrawal;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Models\WasteDeposit;
use App\Models\WasteSale;
use App\Models\WasteType;
use App\Models\WasteDepositItem;
use App\Models\WasteSaleItem;
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
        $stats = [
            // Waste Sale Total Income - BalanceWithdrawal Total Amount            
            Stat::make('Saldo Bank Sampah', 
                'Rp ' . number_format(
                    max(0, WasteSale::sum('total_income') - BalanceWithdrawal::where('status', 'completed')->sum('amount')), 
                    0, '.', '.'
                )),
            
            // Outcome dari transaction history
            Stat::make('Pengeluaran', 'Rp ' . number_format(BalanceWithdrawal::where('status', 'completed')->sum('amount'), 0, '.', '.')),

            
            Stat::make('Total Transaksi', number_format(TransactionHistory::count(), 0, '.', '.')),

            // Total Berat Sampah Terjual, pakai suffix kg
            Stat::make('Berat Sampah Terjual', number_format(WasteSale::sum('total_weight'), 0, '.', '.').' Kg')
                ->color('success'),

            // Total Berat Sampah Terkumpul
            Stat::make('Berat Sampah Terkumpul', number_format(WasteDeposit::sum('total_weight'), 0, '.', '.').' Kg')
                ->color('danger'),
            
            // Total User Aktif
            Stat::make('Nasabah Aktif', User::where('id', '!=', 1)->where('is_active', true)->count())
                ->color('primary'),
        ];

        // Tambahkan widget untuk setiap jenis sampah
        $wasteTypes = WasteType::all();
        foreach ($wasteTypes as $wasteType) {
            $depositWeight = WasteDepositItem::where('waste_type_id', $wasteType->id)->sum('weight_kg');
            $saleWeight = WasteSaleItem::where('waste_type_id', $wasteType->id)->sum('weight_kg');
            $totalWeight = max(0, $depositWeight - $saleWeight);
            
            $stats[] = Stat::make($wasteType->name, number_format($totalWeight, 0, '.', '.') . ' Kg')
                ->color('info');
        }

        return $stats;
    }
}
