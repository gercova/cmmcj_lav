<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Business\EnterpriseController;
use App\Http\Controllers\EMR\AppointmentsController;
use App\Http\Controllers\EMR\ExamsController;
use App\Http\Controllers\EMR\HistoriesController;
use App\Http\Controllers\EMR\ReportsController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Maintenance\DiagnosticsController;
use App\Http\Controllers\Maintenance\DrugsController;
use App\Http\Controllers\Maintenance\OccupationsController;
use App\Http\Controllers\Security\PermissionsController;
use App\Http\Controllers\Security\SpecialtiesController;
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
    Route::get('/sys/dashboard/hcCount',            [ReportsController::class, 'getCountRows']);
    Route::get('/sys/dashboard/hcByMonth',          [ReportsController::class, 'HCByMonth']);
    Route::get('/sys/dashboard/annual/{year}',      [ReportsController::class, 'getAnnualData']);
    Route::get('/sys/dashboard/dxByExam',           [ReportsController::class, 'getDiagnosticsByExam']);
    Route::get('/sys/dashboard/mxByExam',           [ReportsController::class, 'getDrugsByExam']);
    //Route::get('/sys/dashboard/hCBySex',          [ReportsController::class, 'getHistoriesBySex']);
    Route::get('/sys/dashboard/hcByMS',             [ReportsController::class, 'getHistoriesByMaritalStatus']);
    Route::get('/sys/dashboard/hcByBG',             [ReportsController::class, 'getHistoriesByBloodingGroup']);
    Route::get('/sys/dashboard/hcByDI',             [ReportsController::class, 'getHistoriesByDegreeIntruction']);
    Route::get('/sys/dashboard/hcByMAC',            [ReportsController::class, 'getHistoriesByMAC']);
    
    // Historias
    Route::get('/sys/histories',                    [HistoriesController::class, 'index'])->name('emr.histories.home');
    Route::get('/sys/histories/new',                [HistoriesController::class, 'new'])->name('emr.histories.new');
    Route::get('/sys/histories/edit/{history}',     [HistoriesController::class, 'edit'])->name('emr.histories.edit');
    Route::post('/sys/histories/store',             [HistoriesController::class, 'store']);
    Route::post('/sys/histories/list',              [HistoriesController::class, 'list']);
    Route::post('/sys/histories/dni',               [HistoriesController::class, 'searchDni']);
    Route::post('/sys/histories/location',          [HistoriesController::class, 'searchLocation']);
    Route::post('/sys/histories/occupation',        [HistoriesController::class, 'searchOccupation']);
    Route::delete('/sys/histories/{history}',       [HistoriesController::class, 'destroy']);
    // Exámenes 
    Route::get('/sys/exams',                        [ExamsController::class, 'index'])->name('emr.exams.home');
    Route::get('/sys/exams/new/{history}',          [ExamsController::class, 'new'])->name('emr.exams.new');
    Route::get('/sys/exams/edit/{exam}',            [ExamsController::class, 'edit'])->name('emr.exams.edit');
    Route::get('/sys/exams/see/{history}',          [ExamsController::class, 'see'])->name('emr.exams.see');
    Route::get('/sys/exams/view/{exam}',            [ExamsController::class, 'view']);
    Route::post('/sys/exams/store',                 [ExamsController::class, 'store']);
    Route::get('/sys/exams/list/{history}',         [ExamsController::class, 'listExams']);
    Route::get('/sys/exams/list-dx/{exam}',         [ExamsController::class, 'listDiagnostics']);
    Route::get('/sys/exams/list-mx/{exam}',         [ExamsController::class, 'listMedications']);
    Route::get('/sys/exams/list-dc/{exam}',         [ExamsController::class, 'listDocuments']);
    Route::post('/sys/ex-dx/validate-match',        [ExamsController::class, 'validateMatchDx']);
    Route::post('/sys/ex-mx/validate-match',        [ExamsController::class, 'validateMatchMx']);
    Route::delete('/sys/ex/delete/{exam}',          [ExamsController::class, 'destroy']);
    Route::delete('/sys/ex-dc/delete/{id}',         [ExamsController::class, 'destroyDocuments']);
    Route::delete('/sys/ex-dx/delete/{id}',         [ExamsController::class, 'destroyDiagnostics']);
    Route::delete('/sys/ex-mx/delete/{id}',         [ExamsController::class, 'destroyMedications']);
    // Citas
    Route::get('/sys/appx',                         [AppointmentsController::class, 'index'])->name('emr.appointments.home');
    Route::get('/sys/appx/{appointment}',           [AppointmentsController::class, 'show']);
    Route::post('/sys/appx/store',                  [AppointmentsController::class, 'store']);
    Route::post('/sys/appx/list',                   [AppointmentsController::class, 'list']);
    Route::delete('/sys/appx/{appointment}',        [AppointmentsController::class, 'destroy']);
    // Fármacos 
    Route::get('/sys/drugs',                        [DrugsController::class, 'index'])->name('maintenance.drugs');
    Route::get('/sys/drugs/list',                   [DrugsController::class, 'list']);;
    Route::post('/sys/drugs/store',                 [DrugsController::class, 'store']);
    Route::get('/sys/drugs/{drug}',                 [DrugsController::class, 'show']);
    Route::delete('/sys/mx/delete/{drug}',          [DrugsController::class, 'destroy']);
    Route::post('/sys/drugs/search',                [DrugsController::class, 'search']);
    // Diagnosticos
    Route::get('/sys/diagnostics',                  [DiagnosticsController::class, 'index'])->name('maintenance.diagnostics');
    Route::post('/sys/diagnostics/list',            [DiagnosticsController::class, 'list']);
    Route::post('/sys/diagnostics/store',           [DiagnosticsController::class, 'store']);
    Route::get('/sys/diagnostics/{diagnosis}',      [DiagnosticsController::class, 'show']);
    Route::post('/sys/diagnostics/search',          [DiagnosticsController::class, 'search']);
    Route::delete('/sys/dx/delete/{diagnosis}',     [DiagnosticsController::class, 'destroy']);
    // Ocupaciones
    Route::get('/sys/occupations',                  [OccupationsController::class, 'index'])->name('maintenance.occupations');
    Route::get('/sys/occupations/list',             [OccupationsController::class, 'list']);
    Route::post('/sys/occupations/store',           [OccupationsController::class, 'store']);
    Route::get('/sys/occupations/{occupation}',     [OccupationsController::class, 'show']);
    Route::delete('/sys/oc/delete/{occupation}',    [OccupationsController::class, 'destroy']);
    Route::get('/sys/occupations/search',           [OccupationsController::class, 'search']);
    // Empresa
    Route::get('/sys/enterprise',                   [EnterpriseController::class, 'index'])->name('business.enterprise');
    Route::post('/sys/enterprise/store',            [EnterpriseController::class, 'store']);
    Route::get('/sys/enterprise/images',            [EnterpriseController::class, 'getImages']);
    // Especialidades
    Route::get('/sys/specialties',                  [SpecialtiesController::class, 'index'])->name('security.specialties.home');
    Route::get('/sys/specialties/list',             [SpecialtiesController::class, 'list']);
    Route::get('/sys/specialties/{specialty}',      [SpecialtiesController::class, 'show']);
    Route::post('/sys/specialties/store',           [SpecialtiesController::class, 'store']);
    Route::delete('/sys/specialties/delete',        [SpecialtiesController::class, 'destroy']);
    // Permisos
    Route::get('/sys/permissions',                  [PermissionsController::class, 'index'])->name('security.permissions.home');
    Route::get('/sys/permissions/list',             [PermissionsController::class, 'list']);
    Route::get('/sys/permissions/{permission}',     [PermissionsController::class, 'show']);
    Route::post('/sys/permissions/store',           [PermissionsController::class, 'store']);
    Route::delete('/sys/px/delete/{permission}',    [PermissionsController::class, 'destroy']);
    // Usuarios
    Route::get('/sys/users',                        [UsersController::class, 'index'])->name('security.users.home');
    Route::get('/sys/users/new',                    [UsersController::class, 'new'])->name('security.users.new');
    Route::get('/sys/users/edit/{user}',            [UsersController::class, 'edit'])->name('security.users.edit');
    Route::get('/sys/users/role/{user}',            [UsersController::class, 'role'])->name('security.users.role');
    Route::get('/sys/users/list',                   [UsersController::class, 'list']);
    Route::post('/sys/users/store',                 [UsersController::class, 'store']);
    Route::delete('/sys/users/{user}',              [UsersController::class, 'destroy']);
});