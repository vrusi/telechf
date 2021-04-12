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

Route::get('/charts', 'App\Http\Controllers\Patient\ChartController@index')->middleware(['auth', 'patient'])->name('charts');



require __DIR__ . '/auth.php';

Auth::routes();


/*
|--------------------------------------------------------------------------
| Coordinator Routes
|--------------------------------------------------------------------------
*/

Route::get('coordinator/dashboard', 'App\Http\Controllers\Coordinator\DashboardController@index')->middleware(['auth', 'coordinator'])->name('coordinator.dashboard');

Route::resource('coordinator/patients', 'App\Http\Controllers\Coordinator\PatientController')->middleware(['auth', 'coordinator']);

Route::get('coordinator/patients/{patient}/measurements', 'App\Http\Controllers\Coordinator\MeasurementController@index')->middleware(['auth', 'coordinator'])->name('coordinator.patients.measurements');
Route::post('coordinator/patients/{patient}/measurements/check', 'App\Http\Controllers\Coordinator\Measurementcontroller@checkDayAlarms')->middleware(['auth', 'coordinator']);

Route::get('coordinator/patients/{patient}/charts', 'App\Http\Controllers\Coordinator\ChartController@index')->middleware(['auth', 'coordinator'])->name('coordinator.patients.charts');
Route::post('coordinator/patients/{patient}/charts/filter', 'App\Http\Controllers\Coordinator\ChartController@filter')->middleware(['auth', 'coordinator']);

Route::get('coordinator/patients/{patient}/profile', 'App\Http\Controllers\Coordinator\ProfileController@index')->middleware(['auth', 'coordinator'])->name('coordinator.patients.profile');
Route::get('coordinator/patients/{patient}/therapy', 'App\Http\Controllers\Coordinator\ProfileController@therapy')->middleware(['auth', 'coordinator'])->name('coordinator.patients.therapy');
Route::get('coordinator/patients/{patient}/contacts', 'App\Http\Controllers\Coordinator\ContactController@index')->middleware(['auth', 'coordinator'])->name('coordinator.patients.contact');
