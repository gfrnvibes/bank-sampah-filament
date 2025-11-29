<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Panduan Bank Sampah')]
class Panduan extends Component
{
    public function render()
    {
        return view('livewire.panduan');
    }
}
