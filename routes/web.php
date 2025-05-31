<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


//membuat ujicoba dengan role siswa dapat akses fe
Route::get('/siswa', function () {
    return "Siswa";
})->middleware(['auth', 'verified','role:siswa','cek_user'])
 ->name('siswa');


// membuat peraturan role siswa dapat akses fe
Route::middleware(['auth', 'verified', 'role:siswa ', 'cek_usesrs'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('industri', 'industri')->name('industri');
});

Route::middleware(['auth', 'verified', 'role:siswa', 'cek_user'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('pkl', 'pkl')->name('pkl');
    Route::view('industri', 'industri')->name('industri');
});


// Route::middleware(['auth', 'verified', 'role:guru', 'cek_guru'])->group(function () {
//    Route::get('/guru', function (){
//     return "Guru";
//    })->name('login'); 
// });



Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

//Route::get('/users', [AuthController::class, 'index']);

require __DIR__.'/auth.php';