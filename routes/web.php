<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ModelController;

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
Route::get('login', function () {
    return Inertia::render('Auth/Login');
})->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // TAGS
    Route::get('/tags', function () {
        return Inertia::render('Admin/Tags');
    });
    Route::get('/tags-index', [TagController::class, 'index'])->name('tags.index');
    Route::post('/tags-add', [TagController::class, 'store'])->name('add.tag');
    Route::get('/tags-by-type', [TagController::class, 'tagsByType'])->name('tags.by_type');

    // MODEL
    Route::get('/model', function () {
        return Inertia::render('Admin/Model');
    });
    Route::get('/models-index', [ModelController::class, 'index'])->name('models.index');
    Route::post('/add-model', [ModelController::class, 'store'])->name('add.model');
});

Route::get('/', function () {
    return Inertia::render('Configure');
});

Route::get('/tags-by-type', [TagController::class, 'tagsByType'])->name('tags.by_type');
// Define the route with a placeholder for the parent tag ID
Route::get('/tags-by-type-pose/{selectedFinishTagId}', [TagController::class, 'tagsByTypePose'])->name('tags.by_type_pose');

// Route::get('/tags-by-type-pose', [TagController::class, 'tagsByTypePose'])->name('tags.by_type_pose');
Route::get('/tags-by-type-admin', [TagController::class, 'tagsByTypeAdmin'])->name('tags.by_type_admin');

// show model
Route::get('/model/{id}', [ModelController::class, 'show'])->name('models.conf');
Route::post('/search-model', [ModelController::class, 'search']);

Route::get('admin', function () {
    return Inertia::render('Auth/Login');
});




require __DIR__.'/auth.php';
