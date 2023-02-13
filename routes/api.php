<?php

use App\Http\Controllers\category\addCategoryController;
use App\Http\Controllers\category\descriptionCategoryController;
use App\Http\Controllers\category\editCategoryController;
use App\Http\Controllers\category\listCategoryController;
use App\Http\Controllers\order\addOrderController;
use App\Http\Controllers\order\descriptionOrderController;
use App\Http\Controllers\order\editOrderController;
use App\Http\Controllers\order\listOrderController;
use App\Http\Controllers\orderDetail\listOrderDetailController;
use App\Http\Controllers\product\addProductController;
use App\Http\Controllers\product\descriptionProductController;
use App\Http\Controllers\product\editProductController;
use App\Http\Controllers\product\listProductController;
use App\Http\Controllers\user\descriptionUserController;
use App\Http\Controllers\user\addUserController;
use App\Http\Controllers\user\editUserController;
use App\Http\Controllers\user\listUserController;
use App\Http\Controllers\user\loginUserController;
use App\Http\Controllers\user\refreshTokenController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(
    function () {
        Route::post('register', [loginUserController::class, 'register'])->name('registerUserPost');
        Route::post('login', [loginUserController::class, 'login'])->name('loginUserPost');
        Route::post('refreshToken', [refreshTokenController::class, 'refreshToken'])->middleware('auth.jwtRefreshToken')->name('refreshTokenPost');
        Route::get('currentUser', [descriptionUserController::class, 'currentUser'])->middleware('auth.jwt')->name('currentUserGet');
    }
);

Route::middleware(['auth.jwt', 'auth.isAdmin'])->group(
    function () {

        Route::prefix('user')->group(
            function () {
                    Route::get('list', [listUserController::class, 'index'])->name('listUserGet');
                    Route::post('add', [addUserController::class, 'add'])->name('addUserPost');
                    Route::put('edit', [editUserController::class, 'edit'])->name('editUserPut');
                    Route::get('description', [descriptionUserController::class, 'description'])->name('descriptionUserGet');
                }
        );

        Route::prefix('category')->group(
            function () {
                    Route::get('list', [listCategoryController::class, 'index'])->name('listCategoryGet');
                    Route::post('add', [addCategoryController::class, 'add'])->name('addCategoryPost');
                    Route::put('edit', [editCategoryController::class, 'edit'])->name('editCategoryPut');
                    Route::get('description', [descriptionCategoryController::class, 'description'])->name('descriptionCategoryGet');
                }
        );

        Route::prefix('product')->group(
            function () {
                    Route::get('list', [listProductController::class, 'index'])->name('listProductGet');
                    Route::post('add', [addProductController::class, 'add'])->name('addProductPost');
                    Route::put('edit', [editProductController::class, 'edit'])->name('editProductPut');
                    Route::get('description', [descriptionProductController::class, 'description'])->name('descriptionProductGet');
                }
        );

        Route::prefix('order')->group(
            function () {
                    Route::get('list', [listOrderController::class, 'index'])->name('listOrdertGet');
                    Route::put('edit', [editOrderController::class, 'edit'])->name('editOrderPut');
                    Route::get('description', [descriptionOrderController::class, 'description'])->name('descriptionOrderGet');
                }
        );

        Route::prefix('orderDetail')->group(
            function () {
                    Route::get('list', [listOrderDetailController::class, 'index'])->name('listOrderDetailGet');
                }
        );
    }
);



Route::prefix('client')->group(
    function () {
        Route::prefix('category')->group(
            function () {
                    Route::get('list', [listCategoryController::class, 'index'])->name('listCategoryClientGet');
                }
        );
        Route::prefix('product')->group(
            function () {
                    Route::get('list', [listProductController::class, 'index'])->name('listProductClientGet');
                    Route::get('description', [descriptionProductController::class, 'description'])->name('descriptionProductClientGet');
                }
        );
        Route::middleware(['auth.jwt'])->prefix('order')->group(
            function () {
                    Route::post('addDraft', [addOrderController::class, 'addDraft'])->name('addDraftOrderClientPost');
                    Route::put('verify', [editOrderController::class, 'verify'])->name('verifyOrderClientPut');
                    Route::put('cancel', [editOrderController::class, 'cancel'])->name('cancelOrderClientPut');
                    Route::get('list', [listOrderController::class, 'myList'])->name('listOrderClientGet');
                }
        );
    }
);