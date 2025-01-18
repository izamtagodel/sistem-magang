<?php

use App\Http\Controllers\AkunDosenController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DataMagangController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\SidangController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    Route::get('/data-magang', [DataMagangController::class, 'index'])->name('data-magang');
    Route::post('/data-magang', [DataMagangController::class, 'store'])->name('data-magang.post');
    Route::get('/data-magang/{id}/{name}', [DataMagangController::class, 'show'])->name('data-magang.detail-mahasiswa');

    Route::patch('/data-tempat-magang/{id}/update', [DataMagangController::class, 'update_tempat_magang'])->name('update-tempat-magang');
    Route::patch('/data-penjadwalan/{id}/update', [SidangController::class, 'update_penjadwalan'])->name('update-penjadwalan');
    Route::put('/data-nilai/{id}/update', [NilaiController::class, 'update_nilai'])->name('update_nilai');


    Route::resource('data-akun-dosen', AkunDosenController::class)->middleware('adminOnly');


    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');

    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});
