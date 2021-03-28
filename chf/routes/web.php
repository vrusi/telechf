<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Auth;
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
    return view('welcome');
});

Route::get('/dashboard', 'App\Http\Controllers\Patient\DashboardController@index')->middleware(['auth'])->name('dashboard');

Route::resource('/measurements', 'App\Http\Controllers\Patient\MeasurementController')->middleware(['auth']);
Route::get('/measurements/create/{parameterId}', 'App\Http\Controllers\Patient\MeasurementController@measurementForm')->middleware(['auth']);

Route::resource('/contacts', 'App\Http\Controllers\Patient\ContactController')->middleware(['auth']);

Route::get('/profile', 'App\Http\Controllers\Patient\ProfileController@index')->middleware(['auth']);

require __DIR__ . '/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
