<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ProductController;

Route::get('/products-data', [ProductController::class, 'data'])->name('products.data'); //DataTable sayfa ilk açıldığında arka planda ayrı bir istek atıp veriyi kendisi çekecek.index() ile uğraşmıyoruz.
Route::resource('products', ProductController::class); //create edit formları dahil 7 fonk. içn route oluşturur.

Route::get('/', function () {
    return 'Rota 1 Çalışıyor.';
});

Route::get('/rota2', [TestController::class, 'showBlade']);
Route::get('/rota3', [TestController::class, 'showJson']);
Route::post('/test-post', function () {
    return response()->json(['mesaj' => 'Post isteği başarılı!']);
});
