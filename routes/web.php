<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminPanelController;
use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\CheckAdminPermission;
use App\Http\Middleware\CheckSuperadminPermission;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(CheckAuth::class)->group(function () {
    // Authentication
    Route::prefix('/auth')->group(function () {
        Route::get('/', function () {
            return redirect()->route('loginGet');
        });

        Route::get('/login', [AuthController::class, 'loginGet'])->name('loginGet');
        Route::post('/login', [AuthController::class, 'loginPost'])->name('loginPost');
        
        Route::get('/signup', [AuthController::class, 'signupGet'])->name('signupGet');
        Route::post('/signup', [AuthController::class, 'signupPost'])->name('signupPost');
    });

    // Admin Panel
    Route::middleware(CheckAdminPermission::class)->group(function () {
        Route::prefix('/admin')->group(function () {
            Route::get('/', function () {
                return redirect()->route('adminDashboard');
            });

            Route::get('/dashboard', [AdminPanelController::class, 'dashboard'])->name('adminDashboard');

            Route::get('/users', [AdminPanelController::class, 'showAllUsersGet'])->name('adminShowAllUsersGet');
            Route::get('/user/{id}', [AdminPanelController::class, 'updateUserGet'])->name('adminUpdateUserGet')->middleware(CheckSuperadminPermission::class);
            Route::put('/user/{id}', [AdminPanelController::class, 'updateUserPut'])->name('adminUpdateUserPut')->middleware(CheckSuperadminPermission::class);
            Route::get('/user/{id}/delete', [AdminPanelController::class, 'deleteUserDelete'])->name('adminDeleteUserDelete')->middleware(CheckSuperadminPermission::class);

            Route::get('/category', [AdminPanelController::class, 'showAllCategoriesGet'])->name('adminShowAllCategoriesGet');
            Route::post('/category', [AdminPanelController::class, 'addCategoryPost'])->name('adminAddCategoryPost');
            Route::get('/category/{category_id}/delete', [AdminPanelController::class, 'deleteCategoryDelete'])->name('adminDeleteCategoryDelete');

            Route::get('/products', [AdminPanelController::class, 'showAllProductsGet'])->name('adminShowAllProductsGet');
            Route::get('/products/add', [AdminPanelController::class, 'addProductGet'])->name('adminAddProductGet');
            Route::post('/products/add', [AdminPanelController::class, 'addProductPost'])->name('adminAddProductPost');
            Route::get('/product/{product_id}/update', [AdminPanelController::class, 'updateProductGet'])->name('adminUpdateProductGet');
            Route::put('/product/{product_id}/update', [AdminPanelController::class, 'updateProductPut'])->name('adminUpdateProductPut');
            Route::get('/product/{product_id}/delete', [AdminPanelController::class, 'deleteProductDelete'])->name('adminDeleteProductDelete');

            Route::get('/storage', [AdminPanelController::class, 'showAllStorageGet'])->name('adminShowAllStorageGet');
            Route::put('/storage', [AdminPanelController::class, 'updateStoragePut'])->name('adminUpdateStoragePut');
        });
    });
    
});
    

Route::get('/test', function () {
    return "ishlaydi";
})->name('main')->middleware(CheckAdminPermission::class);