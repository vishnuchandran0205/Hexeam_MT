<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SubadminController;

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
    return view('auth.register');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //administrator
Route::middleware(['AdminMiddleWare'])->group(function () {
    Route::get('/administrator_dash', [AdminController::class, 'administrator_dash']);
    Route::get('/add_product_form', [AdminController::class, 'add_product_form']);
    Route::post('/save_products', [AdminController::class, 'save_products']);
    Route::get('/view_products', [AdminController::class, 'view_products']);
    Route::get('/product_edit_form{id}', [AdminController::class, 'product_edit_form']);
    Route::post('/update_products', [AdminController::class, 'update_products']);
    Route::get('/import_products', [AdminController::class, 'import_products']);
    Route::post('/bulk_delete_products',[AdminController::class, 'bulkDelete']);
    Route::get('/search_products',[AdminController::class, 'searchProducts']);

});
    


  //sub admin route
Route::middleware(['SubAdminMiddleWare'])->group(function () {
    Route::get('/sub_admin', [SubadminController::class, 'sub_admin']);
    Route::get('/add_product_form_sub', [SubadminController::class, 'add_product_form']);
    Route::post('/save_products_sub', [SubadminController::class, 'save_products']);
    Route::get('/view_products_sub', [SubadminController::class, 'view_products']);
    Route::get('/product_edit_form_sub{id}', [SubadminController::class, 'product_edit_form']);
    Route::post('/product_edit_sub', [SubadminController::class, 'update_products']);
    Route::get('/import_products_sub', [SubadminController::class, 'import_products']);
    Route::post('/bulk_delete_products_sub',[SubadminController::class, 'bulkDelete']);
    Route::get('/search_products_sub',[SubadminController::class, 'searchProducts']);
});
    




});

require __DIR__.'/auth.php';

