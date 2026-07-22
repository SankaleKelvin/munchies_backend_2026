<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ResendVerificationController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Public Routes
Route::post('register-user', [AuthController::class, 'register']);    
Route::post('login-user', [AuthController::class, 'login']);
Route::get('get-roles', [RoleController::class, 'readRoles']);

//Email Verification
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
              ->name('verification.verify')
              ->middleware(['signed', 'throttle:6,1']);
//Resend Verification
Route::post('/email/resend', [ResendVerificationController::class, 'resend'])
              ->middleware('throttle:6,1');


//Protected Routes
Route::middleware('auth:sanctum')->group(function(){

       //Authenticated User
Route::get('user-info', [AuthController::class, 'userInfo']);
Route::post('logout-user', [AuthController::class, 'logout']);

        //Roles
Route::post('save-role', [RoleController::class, 'createRole']);

Route::get('get-role/{id}', [RoleController::class, 'readRole']);
Route::post('update-role/{id}', [RoleController::class, 'updateRole']);
Route::delete('delete-role/{id}', [RoleController::class, 'deleteRole']);

        //Categories
Route::post('save-category', [CategoryController::class, 'createCategory']);
Route::get('get-categories', [CategoryController::class, 'readCategories']);
Route::get('get-category/{id}', [CategoryController::class, 'readCategory']);
Route::post('update-category/{id}', [CategoryController::class, 'updateCategory']);
Route::delete('delete-category/{id}', [CategoryController::class, 'deleteCategory']);

       //Restaurants
Route::post('save-restaurant', [RestaurantController::class, 'createRestaurant']);
Route::get('get-restaurants', [RestaurantController::class, 'readRestaurants']);
Route::get('get-restaurant/{id}', [RestaurantController::class, 'readRestaurant']);
Route::post('update-restaurant/{id}', [RestaurantController::class, 'updateRestaurant']);
Route::delete('delete-restaurant/{id}', [RestaurantController::class, 'deleteRestaurant']);

       //Menu
Route::post('save-menu', [MenuController::class, 'createMenu']);
Route::get('get-menus', [MenuController::class, 'readMenus']);
Route::get('get-menu/{id}', [MenuController::class, 'readMenu']);
Route::post('update-menu/{id}', [MenuController::class, 'updateMenu']);
Route::delete('delete-menu/{id}', [MenuController::class, 'deleteMenu']);

});