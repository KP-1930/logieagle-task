<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('home');
});

Auth::routes();



Route::middleware(['web', 'CheckAuthUser'])->group(function () {
    // booking routes ==== start ====
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/bookings', [BookingController::class, 'bookingList'])->name('booking.list');
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
    Route::post('/booking/available-rooms', [BookingController::class, 'availableRooms'])->name('booking.availableRooms');
    Route::post('/booking/calculateCost', [BookingController::class, 'calculateCost'])->name('booking.calculateCost');
    // booking routes ==== end ====

    // Room routes
    Route::resource('rooms', RoomController::class);
});
