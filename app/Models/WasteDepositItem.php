<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WasteDepositItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['waste_deposit_id', 'waste_type_id', 'weight_kg', 'price_per_kg', 'subtotal'];

    // Relasi balik ke Header
    public function deposit()
    {
        return $this->belongsTo(WasteDeposit::class);
    }

    // Relasi ke Master Sampah (Untuk ambil nama)
    public function wasteType()
    {
        return $this->belongsTo(WasteType::class)->withTrashed(); // withTrashed agar jika master dihapus, nama tetap muncul
    }
}
