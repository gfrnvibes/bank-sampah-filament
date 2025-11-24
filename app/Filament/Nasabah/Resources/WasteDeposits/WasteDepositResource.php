<?php

namespace App\Filament\Nasabah\Resources\WasteDeposits;

use App\Filament\Nasabah\Resources\WasteDeposits\Pages\CreateWasteDeposit;
use App\Filament\Nasabah\Resources\WasteDeposits\Pages\EditWasteDeposit;
use App\Filament\Nasabah\Resources\WasteDeposits\Pages\ListWasteDeposits;
use App\Filament\Nasabah\Resources\WasteDeposits\Pages\ViewWasteDeposit;
use App\Filament\Nasabah\Resources\WasteDeposits\Schemas\WasteDepositForm;
use App\Filament\Nasabah\Resources\WasteDeposits\Schemas\WasteDepositInfolist;
use App\Filament\Nasabah\Resources\WasteDeposits\Tables\WasteDepositsTable;
use App\Models\WasteDeposit;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class WasteDepositResource extends Resource
{
    protected static ?string $model = WasteDeposit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Truck;
    protected static ?string $navigationLabel = 'Penyetoran Sampah';
    protected static ?string $slug = 'penyetoran-sampah';
    protected static ?string $pluralModelLabel = 'Penyetoran Sampah';
    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 1;

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

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(?Model $record): bool
    {
        return false;
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
