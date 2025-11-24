<?php

namespace App\Filament\Nasabah\Resources\TransactionHistories;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Nasabah\Resources\TransactionHistories\Pages\ManageTransactionHistories;
use App\Models\TransactionHistory;
use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class TransactionHistoryResource extends Resource
{
    protected static ?string $model = TransactionHistory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Clock;
    protected static ?string $navigationLabel = 'Riwayat Transaksi';
    protected static ?string $slug = 'riwayat-transaksi';
    protected static ?string $pluralModelLabel = 'Riwayat Transaksi';
    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Select::make('type')
                    ->options(['deposit' => 'Deposit', 'withdrawal' => 'Withdrawal'])
                    ->required(),
                TextInput::make('reference_id')
                    ->numeric(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('balance_before')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('balance_after')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('type'),
                TextEntry::make('reference_id')
                    ->numeric(),
                TextEntry::make('amount')
                    ->numeric(),
                TextEntry::make('balance_before')
                    ->numeric(),
                TextEntry::make('balance_after')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn($query) =>
                $query->where('user_id', auth()->id())
            )
            ->heading('Riwayat Penyetoran dan Penarikan Saldo')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal & Waktu')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                // TextColumn::make('user.name')
                //     ->label('Nasabah')
                //     ->searchable()
                //     ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        TransactionHistory::TYPE_DEPOSIT => 'success',
                        TransactionHistory::TYPE_WITHDRAWAL => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        TransactionHistory::TYPE_DEPOSIT => 'Penyetoran',
                        TransactionHistory::TYPE_WITHDRAWAL => 'Penarikan',
                    })
                    ->sortable(),
                // TextColumn::make('reference_id')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR', locale: 'id_ID', decimalPlaces: 0)
                    ->prefix(fn($record) => $record->type === TransactionHistory::TYPE_DEPOSIT ? '+ ' : '- ')
                    ->color(fn($record) => match ($record->type) {
                        TransactionHistory::TYPE_DEPOSIT => 'success',
                        TransactionHistory::TYPE_WITHDRAWAL => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('balance_before')
                    ->label('Saldo Sebelum')
                    ->money('IDR', locale: 'id_ID', decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('balance_after')
                    ->label('Saldo Setelah')
                    ->money('IDR', locale: 'id_ID', decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(['deposit' => 'Penyetoran', 'withdrawal' => 'Penarikan'])
                    ->label('Tipe')
                    ->native(false),
                Filter::make('advanced')
                    ->schema([
                        DatePicker::make('created_from')->label('Created from'),
                        DatePicker::make('created_until')->label('Created until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),

            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export'),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->modalWidth('md'),
                    // EditAction::make(),
                    // DeleteAction::make(),
                ]),
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
            'index' => ManageTransactionHistories::route('/'),
        ];
    }
}
