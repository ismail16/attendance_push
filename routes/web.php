<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\FingerController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Device Routes
    Route::get('/device-information', [DeviceController::class, 'index'])->name('device.index');
    Route::get('device/attendance', [DeviceController::class, 'attendance'])->name('device.attendance');
    Route::get('device/clear-attendance', [DeviceController::class, 'clear_attendance'])->name('device.clear_attendance');
    Route::get('device/{device_id}/restart', [DeviceController::class, 'restart'])->name('device.restart');
    Route::get('device/{device_id}/shutdown', [DeviceController::class, 'shutdown'])->name('device.shutdown');
    Route::post('/device/set-ip', [DeviceController::class, 'set_device_ip'])->name('device.set_ip');
    Route::get('device/{device_id}/test-sound', [DeviceController::class, 'test_sound'])->name('device.test_sound');
    Route::resource('devices', DeviceController::class);

    Route::get('/export-attendances', [DeviceController::class, 'export'])->name('attendances.export');
    Route::get('/export-users', [UserController::class, 'export'])->name('users.export');


    Route::get('device/export-attendance', [DeviceController::class, 'export_attendance'])->name('export.export_attendance');

    Route::get('/attendance/search', [DeviceController::class, 'searchAttendance'])->name('search.attendance');


    Route::get('/export-search-attendances', [DeviceController::class, 'exportAttendances'])->name('attendances.exportsearch');

    Route::get('/sync', [DeviceController::class, 'syncFromDevice'])->name('sync');

    //User Routes
    Route::get('users/import-user-index', [UserController::class, 'importUsersIndex'])->name('users.import-user-index');
    Route::delete('user/all-delete', [UserController::class, 'allDelete'])->name('user.all-delete');
    Route::delete('/users/{uid}/{device_id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::resource('/users', UserController::class)->except('destroy');
    Route::get('users/single-user/[user]', [UserController::class, 'show_single'])->name('users.show-single');

    //Settings
    Route::get('/settings', [DeviceController::class, 'settings_index'])->name('settings.index');

    //Data
    Route::resource('attendance', DataController::class);
    //get server data
    Route::get('get-data-from-another-server', [DataController::class, 'getDataFromAnotherServer'])->name('get-data-from-another-server');

    //Organization
    Route::get('organization', [OrganizationController::class, 'edit'])->name('organization.edit');
    Route::post('organization/update', [OrganizationController::class, 'update'])->name('organization.update');


    //fingerprint
    Route::get('fingerprint', [FingerController::class, 'index'])->name('fingerprint.index');
    Route::post('export-fingerprint-to-server', [FingerController::class, 'exportFNGToServer'])->name('fingerprint.store');
    Route::post('import-fingerprint-to-device', [FingerController::class, 'importFNGToDevice'])->name('fingerprint.import-to-device');
});

require __DIR__ . '/auth.php';
