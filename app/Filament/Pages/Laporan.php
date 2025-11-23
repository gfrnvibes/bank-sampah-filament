<?php

namespace App\Filament\Pages;

use UnitEnum;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use App\Livewire\LaporanPenjualan;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Tabs\Tab;
use App\Livewire\Laporan as LivewireLaporan;

class Laporan extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::DocumentText;
    protected string $view = 'filament.pages.laporan';
    protected static string|UnitEnum|null $navigationGroup = 'Menu';
    protected static ?int $navigationSort = 5;

    public function getTabs(): array
    {
        return [
            'All' => Tab::make('All')->schema([
                Livewire::make(LaporanPenjualan::class)->key('active-tasks-table')
            ]),
        ];
    }

    public function infolist(Schema $infolist): Schema
    {
        return $infolist
            ->schema([
                Tabs::make('Tabs')->contained(false)
                    ->tabs([
                        Tab::make('active-tasks')->label('Penyetoran')
                            ->schema([
                                Livewire::make(LaporanPenjualan::class)->key('active-tasks-table')
                            ]),
                        Tab::make('Tab 2')->label('Penjualan')
                            ->schema([
                                Livewire::make(LaporanPenjualan::class)->key('active-tasks-table-2')->lazy()
                            ]),
                        Tab::make('Tab 3')->label('Penarikan')
                            ->schema([
                                Livewire::make(LaporanPenjualan::class)->key('active-tasks-table-3')->lazy()
                            ]),
                    ]),
            ]);
    }
}
