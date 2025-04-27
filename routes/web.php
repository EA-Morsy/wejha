<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/scanner', function(){
    return view('scanner');
});
Route::get('/checkSerial', function(){
    return response()->json(['message'=>"تم الحجز بنجاح"], 200);
})->name('checkSerial');

Route::get('/database', [HomeController::class, 'database']);
Route::get('/privacy', [HomeController::class, 'privacy']);
Route::get('/language', [HomeController::class, 'language'])->name('language');
Auth::routes();
Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage link created';
});
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return 'Cache cleared';
});
Route::get('/install-permissions', function () {
    Artisan::call('install:permissions');
    return 'Permission updated';
});

