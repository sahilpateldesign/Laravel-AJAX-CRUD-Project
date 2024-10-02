<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IntricaretechController;

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

Route::get('/',[IntricaretechController::class,'index'])->name('index');
Route::post('updatestore',[IntricaretechController::class,'updatestore'])->name('updatestore');
Route::get('edit/{id}',[IntricaretechController::class,'edit'])->name('edit');
Route::delete('destroy/{id}',[IntricaretechController::class,'destroy'])->name('destroy');
