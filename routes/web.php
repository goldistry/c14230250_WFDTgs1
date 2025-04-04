<?php

use App\Http\Controllers\ResourceController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;


Route::get('/', [ResourceController::class, 'homepage'])->name('homepage');
Route::get('/addDrama', [ResourceController::class, 'addDrama'])->name('drama.add');
Route::post('/saveNewDrama', [ResourceController::class, 'saveNewDrama'])->name('drama.add.save');
Route::get('/drama/detail/{id}', [ResourceController::class, 'dramaDetail'])->name('drama.detail');
Route::get('/drama/edit/{id}', [ResourceController::class, 'editDrama'])->name('drama.edit');
Route::put('/drama/edit/save/{id}', [ResourceController::class, 'update'])->name('drama.edit.save');
Route::get('/', [ResourceController::class, 'homepage'])->name('homepage');
Route::delete('/drama/delete/{id}', [ResourceController::class, 'destroy'])->name('drama.delete');
Route::get('/subscriptions/platform', [SubscriptionController::class, 'showSubs'])->name('platform.subscription');
