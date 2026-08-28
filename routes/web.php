<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ProductController;

Route::resource('products', ProductController::class);

Route::get('/', function () {
    return 'Rota 1 Çalışıyor.';
});

Route::get('/rota2', [TestController::class, 'showBlade']);
Route::get('/rota3', [TestController::class, 'showJson']);
Route::post('/test-post', function () {
    return response()->json(['mesaj' => 'Post isteği başarılı!']);
});
