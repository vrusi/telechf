<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Patient Routes
|--------------------------------------------------------------------------
*/


Route::get('/dashboard', 'App\Http\Controllers\Patient\DashboardController@index')->middleware(['auth', 'patient'])->name('dashboard');

Route::resource('/measurements', 'App\Http\Controllers\Patient\MeasurementController')->middleware(['auth', 'patient']);
Route::get('/measurements/create/{parameterId}', 'App\Http\Controllers\Patient\MeasurementController@measurementForm')->middleware(['auth', 'patient']);

Route::resource('/contacts', 'App\Http\Controllers\Patient\ContactController')->middleware(['auth', 'patient']);

Route::get('/profile', 'App\Http\Controllers\Patient\ProfileController@index')->middleware(['auth', 'patient'])->name('profile');
Route::get('/therapy', 'App\Http\Controllers\Patient\ProfileController@therapy')->middleware(['auth', 'patient'])->name('therapy');

Route::get('/charts', 'App\Http\Controllers\Patient\ChartsController@index')->middleware(['auth', 'patient'])->name('charts');



require __DIR__ . '/auth.php';

Auth::routes();


/*
|--------------------------------------------------------------------------
| Coordinator Routes
|--------------------------------------------------------------------------
*/

Route::get('coordinator/dashboard', 'App\Http\Controllers\Coordinator\DashboardController@index')->middleware(['auth', 'coordinator'])->name('coordinator.dashboard');