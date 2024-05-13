<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScraperController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('scraper/scrape', [ScraperController::class, 'scraper'])->name('scraper.scrape');
    Route::post('scraper/update', [ScraperController::class, 'update'])->name('scraper.update');
    Route::delete('scraper/{id}', [ScraperController::class, 'delete'])->name('scraper.delete');
    Route::get('scraper/view', [ScraperController::class, 'showData'])->name('scraper.view');

});

require __DIR__.'/auth.php';
