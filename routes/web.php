<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Auth;
use app\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------|
| Web Routes                                                               |
|--------------------------------------------------------------------------|
| Here is where you can register web routes for your application. These    |
| routes are loaded by the RouteServiceProvider and all of them will be    |
| assigned to the "web" middleware group. Make something great!             |
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    } else {
        return redirect()->route('login');
    }
});
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('home', [HomeController::class, 'index'])->name('home');
Route::get('profile', [ProfileController::class, 'index'])->name('profile');

Auth::routes();

// Menambahkan Local Disk
Route::get('/local-disk', function() {
    Storage::disk('local')->put('local-example.txt', 'This is local example content');
    return asset('storage/local-example.txt');
});

// Meletakan file pada public disk
Route::get('/public-disk', function() {
    Storage::disk('public')->put('public-example.txt', 'This is public example content');
    return asset('storage/public-example.txt');
});

// Menghapus File pada storage
Route::get('/delete-local-file', function() {
    Storage::disk('local')->delete('local-example.txt');
    return 'Deleted';
});

Route::get('/delete-public-file', function() {
    Storage::disk('public')->delete('public-example.txt');
    return 'Deleted';
});

// Menampilkan halaman form Forgot Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');

// Menangani pengiriman link reset password
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

// Group Routes for Employee (Auth required)
Route::middleware(['auth'])->group(function() {
    Route::resource('employees', EmployeeController::class);
    Route::get('download-file/{employeeId}', [EmployeeController::class, 'downloadFile'])->name('employees.downloadFile');
    Route::get('getEmployees', [EmployeeController::class, 'getData'])->name('employees.getData');
    Route::get('exportExcel', [EmployeeController::class, 'exportExcel'])->name('employees.exportExcel');
    Route::get('exportPdf', [EmployeeController::class, 'exportPdf'])->name('employees.exportPdf');
    });
