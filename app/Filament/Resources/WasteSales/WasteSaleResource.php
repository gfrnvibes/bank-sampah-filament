<?php

namespace App\Filament\Resources\WasteSales;

use App\Filament\Resources\WasteSales\Pages\CreateWasteSale;
use App\Filament\Resources\WasteSales\Pages\EditWasteSale;
use App\Filament\Resources\WasteSales\Pages\ListWasteSales;
use App\Filament\Resources\WasteSales\Pages\ViewWasteSale;
use App\Filament\Resources\WasteSales\Schemas\WasteSaleForm;
use App\Filament\Resources\WasteSales\Schemas\WasteSaleInfolist;
use App\Filament\Resources\WasteSales\Tables\WasteSalesTable;
use App\Models\WasteSale;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WasteSaleResource extends Resource
{
    protected static ?string $model = WasteSale::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::ShoppingBag;

    protected static ?string $navigationLabel = 'Penjualan Sampah';

    protected static ?string $slug = 'penjualan-sampah';
    protected static ?string $pluralModelLabel = 'Penjualan Sampah';

    protected static ?int $navigationSort = 2;

    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';

    public static function form(Schema $schema): Schema
    {
        return WasteSaleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WasteSaleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WasteSalesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWasteSales::route('/'),
            'create' => CreateWasteSale::route('/create'),
            'view' => ViewWasteSale::route('/{record}'),
            'edit' => EditWasteSale::route('/{record}/edit'),
        ];
    }
}
