<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\NewsletterController;
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

// Form contact
Route::post('form/contact', [FormController::class, 'form_contact']);

// Categories images
Route::post('category/image', [CategoryController::class, 'store']);
Route::get('category/image/{cod_category}', [CategoryController::class, 'category_images']);

// Newsletter
Route::post('newsletter/register/email', [NewsletterController::class, 'newsletter_register_email']);

// Clear cache
Route::get('/clear-cache', function() {
    Artisan::call('config:clear');
    Artisan::call('optimize');

    return response()->json([
        "message" => "Cache cleared successfully"
    ]);
});