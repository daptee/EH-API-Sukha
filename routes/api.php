<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Order Controller
Route::post('order', [OrderController::class, 'store']);
Route::post('payment', [PaymentController::class, 'store']);
Route::post('product/images', [ProductController::class, 'store']);
Route::get('product/images/{product_id}', [ProductController::class, 'product_images']);
Route::get('product/images_principal', [ProductController::class, 'product_images_principal']);

// Clear cache
Route::get('/clear-cache', function() {
    Artisan::call('config:clear');
    Artisan::call('optimize');

    return response()->json([
        "message" => "Cache cleared successfully"
    ]);
});