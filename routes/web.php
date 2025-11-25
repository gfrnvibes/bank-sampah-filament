<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('livewire/home');
// });

Route::get('/', \App\Livewire\Home::class);
Route::post('/logout', function () {
    auth()->logout();

    // Biar session bener-bener di-reset
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/'); // arahkan kemana pun kamu mau
})->name('logout');

