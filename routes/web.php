<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Stunting\AgregatController;
use App\Http\Controllers\Stunting\AnggaranController;
use App\Http\Controllers\Stunting\BnbaController;
use App\Http\Controllers\Kemiskinan\KemiskinanController;
use App\Http\Controllers\Lingkungan\LingkunganController;
use App\Http\Controllers\PublicController;

// ========================
// PUBLIC ROUTES
// ========================
Route::get('/', [PublicController::class, 'index'])->name('home');

// Dashboard & Read-Only Menus
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// PORTAL STUNTING (Reads)
Route::prefix('stunting')->name('stunting.')->group(function () {
    Route::get('/agregat', [AgregatController::class, 'index'])->name('agregat');
    Route::get('/anggaran', [AnggaranController::class, 'index'])->name('anggaran');
    Route::get('/bnba', [BnbaController::class, 'index'])->name('bnba.index');
});

// PORTAL KEMISKINAN (Reads)
Route::prefix('kemiskinan')->name('kemiskinan.')->group(function () {
    Route::get('/', [KemiskinanController::class, 'index'])->name('index');
    Route::get('/desil', [KemiskinanController::class, 'desil'])->name('desil');
    Route::get('/pengangguran', [KemiskinanController::class, 'pengangguran'])->name('pengangguran');
    Route::get('/pkh', [KemiskinanController::class, 'pkh'])->name('pkh');
    Route::get('/sembako', [KemiskinanController::class, 'sembako'])->name('sembako');
    Route::get('/ak1', [KemiskinanController::class, 'ak1'])->name('ak1');
    Route::get('/ojeng', [KemiskinanController::class, 'ojeng'])->name('ojeng');
});

// PORTAL LINGKUNGAN (Reads)
Route::prefix('lingkungan')->name('lingkungan.')->group(function () {
    Route::get('/', [LingkunganController::class, 'index'])->name('index');
    Route::get('/tonton/{lingkungan}', [LingkunganController::class, 'show'])->name('show');
});


// ========================
// AUTH ROUTES (Guest Only)
// ========================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ========================
// PROTECTED ROUTES (Auth Required)
// ========================
Route::middleware('auth')->group(function () {
    
    // PORTAL STUNTING
    Route::prefix('stunting')->name('stunting.')->group(function () {
        Route::post('/agregat/penduduk', [AgregatController::class, 'storePenduduk'])->name('agregat.penduduk.store');
        Route::post('/agregat/stunting', [AgregatController::class, 'storeStunting'])->name('agregat.stunting.store');
        Route::post('/agregat/kek', [AgregatController::class, 'storeKek'])->name('agregat.kek.store');
        Route::post('/anggaran', [AnggaranController::class, 'store'])->name('anggaran.store');
        Route::put('/anggaran/{anggaran}', [AnggaranController::class, 'update'])->name('anggaran.update');
        Route::delete('/anggaran/{anggaran}', [AnggaranController::class, 'destroy'])->name('anggaran.destroy');
        Route::post('/bnba', [BnbaController::class, 'store'])->name('bnba.store');
        Route::put('/bnba/{bnba}', [BnbaController::class, 'update'])->name('bnba.update');
        Route::delete('/bnba/{bnba}', [BnbaController::class, 'destroy'])->name('bnba.destroy');
        Route::get('/bnba/import', [BnbaController::class, 'importForm'])->name('bnba.import');
        Route::post('/bnba/import', [BnbaController::class, 'import'])->name('bnba.import.process');
        Route::get('/bnba/template', [BnbaController::class, 'downloadTemplate'])->name('bnba.template');
    });

    // PORTAL KEMISKINAN
    Route::prefix('kemiskinan')->name('kemiskinan.')->group(function () {
        Route::post('/desil', [KemiskinanController::class, 'storeDesil'])->name('desil.store');
        Route::post('/pengangguran', [KemiskinanController::class, 'storePengangguran'])->name('pengangguran.store');
        Route::post('/pkh', [KemiskinanController::class, 'storePkh'])->name('pkh.store');
        Route::put('/pkh/{pkh}', [KemiskinanController::class, 'updatePkh'])->name('pkh.update');
        Route::post('/sembako', [KemiskinanController::class, 'storeSembako'])->name('sembako.store');
        Route::put('/sembako/{sembako}', [KemiskinanController::class, 'updateSembako'])->name('sembako.update');
        Route::post('/ak1', [KemiskinanController::class, 'storeAk1'])->name('ak1.store');
        Route::post('/ojeng', [KemiskinanController::class, 'storeOjeng'])->name('ojeng.store');
        Route::put('/ojeng/{ojeng}', [KemiskinanController::class, 'updateOjeng'])->name('ojeng.update');
        Route::delete('/ojeng/{ojeng}', [KemiskinanController::class, 'destroyOjeng'])->name('ojeng.destroy');
    });

    // PORTAL LINGKUNGAN
    Route::prefix('lingkungan')->name('lingkungan.')->group(function () {
        Route::get('/create', [LingkunganController::class, 'create'])->name('create');
        Route::post('/', [LingkunganController::class, 'store'])->name('store');
        Route::get('/{lingkungan}/edit', [LingkunganController::class, 'edit'])->name('edit');
        Route::put('/{lingkungan}', [LingkunganController::class, 'update'])->name('update');
        Route::delete('/{lingkungan}', [LingkunganController::class, 'destroy'])->name('destroy');
        Route::delete('/{lingkungan}/foto/{index}', [LingkunganController::class, 'deleteFoto'])->name('foto.delete');
    });
});
