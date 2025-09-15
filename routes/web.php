<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Business\EnterpriseController;
use App\Http\Controllers\EMR\ExamsController;
use App\Http\Controllers\EMR\HistoriesController;
use App\Http\Controllers\EMR\ReportsController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Maintenance\DiagnosticsController;
use App\Http\Controllers\Maintenance\DrugsController;
use App\Http\Controllers\Maintenance\OccupationsController;
use App\Http\Controllers\Security\PermissionsController;
use App\Http\Controllers\Security\UsersController;
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

Route::middleware(['prevent.cache'])->group(function(){
    Route::get('/login',    [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
    Route::post('/login',   [AuthController::class, 'login'])->middleware('guest');
    Route::post('/logout',  [AuthController::class, 'logout'])->name('logout')->middleware('auth');
});

Route::middleware(['auth', 'prevent.back'])->group(function(){
    Route::get('/sys/home',                         [HomeController::class, 'index'])->name('home');
    // Dashboard
    Route::get('/sys/dashboard',                    [ReportsController::class, 'index'])->name('dashboard');
    // Historias
    Route::get('/sys/histories',                    [HistoriesController::class, 'index'])->name('emr.histories.home');
    Route::get('/sys/histories/new',                [HistoriesController::class, 'new'])->name('emr.histories.new');
    Route::get('/sys/histories/edit/{history}',     [HistoriesController::class, 'edit'])->name('emr.histories.edit');
    // Exámenes 
    Route::get('/sys/exams',                        [ExamsController::class, 'index'])->name('emr.exams.home');
    Route::get('/sys/exams/new',                    [ExamsController::class, 'new'])->name('emr.exams.new');
    Route::get('/sys/exams/edit/{exam}',            [ExamsController::class, 'edit'])->name('emr.exams.edit');
    Route::get('/sys/exams/see/{exam}',             [ExamsController::class, 'see'])->name('emr.exams.see');
    Route::post('/sys/exams',                       [ExamsController::class, 'store']);
    Route::delete('/sys/exams/{exam}',              [ExamsController::class, 'destroy']);
    // Fármacos 
    Route::get('/sys/drugs',                        [DrugsController::class, 'index'])->name('maintenance.drugs');
    Route::post('/sys/drugs',                       [DrugsController::class, 'store']);
    Route::get('/sys/drugs/{drug}',                 [DrugsController::class, 'show']);
    Route::delete('/sys/drugs/{drug}',              [DrugsController::class, 'destroy']);
    Route::get('/sys/drugs/search',                 [DrugsController::class, 'search']);
    // Diagnosticos
    Route::get('/sys/diagnostics',                  [DiagnosticsController::class, 'index'])->name('maintenance.diagnostics');
    Route::post('/sys/diagnostics',                 [DiagnosticsController::class, 'store']);
    Route::get('/sys/diagnostics/{diagnosis}',      [DiagnosticsController::class, 'show']);
    Route::delete('/sys/diagnostics/{diagnosis}',   [DiagnosticsController::class, 'destroy']);
    Route::get('/sys/diagnostics/search',           [DiagnosticsController::class, 'search']);
    // Ocupaciones
    Route::get('/sys/occupations',                  [OccupationsController::class, 'index'])->name('maintenance.occupations');
    Route::post('/sys/occupations',                 [OccupationsController::class, 'store']);
    Route::get('/sys/occupations/{occupation}',     [OccupationsController::class, 'show']);
    Route::delete('/sys/occupations/{occupation}',  [OccupationsController::class, 'destroy']);
    Route::get('/sys/occupations/search',           [OccupationsController::class, 'search']);
    // Empresa
    Route::get('/sys/enterprise',                   [EnterpriseController::class, 'index'])->name('business.enterprise');
    // Permisos
    Route::get('/sys/permissions',                  [PermissionsController::class, 'index'])->name('security.permissions');
    // Usuarios
    Route::get('/sys/users',                        [UsersController::class, 'index'])->name('security.users.home');
});