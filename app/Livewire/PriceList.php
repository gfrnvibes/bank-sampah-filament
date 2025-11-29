<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\WasteType;
use Livewire\Attributes\Title;

#[Title('Daftar Harga Bank Sampah')]

class PriceList extends Component
{
    public function render()
    {
        return view('livewire.price-list', [
            'items' => WasteType::orderBy('name')->get()
        ]);
    }
}
