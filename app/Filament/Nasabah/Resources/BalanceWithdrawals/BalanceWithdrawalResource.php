<?php

namespace App\Filament\Nasabah\Resources\BalanceWithdrawals;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Facades\Filament;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use App\Models\BalanceWithdrawal;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Validation\ValidationException;
use App\Filament\Nasabah\Resources\BalanceWithdrawals\Pages\ManageBalanceWithdrawals;

class BalanceWithdrawalResource extends Resource
{
    protected static ?string $model = BalanceWithdrawal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(fn() => Filament::auth()->id()),

                TextInput::make('amount')
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
                TextEntry::make('amount')
                    ->money('IDR', decimalPlaces: 0, locale: 'id_ID')
                    ->badge()
                    ->size('xxl'),
                TextEntry::make('status')
                    ->badge()
                    ->size('xxl')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'accepted' => 'Selesai',
                        'rejected' => 'Ditolak',
                        default => ucfirst($state),
                    })
                    ->icon(fn(string $state): ?string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'accepted' => 'heroicon-o-check-circle',
                        'rejected' => 'heroicon-o-x-circle',
                        default => null,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                    }),
                TextEntry::make('created_at')
                    ->dateTime(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('amount')
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
                        default => ucfirst($state),
                    })
                    ->icon(fn(string $state): ?string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'accepted' => 'heroicon-o-check-circle',
                        'rejected' => 'heroicon-o-x-circle',
                        default => null,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
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
