<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AllImageController;

Route::get('/', function () {
    return view('welcome');
});
// all image
Route::get('/image-crud', [AllImageController::class, 'index'])->name('image.index');
Route::post('/image-crud', [AllImageController::class, 'store'])->name('image.store');
Route::delete('/image-crud/{id}', [AllImageController::class, 'destroy'])->name('image.destroy');
