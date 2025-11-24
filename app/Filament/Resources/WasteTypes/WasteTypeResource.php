<?php

namespace App\Filament\Resources\WasteTypes;

use App\Filament\Resources\WasteTypes\Pages\ManageWasteTypes;
use App\Models\WasteType;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class WasteTypeResource extends Resource
{
    protected static ?string $model = WasteType::class;

    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrash;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Trash;
    protected static ?string $slug = 'jenis-sampah';
    protected static ?string $navigationLabel = 'Jenis Sampah';
    protected static ?string $pluralModelLabel = 'Jenis Sampah';
    protected static string|UnitEnum|null $navigationGroup = 'Menu';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Jenis Sampah')
                    ->required(),
                TextInput::make('price_per_kg')
                    ->label('Harga per Kg')
                    ->prefix('Rp')
                    ->required()
                    ->numeric(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('price_per_kg')
                    ->money('IDR', decimalPlaces: 0, locale: 'id_ID')
                    ->badge()
                    ->size('xl')
                    ->color('success')
                    ->sortable(),
                TextColumn::make('description'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                        ->modalWidth('md')
                        ->modalSubmitActionLabel('Simpan')
                        ->modalHeading('Edit Jenis Sampah'),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageWasteTypes::route('/'),
        ];
    }
}
