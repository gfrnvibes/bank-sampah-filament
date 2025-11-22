<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('livewire/home');
// });

Route::get('/', \App\Livewire\Home::class);

