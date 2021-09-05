<?php

use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\CheckUserType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

require __DIR__.'/auth.php';

// Route::get('/admin/categories', 'Admin\CategoriesController@index')
//     ->name('categories.index');
// Route::get('/admin/categories/create', [CategoriesController::class, 'create'])
//     ->name('categories.create');
// Route::post('/admin/categories', [CategoriesController::class, 'store'])
//     ->name('categories.store');
// Route::get('/admin/categories/{id}', [CategoriesController::class, 'show'])
//     ->name('categories.show');
// Route::get('/admin/categories/{id}/edit', [CategoriesController::class, 'edit'])
//     ->name('categories.edit');
// Route::put('/admin/categories/{id}', [CategoriesController::class, 'update'])
//     ->name('categories.update');
// Route::delete('/admin/categories/{id}', [CategoriesController::class, 'destroy'])
//     ->name('categories.destroy');

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ],
],function (){

    Route::namespace('Admin')->prefix('admin/')->middleware(['auth','user.type'])
            ->group(function () {

                Route::resource('categories', 'CategoriesController');

                Route::get('categories/trash', [CategoriesController::class, 'trash'])
                    ->name('categories.trash');
                Route::put('categories/trash/{id?}', [CategoriesController::class, 'restore'])
                    ->name('categories.restore');
                Route::delete('categories/trash/{id?}', [CategoriesController::class, 'forceDelete'])
                    ->name('categories.force-delete');

                Route::resource('products', 'ProductsController');

                Route::get('products/trash', [ProductsController::class, 'trash'])
                    ->name('products.trash');
                Route::put('products/trash/{id?}', [ProductsController::class, 'restore'])
                    ->name('products.restore');
                Route::delete('products/trash/{id?}', [ProductsController::class, 'forceDelete'])
                    ->name('products.force-delete');

                Route::resource('roles', 'RoleController');
    });


});
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ],
    'namespace' => 'admin'
    ],function (){

    require __DIR__.'/auth.php';


});
