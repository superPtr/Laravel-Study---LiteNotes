<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TrashedNoteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::resource('/notes', NoteController::class)->middleware(['auth']);

// Route::get('/trashed', [TrashedNoteController::class, 'index'])->middleware('auth')->name('trashed.index');

// Route::get('/trashed/{note}', [TrashedNoteController::class, 'show'])
//     ->withTrashed()
//     ->middleware('auth')->name('trashed.show');

// Route::put('/trashed/{note}', [TrashedNoteController::class, 'update'])
//     ->withTrashed()
//     ->middleware('auth')->name('trashed.update');


// Route::delete('/trashed/{note}', [TrashedNoteController::class, 'destroy'])
//     ->withTrashed()
//     ->middleware('auth')->name('trashed.destory');

Route::prefix('/trashed')->name('trashed.')->middleware('auth')->group(function(){
    Route::get('/', [TrashedNoteController::class, 'index'])->name('index');
    Route::get('/{note}', [TrashedNoteController::class, 'show'])->name('show')->withTrashed();
    Route::put('/{note}', [TrashedNoteController::class, 'update'])->name('update')->withTrashed();
    Route::delete('/{note}', [TrashedNoteController::class, 'destroy'])->name('destroy')->withTrashed();
});

require __DIR__.'/auth.php';
