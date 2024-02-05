<?php

use App\Http\Controllers\Api\DayController;
use App\Http\Controllers\Api\FieldController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\InfluencerController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WeekController;
use App\Http\Controllers\JWTController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('/register', [JWTController::class, 'register']);
    Route::post('/login', [JWTController::class, 'login']);
    Route::post('/logout', [JWTController::class, 'logout']);
    Route::post('/refresh', [JWTController::class, 'refresh']);
    Route::post('/profile', [JWTController::class, 'profile']);
});

Route::apiResource('roles', RoleController::class)
    ->parameters([
        'roles' => 'item'
    ])->names('roles');

Route::apiResource('users', UserController::class)
    ->parameters([
        'users' => 'item'
    ])->names('users');

Route::post('importExcel', [FileController::class, 'importExcel'])->name('file.importExcel');
Route::post('importDayExcel', [FileController::class, 'importDayExcel'])->name('file.importDayExcel');
Route::post('importUserExcel', [FileController::class, 'importUserExcel'])->name('file.importUserExcel');
Route::post('importWeekExcel', [FileController::class, 'importWeekExcel'])->name('file.importWeekExcel');

Route::apiResource('files', FileController::class)
    ->parameters([
        'files' => 'item'
    ])->names('files');


// // Total
// Route::get('total', [FieldController::class, 'total'])->name('field.total');
// Route::get('lastWeek', [FieldController::class, 'lastWeek'])->name('field.lastWeek');
// Route::get('today', [FieldController::class, 'today'])->name('field.today');
// Route::get('week', [FieldController::class, 'week'])->name('field.week');
// Route::get('month', [FieldController::class, 'month'])->name('field.month');
// Route::get('chart', [FieldController::class, 'chart'])->name('field.chart');
// Route::get('index', [FieldController::class, 'index'])->name('field.index');

// //casos
// Route::get('case/{case}', [FieldController::class, 'case']);


// influencers
Route::post('influencers/{item}/restore', [InfluencerController::class, 'restore'])->name('influencers.restore');
Route::post('influencers/{item}/forceDelete', [InfluencerController::class, 'forceDelete'])->name('influencers.forceDelete');
Route::apiResource('influencers', InfluencerController::class)
    ->parameters([
        'influencers' => 'item'
    ])->names('influencers');


// Días
Route::get('total', [DayController::class, 'total'])->name('field.total');
Route::get('lastWeek', [DayController::class, 'lastWeek'])->name('field.lastWeek');
Route::get('today', [DayController::class, 'today'])->name('field.today');
Route::get('week', [DayController::class, 'week'])->name('field.week');
Route::get('month', [DayController::class, 'month'])->name('field.month');
Route::get('chart', [DayController::class, 'chart'])->name('field.chart');
Route::get('index', [DayController::class, 'index'])->name('field.index');
Route::post('dateBetween', [DayController::class, 'dateBetween'])->name('field.dateBetween');
//casos
Route::get('case/{case}', [DayController::class, 'case']);


// Listar Todo de forma descendente
Route::get('day/all', [DayController::class, 'listAll'])->name('day.listAll');
Route::get('week/all', [WeekController::class, 'listAll'])->name('week.listAll');
