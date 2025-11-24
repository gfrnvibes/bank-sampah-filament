<?php

namespace App\Filament\Widgets;

use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{

    protected function getStats(): array
    {
        return [
            Stat::make('Saldo Bank', '192.1k')
                ->description('32k increase')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),
            Stat::make('Income', '21%')
                ->description('43k increase')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),
            Stat::make('Outcome', '3:12')
                ->description('12k decrease')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger')
                ->descriptionIcon('heroicon-m-arrow-trending-down', IconPosition::Before),
        ];
    }
}
