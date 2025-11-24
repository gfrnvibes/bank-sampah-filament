<?php

namespace App\Filament\Nasabah\Widgets;

use App\Models\User;
use App\Models\WasteDeposit;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    
    protected function getStats(): array
    {
        $userId = auth()->user()?->id;

        return [
            // number format with RP currency
            Stat::make('Saldo Kamu',
                'Rp ' . number_format(User::where('id', $userId)
                    ->sum('balance'), 0, ',', '.')
            )
            ->color('success'),

             // number format with KG currency
            Stat::make('Berat Sampah Terkumpul',
                number_format(WasteDeposit::where('user_id', $userId)
                    ->sum('total_weight'), 0, ',', '.') . ' Kg'
            )
            ->color('warning'),

            // prefix x
            Stat::make('Setor Sampah',
                WasteDeposit::where('user_id', $userId)
                    ->count() . 'x (kali)'
            )
            ->color('primary'),
        ];
    }
}
