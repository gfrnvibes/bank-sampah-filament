<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Bank Sampah Desa Tanggulun')]
class Home extends Component
{
    public function render()
    {
        return view('livewire.home');
    }
}
