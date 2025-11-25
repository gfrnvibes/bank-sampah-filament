<?php

namespace App\Filament\Nasabah\Resources\BalanceWithdrawals;

use UnitEnum;
use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Facades\Filament;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use App\Models\BalanceWithdrawal;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\Filter;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Form;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Validation\ValidationException;
use App\Filament\Nasabah\Resources\BalanceWithdrawals\Pages\ManageBalanceWithdrawals;

class BalanceWithdrawalResource extends Resource
{
    protected static ?string $model = BalanceWithdrawal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Banknotes;
    protected static ?string $navigationLabel = 'Penarikan Saldo';
    protected static ?string $slug = 'penarikan-saldo';
    protected static ?string $pluralModelLabel = 'Penarikan Saldo';
    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        // Count pending balance withdrawals for the authenticated user
        return static::getModel()::where('user_id', auth()->id())->where('status', 'pending')->count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(fn() => Filament::auth()->id()),

                TextInput::make('amount')
                    ->label('Jumlah Penarikan')
                    ->numeric()
                    ->required()
                    ->minValue(1) // angka harus > 0
                    ->rule(function () {
                        return function (string $attribute, $value, $fail) {
                            $user = Filament::auth()->user();

                            if (!$user) {
                                $fail('User tidak ditemukan.');
                                return;
                            }

                            if ($value > $user->balance) {
                                $fail('Jumlah penarikan melebihi saldo yang kamu punya.');
                            }
                        };
                    }),

                Hidden::make('status')
                    ->default('pending'),
            ])->columns(1);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('created_at')
                    ->label('Dibuat pada')
                    ->dateTime(),
                TextEntry::make('amount')
                    ->label('Jumlah Penarikan')
                    ->money('IDR', decimalPlaces: 0, locale: 'id_ID')
                    ->badge()
                    ->size('xxl'),
                TextEntry::make('status')
                    ->badge()
                    ->size('xxl')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'accepted' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'completed' => 'Selesai',
                        default => ucfirst($state),
                    })
                    ->icon(fn(string $state): ?string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'accepted' => 'heroicon-o-information-circle',
                        'rejected' => 'heroicon-o-x-circle',
                        'completed' => 'heroicon-o-check-circle',
                        default => null,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'accepted' => 'primary',
                        'rejected' => 'danger',
                        'completed' => 'success',
                    }),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn($query) =>
                $query->where('user_id', auth()->id())
            )
            ->defaultSort('created_at', 'desc')
            ->heading('Riwayat Penarikan Saldo')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal & Waktu')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Jumlah Penarikan')
                    ->money('IDR', decimalPlaces: 0, locale: 'id_ID')
                    ->badge()
                    ->size('xxl')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->size('xxl')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'accepted' => 'Selesai',
                        'rejected' => 'Ditolak',
                        'completed' => 'Selesai',
                        default => ucfirst($state),
                    })
                    ->icon(fn(string $state): ?string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'accepted' => 'heroicon-o-check-circle',
                        'rejected' => 'heroicon-o-x-circle',
                        'completed' => 'heroicon-o-check-circle',
                        default => null,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'accepted' => 'primary',
                        'rejected' => 'danger',
                        'completed' => 'success',
                    }),
            ])
            ->filters([
                Filter::make('advanced')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'accepted' => 'Accepted',
                                'rejected' => 'Rejected',
                            ])->native(false),
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
                            )
                            ->when(
                                $data['status'] ?? null,
                                fn(Builder $query, $status): Builder => $query->where('status', $status),
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat Detail')
                    ->modalWidth('xl')
                    ->modalHeading('Detail Penarikan Saldo'),
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
