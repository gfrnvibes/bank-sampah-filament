<?php

namespace App\Filament\Resources\BalanceWithdrawals;

use UnitEnum;
use BackedEnum;
use App\Models\User;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use App\Models\BalanceWithdrawal;
use Filament\Actions\ActionGroup;
use App\Models\TransactionHistory;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Radio;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Widgets\LatestBalanceWithdrawals;
use App\Filament\Resources\BalanceWithdrawals\Pages\ManageBalanceWithdrawals;
use App\Filament\Resources\BalanceWithdrawals\Widgets\LatestBalanceWithdrawal;

class BalanceWithdrawalResource extends Resource
{
    protected static ?string $model = BalanceWithdrawal::class;
    protected static ?int $navigationSort = 3;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Banknotes;
    protected static ?string $navigationLabel = 'Penarikan Saldo';
    protected static ?string $slug = 'penarikan-saldo';
    protected static ?string $pluralModelLabel = 'Penarikan Saldo';
    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->orWhere('status', 'accepted')->count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->options(User::query()->where('id', '!=', 1)->where('is_active', true)->pluck('name', 'id'))
                    ->label('Pilih Nasabah')
                    ->required()
                    ->searchable()
                    ->native(false),
                TextInput::make('amount')
                    ->label('Jumlah Penarikan')
                    ->prefix('Rp')
                    ->placeholder('56500')
                    ->required()
                    ->numeric()
                    ->rules([
                        function ($get) {
                            return function (string $attribute, $value, $fail) use ($get) {
                                $userId = $get('user_id');
                                if ($userId) {
                                    $user = User::find($userId);
                                    if ($user && $user->balance < $value) {
                                        $fail('Saldo nasabah tidak mencukupi');
                                    }
                                    // nilai negatif
                                    if ($value <= 1000) {
                                        $fail('Jumlah penarikan harus lebih dari Rp 1.000');
                                    }
                                }
                            };
                        }
                    ]),
                Radio::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'accepted' => 'Disetujui',
                        // 'rejected' => 'Ditolak',
                        'completed' => 'Selesai'
                    ])
                    ->descriptions([
                        'pending' => 'Menunggu persetujuan',
                        'accepted' => 'Tunai belum diberikan kepada nasabah',
                        // 'rejected' => 'Pengajuan ditolak',
                        'completed' => 'Tunai telah diberikan kepada nasabah',
                    ])
                    ->default('completed')
                    ->required()
                    ->live(),
                FileUpload::make('receipt')
                    ->label('Bukti Setoran')
                    ->image()
                    ->visible(fn($get) => $get('status') === 'completed')
                    ->required(fn($get) => $get('status') === 'completed'),
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
                ImageEntry::make('receipt')
                    ->label('Bukti Penarikan'),
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
                    ->dateTime('d/m/y, h:i')
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
                        'accepted' => 'heroicon-o-check-circle',
                        'rejected' => 'heroicon-o-x-circle',
                        'completed' => 'heroicon-s-check-badge',
                        default => null,
                    }),
                ImageColumn::make('receipt')
                    ->label('Bukti Transaksi')
                    ->toggleable(),
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
                                'pending' => 'Menunggu',
                                'accepted' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                'completed' => 'Selesai',
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
                            )
                            ->when(
                                $data['payment_status'] ?? null,
                                fn(Builder $query, $payment): Builder => $query->where('payment_status', $payment),
                            );
                    }),
            ])
            ->filtersFormColumns(1)
            ->deferFilters(false)

            ->recordActions([

                ActionGroup::make([
                    // DeleteAction::make()
                    //     ->action(function (BalanceWithdrawal $record) {
                    //         $record->hidden_by_admin = true;
                    //     }),
                    Action::make('accept')
                        ->label('Accept')
                        ->requiresConfirmation()
                        ->action(function (BalanceWithdrawal $record) {
                            $record->status = 'accepted';
                            $record->save();
                        })
                        ->icon('heroicon-o-check-circle')
                        ->color('primary')
                        ->visible(fn(BalanceWithdrawal $record): bool => $record->status === 'pending'),
                    Action::make('completed')
                        ->label('Complete')
                        ->requiresConfirmation()
                        ->action(function (BalanceWithdrawal $record) {
                            $record->status = 'completed';

                            $user = $record->user;
                            $balanceBefore = $user->balance;
                            $user->balance -= $record->amount;
                            $user->save(); // INI WAJIB
                
                            // Buat entri TransactionHistory
                            TransactionHistory::create([
                                'user_id' => $user->id,
                                'type' => 'withdrawal',
                                'amount' => $record->amount,
                                'balance_before' => $balanceBefore,
                                'balance_after' => $user->balance,
                                'reference_type' => BalanceWithdrawal::class,
                                'reference_id' => $record->id,
                            ]);
                
                            $record->save();
                        })
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn(BalanceWithdrawal $record): bool => $record->status === 'accepted'),

                    Action::make('reject')
                        ->label('Reject')
                        ->requiresConfirmation()
                        ->action(function (BalanceWithdrawal $record) {
                            $record->status = 'rejected';
                            $record->save();
                        })
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn(BalanceWithdrawal $record): bool => $record->status === 'pending' || $record->status === 'accepted'),

                    ViewAction::make()
                        ->label('Lihat Detail')
                        ->modalWidth('md')
                        ->modalHeading('Detail Penarikan Saldo'),

                ]),

                // EditAction::make(),
                // DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),

                    // BulkAction::make('accept')
                    //     ->label('Accept')
                    //     ->requiresConfirmation()
                    //     ->action(function ($records) {
                    //         foreach ($records as $record) {
                    //             $record->update(['status' => 'accepted']);
                    //         }
                    //     })
                    //     ->icon('heroicon-o-information-circle')
                    //     ->color('primary')
                    //     ->visible(
                    //         fn($records) =>
                    //         $records->every(fn($r) => $r->status === 'pending')
                    //     ),

                    // BulkAction::make('completed')
                    //     ->label('Complete')
                    //     ->requiresConfirmation()
                    //     ->action(function ($records) {
                    //         foreach ($records as $record) {
                    //             $record->update(['status' => 'completed']);

                    //             $user = $record->user;
                    //             $balanceBefore = $user->balance;
                    //             $user->balance -= $record->amount;
                    //             $user->save();

                    //             TransactionHistory::create([
                    //                 'user_id' => $user->id,
                    //                 'type' => 'withdrawal',
                    //                 'amount' => $record->amount,
                    //                 'balance_before' => $balanceBefore,
                    //                 'balance_after' => $user->balance,
                    //                 // 'reference_type' => BalanceWithdrawal::class,
                    //                 'reference_id' => $record->id,
                    //             ]);
                    //         }
                    //     })
                    //     ->icon('heroicon-o-check-circle')
                    //     ->color('success')
                    //     ->visible(
                    //         fn($records) =>
                    //         $records->every(fn($r) => $r->status === 'accepted')
                    //     ),

                    // BulkAction::make('reject')
                    //     ->label('Reject')
                    //     ->requiresConfirmation()
                    //     ->action(function ($records) {
                    //         foreach ($records as $record) {
                    //             $record->update(['status' => 'rejected']);
                    //         }
                    //     })
                    //     ->icon('heroicon-o-x-circle')
                    //     ->color('danger')
                    //     ->visible(
                    //         fn($records) =>
                    //         $records->every(fn($r) => $r->status === 'pending')
                    //     ),
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