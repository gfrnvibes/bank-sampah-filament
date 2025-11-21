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
            Stat::make('Pendapatan (Rp)',
                User::where('id', $userId)
                    ->sum('balance')
            ),

            Stat::make('Berat Sampah Terkumpul (Kg)',
                WasteDeposit::where('user_id', $userId)
                    ->sum('total_weight')
            ),

            Stat::make('Setor Sampah',
                WasteDeposit::where('user_id', $userId)
                    ->count()
            ),
        ];
    }
}
