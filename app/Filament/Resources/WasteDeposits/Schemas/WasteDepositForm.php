<?php

namespace App\Filament\Resources\WasteDeposits\Schemas;

use App\Models\User;
use App\Models\WasteType;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater\TableColumn;

class WasteDepositForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // --- BAGIAN HEADER (TOTAL) ---
                Grid::make(3)->schema([
                    Select::make('user_id')
                        ->options(User::query()->where('id', '!=', 1)->where('is_active', true)->pluck('name', 'id'))
                        ->label('Pilih Nasabah')
                        ->required()
                        ->searchable()
                        ->native(false),

                    TextInput::make('total_weight')
                        ->label('Total Berat')
                        ->numeric()
                        ->suffix(' Kg')
                        ->default(0)       // Agar tidak null saat awal
                        ->disabled()       // Agar user tidak bisa edit
                        ->dehydrated()     // Agar tetap tersimpan ke DB
                        ->reactive(),      // Agar bisa update otomatis

                    TextInput::make('total_amount')
                        ->label('Total Pendapatan')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0)
                        ->disabled()
                        ->dehydrated()
                        ->reactive(),
                ]),

                // --- BAGIAN REPEATER (ITEM) ---
                Repeater::make('items')
                    ->relationship()
                    ->label('Detail Sampah')
                    ->addActionLabel('Tambah Jenis Sampah')
                    ->columns(4)
                    // Trigger update Total saat baris ditambah/hapus
                    ->live()
                    ->afterStateUpdated(function ($get, $set) {
                        self::calculateGrandTotal($get, $set);
                    })
                    ->table([
                        TableColumn::make('Jenis Sampah'),
                        TableColumn::make('Berat'),
                        TableColumn::make('Harga / Kg'),
                        TableColumn::make('Subtotal'),
                    ])
                    ->schema([
                        // 1. INPUT JENIS SAMPAH
                        Select::make('waste_type_id')
                            ->label('Jenis Sampah')
                            ->required()
                            ->searchable()
                            ->options(WasteType::pluck('name', 'id'))
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                // Ambil harga dari Master
                                $wt = WasteType::find($state);
                                $price = $wt ? (float) $wt->price_per_kg : 0;

                                $set('price_per_kg', $price);

                                // Hitung Baris & Grand Total
                                self::calculateRow($set, $get);
                                self::calculateGrandTotal($get, $set);
                            }),

                        // 2. INPUT BERAT
                        TextInput::make('weight_kg')
                            ->label('Berat (kg)')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->live(onBlur: true) // Hitung saat pindah kolom agar performa terjaga
                            ->afterStateUpdated(function ($state, $set, $get) {
                                // Hitung Baris & Grand Total
                                self::calculateRow($set, $get);
                                self::calculateGrandTotal($get, $set);
                            }),

                        // 3. INPUT HARGA
                        TextInput::make('price_per_kg')
                            ->label('Harga per Kg')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(0)
                            ->readOnly()   // Ubah jadi readOnly jika tidak boleh diedit
                            ->dehydrated() // Wajib agar tersimpan
                            ->required(),

                        // 4. INPUT SUBTOTAL
                        TextInput::make('subtotal')
                            ->label('Pendapatan')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->disabled()   // Disable input manual
                            ->dehydrated() // Wajib agar tersimpan
                            ->required(),
                    ])
                    ->reorderable(false),

                Grid::make(2)->schema([
                    FileUpload::make('receipt')
                            ->label('Bukti Transaksi')
                            ->required()
                            ->image(),
                    Textarea::make('notes')
                        ->label('Catatan'),
                ])
            ])->columns(1);
    }

    /**
     * Fungsi 1: Menghitung Subtotal untuk SATU baris saja
     */
    protected static function calculateRow($set, $get)
    {
        $weight = (float) $get('weight_kg');
        $price = (float) $get('price_per_kg');

        $subtotal = $weight * $price;
        $set('subtotal', $subtotal);
    }

    /**
     * Fungsi 2: Menghitung TOTAL KESELURUHAN (Looping semua baris)
     */
    protected static function calculateGrandTotal($get, $set)
    {
        // Cek context: Apakah kita di dalam baris (child) atau di luar (parent)?
        // Jika di dalam baris, kita perlu naik ke parent ('../../items')
        // Jika di luar, kita ambil langsung ('items')
        $items = $get('items') ?? $get('../../items');

        $totalWeight = 0;
        $totalAmount = 0;

        if (is_array($items)) {
            foreach ($items as $item) {
                // Ambil data mentah dari array state
                $w = (float) ($item['weight_kg'] ?? 0);
                $p = (float) ($item['price_per_kg'] ?? 0);

                // Kita hitung ulang subtotal di sini untuk menjamin akurasi
                // (daripada mengambil field subtotal yang mungkin belum terupdate)
                $rowSubtotal = $w * $p;

                $totalWeight += $w;
                $totalAmount += $rowSubtotal;
            }
        }

        // Set nilai ke Header
        // Gunakan path traversal '../../' jika kita sedang berada di dalam repeater item
        if ($get('items') === null) {
            $set('../../total_weight', $totalWeight);
            $set('../../total_amount', $totalAmount);
        } else {
            $set('total_weight', $totalWeight);
            $set('total_amount', $totalAmount);
        }
    }
}