<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Livewire\Panduan;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;


// Route::get('/', function () {
//     return view('livewire/home');
// });

Route::get('/', \App\Livewire\Home::class);
Route::post('/logout', function () {
    auth()->logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');

Route::get('price-list', \App\Livewire\PriceList::class)->name('price-list');
Route::get('panduan', Panduan::class)->name('panduan');

Route::get('admin-login', \App\Filament\Pages\Auth\AdminLogin::class)->name('admin.login');
Route::get('nasabah-login', \App\Filament\Nasabah\Pages\Auth\NasabahLogin::class)->name('nasabah.login');
Route::get('nasabah-register', \App\Filament\Nasabah\Pages\Auth\NasabahRegister::class)->name('nasabah.register');

Route::get('filament/nasabah/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->intended('/nasabah');
})->middleware('signed')->name('filament.nasabah.auth.email-verification.verify');


