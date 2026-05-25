<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\EbolaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Command Center Dashboard & Outbreak Simulator
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Global Search
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    // Digital Archive Tiers Directory
    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
    
    // GitBook style plan viewer
    Route::get('/plans/{type}/{id}/{sectionId?}', [PlanController::class, 'show'])->name('plans.show');
    
    // CRUD on plan sections (chapters)
    Route::get('/plans/{type}/{id}/sections/create', [PlanController::class, 'createSection'])->name('plans.sections.create');
    Route::post('/plans/{type}/{id}/sections', [PlanController::class, 'storeSection'])->name('plans.sections.store');
    
    Route::get('/sections/{sectionId}/edit', [PlanController::class, 'editSection'])->name('plans.sections.edit');
    Route::patch('/sections/{sectionId}', [PlanController::class, 'updateSection'])->name('plans.sections.update');
    Route::delete('/sections/{sectionId}', [PlanController::class, 'destroySection'])->name('plans.sections.destroy');

    // Ebola Daily Bulletin & Monitoring
    Route::get('/ebola', [EbolaController::class, 'index'])->name('ebola.index');
    Route::get('/ebola/create', [EbolaController::class, 'create'])->name('ebola.create');
    Route::post('/ebola', [EbolaController::class, 'store'])->name('ebola.store');
    Route::get('/ebola/{id}/edit', [EbolaController::class, 'edit'])->name('ebola.edit');
    Route::patch('/ebola/{id}', [EbolaController::class, 'update'])->name('ebola.update');
    Route::delete('/ebola/{id}', [EbolaController::class, 'destroy'])->name('ebola.destroy');
    Route::get('/ebola/bulletin/download', [EbolaController::class, 'downloadBulletinCsv'])->name('ebola.bulletin.download');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
