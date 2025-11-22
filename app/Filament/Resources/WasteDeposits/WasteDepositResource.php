<?php

namespace App\Filament\Resources\WasteDeposits;

use App\Filament\Resources\WasteDeposits\Pages\CreateWasteDeposit;
use App\Filament\Resources\WasteDeposits\Pages\EditWasteDeposit;
use App\Filament\Resources\WasteDeposits\Pages\ListWasteDeposits;
use App\Filament\Resources\WasteDeposits\Pages\ViewWasteDeposit;
use App\Filament\Resources\WasteDeposits\Schemas\WasteDepositForm;
use App\Filament\Resources\WasteDeposits\Schemas\WasteDepositInfolist;
use App\Filament\Resources\WasteDeposits\Tables\WasteDepositsTable;
use App\Models\WasteDeposit;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WasteDepositResource extends Resource
{
    protected static ?string $model = WasteDeposit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Truck;

    protected static ?string $navigationLabel = 'Setor Sampah';

    protected static ?string $slug = 'setor-sampah';

    protected static ?string $pluralModelLabel = 'Setor Sampah';

    protected static ?int $navigationSort = 1;

    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';

    public static function form(Schema $schema): Schema
    {
        return WasteDepositForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WasteDepositInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WasteDepositsTable::configure($table);
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
            'index' => ListWasteDeposits::route('/'),
            'create' => CreateWasteDeposit::route('/create'),
            'view' => ViewWasteDeposit::route('/{record}'),
            'edit' => EditWasteDeposit::route('/{record}/edit'),
        ];
    }
}
