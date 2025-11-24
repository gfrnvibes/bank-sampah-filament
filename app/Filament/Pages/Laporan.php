<?php

namespace App\Filament\Pages;

use UnitEnum;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use App\Livewire\LaporanPenarikan;
use App\Livewire\LaporanPenjualan;
use App\Livewire\LaporanPenyetoran;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Tabs\Tab;
use App\Livewire\Laporan as LivewireLaporan;

class Laporan extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::ClipboardDocumentList;
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
                Tabs::make('Tabs')
                    ->contained(false)
                    ->tabs([
                        Tab::make('active-tasks')->label('Penyetoran')
                            ->schema([
                                Livewire::make(LaporanPenyetoran::class)->key('active-tasks-table')
                            ])->icon(Heroicon::Truck),
                        Tab::make('Tab 2')->label('Penjualan')
                            ->schema([
                                Livewire::make(LaporanPenjualan::class)->key('active-tasks-table-2')->lazy()
                            ])->icon(Heroicon::ShoppingBag),
                        Tab::make('Tab 3')->label('Penarikan')
                            ->schema([
                                Livewire::make(LaporanPenarikan::class)->key('active-tasks-table-3')->lazy()
                            ])->icon(Heroicon::Banknotes),
                    ]),
            ]);
    }
}
