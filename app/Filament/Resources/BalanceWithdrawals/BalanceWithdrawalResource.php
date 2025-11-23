<?php

namespace App\Filament\Resources\BalanceWithdrawals;

use App\Filament\Resources\BalanceWithdrawals\Pages\ManageBalanceWithdrawals;
use App\Models\BalanceWithdrawal;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class BalanceWithdrawalResource extends Resource
{
    protected static ?string $model = BalanceWithdrawal::class;
    protected static ?int $navigationSort = 2;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::CurrencyDollar;
    protected static ?string $navigationLabel = 'Penarikan Saldo';
    protected static ?string $slug = 'penarikan-saldo';
    protected static ?string $pluralModelLabel = 'Penarikan Saldo';
    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->options(User::query()->where('id', '!=', 1)->pluck('name', 'id'))
                    ->label('Pilih Nasabah')
                    ->required()
                    ->searchable()
                    ->native(false),
                TextInput::make('amount')
                    ->label('Jumlah Penarikan')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'completed' => 'Completed'
                    ])
                    ->default('pending')
                    ->native(false)
                    ->required(),
            ])->columns(1);
    }

    
    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('Nasabah')
                    ->numeric(),
                TextEntry::make('amount')
                    ->label('Jumlah Penarikan')
                    ->badge()
                    ->size('xl')
                    ->color('primary')
                    ->money('IDR', decimalPlaces: 0, locale: 'id_ID'),
                TextEntry::make('status')
                    ->badge()
                    ->size('xl')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'accepted' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'completed' => 'Selesai',
                        default => ucfirst($state),
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'accepted' => 'primary',
                        'rejected' => 'danger',
                        'completed' => 'success'
                    })
                    ->icon(fn(string $state): ?string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'accepted' => 'heroicon-o-information-circle',
                        'rejected' => 'heroicon-o-x-circle',
                        'completed' => 'heroicon-o-check-circle',
                        default => null,
                    }),
                TextEntry::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime(),
                // TextEntry::make('updated_at')
                //     ->label('Diperbarui Pada')
                //     ->dateTime(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Nasabah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Jumlah Penarikan')
                    ->money('IDR', decimalPlaces: 0, locale: 'id_ID')
                    ->badge()
                    ->size('xl')
                    ->color('success')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->size('xl')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'accepted' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'completed' => 'Selesai',
                        default => ucfirst($state),
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'accepted' => 'primary',
                        'rejected' => 'danger',
                        'completed' => 'success',
                    })
                    ->icon(fn(string $state): ?string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'accepted' => 'heroicon-o-information-circle',
                        'rejected' => 'heroicon-o-x-circle',
                        'completed' => 'heroicon-o-check-circle',
                        default => null,
                    }),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                    Filter::make('advanced')
                        ->schema([
                            Select::make('status')
                                ->options([
                                    'pending' => 'Pending',
                                    'accepted' => 'Accepted',
                                    'rejected' => 'Rejected',
                                    'completed' => 'Complete',
                                ])->native(false),
                            DatePicker::make('created_from')->label('Created from'),
                            DatePicker::make('created_until')->label('Created until'),
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $query
                                ->when(
                                    $data['created_from'] ?? null,
                                    fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                                )
                                ->when(
                                    $data['created_until'] ?? null,
                                    fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                                )
                                ->when(
                                    $data['status'] ?? null,
                                    fn (Builder $query, $status): Builder => $query->where('status', $status),
                                )
                                ->when(
                                    $data['payment_status'] ?? null,
                                    fn (Builder $query, $payment): Builder => $query->where('payment_status', $payment),
                                );
                        }),
                ])
                ->filtersFormColumns(1) 
                ->deferFilters(false)
            
            ->recordActions([

                ActionGroup::make([
                    Action::make('accept')
                        ->label('Accept')
                        ->requiresConfirmation()
                        ->action(function (BalanceWithdrawal $record) {
                            $record->status = 'accepted';                
                            $record->save();
                        })
                        ->icon('heroicon-o-information-circle')
                        ->color('primary')
                        ->visible(fn (BalanceWithdrawal $record): bool => $record->status === 'pending'),
                    Action::make('completed')
                        ->label('Complete')
                        ->requiresConfirmation()
                        ->action(function (BalanceWithdrawal $record) {
                            $record->status = 'completed';

                            $user = $record->user;
                            $user->balance -= $record->amount;
                            $user->save(); // INI WAJIB
                
                            $record->save();
                        })
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (BalanceWithdrawal $record): bool => $record->status === 'accepted'),
                    
                    Action::make('reject')
                        ->label('Reject')
                        ->requiresConfirmation()
                        ->action(function (BalanceWithdrawal $record) {
                            $record->status = 'rejected';
                            $record->save();
                        })
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (BalanceWithdrawal $record): bool => $record->status === 'pending' || $record->status === 'accepted' ),

                        ViewAction::make(),

                ]),

                // EditAction::make(),
                // DeleteAction::make(),
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
            'index' => ManageBalanceWithdrawals::route('/'),
        ];
    }
}
