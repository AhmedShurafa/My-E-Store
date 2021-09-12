<?php

use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessagesController;
use App\Http\Middleware\CheckUserType;
use App\Models\Order;
use Illuminate\Mail\Events\MessageSent;
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
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ],
    'namespace' => 'admin'
],function (){

    require __DIR__.'/auth.php';

    Route::get('/', [HomeController::class, 'index'])->name('home');


});

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

        Route::get('dashboard',function (){
            $title = 'Dashboard';
            return view('admin.index',compact('title'));
        })->name('Dashboard');

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

        Route::get('chat',[MessagesController::class,'index'])->name('chat');
        Route::post('chat',[MessagesController::class,'store'])->name('chat.store');

        // Notififcation
        Route::get('notifications',[NotificationController::class,'index'])->name('notification');
        Route::get('notification/{id}',[NotificationController::class,'show'])->name('notification.read');
    });
});

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ],
],function (){

    Route::get('product/{slug}','ProductsController@show')->name('product.show');

    Route::post('cart/store','CartController@store')->name('cart');
    Route::get('cart','CartController@index')->name('cart.show');


    Route::get('cart/delete/{item}','CartController@delete')->name('cart.delete');

    Route::get('cart/clear','CartController@clear')->name('cart.clear');


    Route::get('checkout',[CheckoutController::class , 'create'])->name('checkout');

    Route::post('checkout',[CheckoutController::class , 'store'])->name('checkout.store');;

    // Route::get('orders',[CheckoutController::class , 'create'])->name('orders');



    Route::get('/orders', function () {
        return Order::all();
    })->name('orders');
});

//Route::fallback(function (){
//    return view('home');
//});

