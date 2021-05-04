<?php

use App\Http\Controllers\Coordinator\PatientController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect('/login');
    } else {

        if ($user->is_coordinator) {
            return redirect('/coordinator/dashboard');
        } else {
            return redirect('/dashboard');
        }
    }
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
Route::get('/profile/create', 'App\Http\Controllers\Patient\ProfileController@create')->middleware(['auth', 'patient']);
Route::post('/profile', 'App\Http\Controllers\Patient\ProfileController@store')->middleware(['auth', 'patient']);
Route::get('/therapy', 'App\Http\Controllers\Patient\ProfileController@therapy')->middleware(['auth', 'patient'])->name('therapy');

Route::get('/charts', 'App\Http\Controllers\Patient\ChartController@index')->middleware(['auth', 'patient'])->name('charts');
Route::post('/charts/filter', 'App\Http\Controllers\Patient\ChartController@filter')->middleware(['auth', 'patient']);
Route::post('/charts', 'App\Http\Controllers\Patient\ChartController@selectDate')->middleware(['auth', 'patient']);

require __DIR__ . '/auth.php';

Auth::routes();
Route::get('/register', function() {
    return redirect('/login');
});
/*
|--------------------------------------------------------------------------
| Coordinator Routes
|--------------------------------------------------------------------------
*/

Route::get('/coordinator', function () {
    return redirect('/coordinator/dashboard');
})->middleware(['auth','coordinator']);

Route::get('coordinator/dashboard', 'App\Http\Controllers\Coordinator\DashboardController@index')->middleware(['auth', 'coordinator'])->name('coordinator.dashboard');

Route::resource('coordinator/patients', 'App\Http\Controllers\Coordinator\PatientController')->middleware(['auth', 'coordinator']);
Route::resource('coordinator/patients/{patient}/measurements/notes', 'App\Http\Controllers\Coordinator\NotesController')->middleware(['auth', 'coordinator']);

Route::get('coordinator/patients/{patient}/measurements', 'App\Http\Controllers\Coordinator\MeasurementController@index')->middleware(['auth', 'coordinator'])->name('coordinator.patients.measurements');
Route::post('coordinator/patients/{patient}/measurements/check', 'App\Http\Controllers\Coordinator\MeasurementController@checkDayAlarms')->middleware(['auth', 'coordinator']);

Route::get('coordinator/patients/{patient}/charts', 'App\Http\Controllers\Coordinator\ChartController@index')->middleware(['auth', 'coordinator'])->name('coordinator.patients.charts');
Route::post('coordinator/patients/{patient}/charts/filter', 'App\Http\Controllers\Coordinator\ChartController@filter')->middleware(['auth', 'coordinator']);
Route::post('coordinator/patients/{patient}/charts', 'App\Http\Controllers\Coordinator\ChartController@selectDate')->middleware(['auth', 'coordinator'])->name('coordinator.patients.charts');

Route::get('coordinator/patients/{patient}/profile', 'App\Http\Controllers\Coordinator\ProfileController@index')->middleware(['auth', 'coordinator'])->name('coordinator.patients.profile');
Route::get('coordinator/patients/{patient}/profile/edit', 'App\Http\Controllers\Coordinator\ProfileController@create')->middleware(['auth', 'coordinator']);
Route::post('coordinator/patients/{patient}/profile/edit', 'App\Http\Controllers\Coordinator\ProfileController@update')->middleware(['auth', 'coordinator'])->name('coordinator.patients.profile.update');

Route::get('coordinator/patients/{patient}/therapy', 'App\Http\Controllers\Coordinator\ProfileController@therapy')->middleware(['auth', 'coordinator'])->name('coordinator.patients.therapy');
Route::get('coordinator/patients/{patient}/contacts', 'App\Http\Controllers\Coordinator\ContactController@index')->middleware(['auth', 'coordinator'])->name('coordinator.patients.contact');
Route::get('coordinator/patients/{patient}/contacts/create', 'App\Http\Controllers\Coordinator\ContactController@create')->middleware(['auth', 'coordinator']);
Route::post('coordinator/patients/contacts', 'App\Http\Controllers\Coordinator\ContactController@store')->middleware(['auth', 'coordinator'])->name('contacts.store');

Route::get('coordinator/thresholds', 'App\Http\Controllers\Coordinator\ThresholdController@index')->middleware(['auth', 'coordinator'])->name('coordinator.thresholds');
Route::get('coordinator/thresholds/create', 'App\Http\Controllers\Coordinator\ThresholdController@create')->middleware(['auth', 'coordinator'])->name('coordinator.thresholds.create');
Route::post('coordinator/thresholds/store', 'App\Http\Controllers\Coordinator\ThresholdController@store')->middleware(['auth', 'coordinator']);

Route::get('coordinator/patients/{patient}/therapy/thresholds/create', 'App\Http\Controllers\Coordinator\PatientThresholdController@create')->middleware(['auth', 'coordinator']);
Route::post('coordinator/patients/{patient}/therapy/thresholds/store', 'App\Http\Controllers\Coordinator\PatientThresholdController@store')->middleware(['auth', 'coordinator']);

Route::get('coordinator/patients/{patient}/deactivate', 'App\Http\Controllers\Coordinator\PatientController@deactivate')->middleware(['auth', 'coordinator']);
Route::get('coordinator/patients/{patient}/restore', 'App\Http\Controllers\Coordinator\PatientController@restore')->middleware(['auth', 'coordinator']);

/*
|--------------------------------------------------------------------------
| ECG Path
|--------------------------------------------------------------------------
*/

Route::post('patients/ecgData', 'App\Http\Controllers\Coordinator\PatientController@storeEcg');
Route::post('patients/{patient}/ecgData', 'App\Http\Controllers\Coordinator\PatientController@storeEcgWithQuery');

