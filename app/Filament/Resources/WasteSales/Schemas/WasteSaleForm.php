<?php

namespace App\Filament\Resources\WasteSales\Schemas;

use Closure;
use App\Models\WasteType;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater\TableColumn;

class WasteSaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Repeater::make('items')
                    ->label('Detail Sampah')
                    ->relationship() // Pastikan ada relationship jika ini relasi database
                    ->addActionLabel('Tambah Baris')
                    ->live() // Agar repeater reaktif saat tambah/hapus baris
                    ->afterStateUpdated(function ($get, $set) {
                        // Trigger kalkulasi saat baris ditambah/dihapus
                        self::updateGrandTotals($get, $set);
                    })
                    ->table([
                        TableColumn::make('Jenis Sampah'),
                        TableColumn::make('Berat'),
                        TableColumn::make('Harga / Kg'),
                        TableColumn::make('Subtotal'),
                    ])
                    ->reorderable(false)
                    ->schema([
                        Select::make('waste_type_id')
                            ->label('Jenis Sampah')
                            ->required()
                            ->options(WasteType::pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                // 1. Ambil Harga dari Master Data
                                $wasteType = WasteType::find($state);
                                $price = $wasteType ? (float) $wasteType->price_per_kg : 0;
                                $set('price_per_kg', $price);

                                // 2. Hitung Ulang Baris Ini
                                self::updateRowSubtotal($set, $get);

                                // 3. Hitung Ulang Total Header
                                self::updateGrandTotals($get, $set);
                            }),

                        TextInput::make('weight_kg')
                            ->label('Berat Sampah (kg)')
                            ->suffix('Kg')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->live(onBlur: true) // Update saat pindah kolom
                            ->afterStateUpdated(function ($state, $set, $get) {
                                // 1. Hitung Ulang Baris Ini
                                self::updateRowSubtotal($set, $get);

                                // 2. Hitung Ulang Total Header
                                self::updateGrandTotals($get, $set);
                            }),

                        TextInput::make('price_per_kg')
                            ->label('Harga / Kg')
                            ->prefix('Rp')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->dehydrated() // Tetap dikirim ke DB
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set, $get) {
                                // Logic sama: Hitung ulang saat harga diubah manual
                                self::updateRowSubtotal($set, $get);
                                self::updateGrandTotals($get, $set);
                            }),

                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(0)
                            ->disabled()   // Disable input
                            ->dehydrated() // PENTING: Agar nilai hasil hitungan masuk ke DB
                    ])
                    ->columns(4),

                Grid::make(3)->schema([
                    TextInput::make('total_weight')
                        ->label('Total Berat')
                        ->suffix('Kg')
                        ->numeric()
                        ->default(0)
                        ->disabled()
                        ->dehydrated() // Solusi "Disable tapi Not Null"
                        ->reactive(),

                    TextInput::make('total_income') // Saya sesuaikan nama variabel header Anda
                        ->label('Total Pendapatan')
                        ->prefix('Rp')
                        ->numeric()
                        ->default(0)
                        ->disabled()
                        ->dehydrated() // Solusi "Disable tapi Not Null"
                        ->reactive(),

                    // Jika ini field user relasi, sebaiknya Select. Jika text biasa, ok.
                    TextInput::make('buyer')
                        ->label('Pembeli')
                        ->required(),
                ]),

                Grid::make(2)->schema([
                    FileUpload::make('receipt')
                            ->label('Bukti Transaksi')
                            ->image(),
                    Textarea::make('notes')
                        ->label('Catatan'),
                ])
            ])->columns(1);
    }

    // --- FUNGSI BANTUAN KALKULASI ---

    // 1. Menghitung Subtotal PER BARIS (Row Level)
    protected static function updateRowSubtotal($set, $get)
    {
        // Ambil nilai dari baris yang sedang aktif
        $weight = (float) $get('weight_kg');
        $price = (float) $get('price_per_kg');

        // Set Subtotal di baris tersebut
        $set('subtotal', $weight * $price);
    }

    // 2. Menghitung Total KESELURUHAN (Header Level)
    protected static function updateGrandTotals($get, $set)
    {
        // PENTING: Kita perlu mengambil array items.
        // Jika dipanggil dari dalam baris, kita gunakan '../../items' (naik 2 level)
        // Jika dipanggil dari repeater (add/delete), kita gunakan 'items' (level sama)

        // Kita coba ambil dari context repeater dulu (saat add/delete)
        $items = $get('items');

        // Jika null (artinya fungsi dipanggil dari dalam row), ambil parent
        if ($items === null) {
            $items = $get('../../items');
        }

        $totalWeight = 0;
        $totalIncome = 0;

        if (is_array($items)) {
            foreach ($items as $item) {
                // Pastikan key array sesuai dengan nama field di schema (weight_kg, subtotal)
                $weight = (float) ($item['weight_kg'] ?? 0);
                $subtotal = (float) ($item['subtotal'] ?? 0);

                // Jika subtotal belum terhitung di array (kasus edge case), hitung manual
                if ($subtotal == 0 && isset($item['price_per_kg'])) {
                    $subtotal = $weight * (float) $item['price_per_kg'];
                }

                $totalWeight += $weight;
                $totalIncome += $subtotal;
            }
        }

        // Set ke field Header. 
        // Logic path: Jika kita di dalam row, kita harus set ke '../../total_weight'
        // Namun fungsi $set di Filament v3 seringkali pintar mencari path absolut jika nama field unik.
        // Untuk aman, kita cek context.

        // Cara paling aman di Filament Form:
        // Coba set langsung (biasanya berhasil jika field ada di root schema)
        // Jika gagal, gunakan path traversal.

        // Kita asumsikan field total_weight ada di root form.
        // Teknik Filament v3 untuk akses root dari kedalaman apapun:

        // Trik: Gunakan path relatif jika $items diambil dari parent
        if ($get('items') === null) {
            // Berarti kita sedang di dalam child (row), perlu naik ke parent
            $set('../../total_weight', $totalWeight);
            $set('../../total_income', $totalIncome);
        } else {
            // Berarti kita sedang di root (repeater event)
            $set('total_weight', $totalWeight);
            $set('total_income', $totalIncome);
        }
    }
}