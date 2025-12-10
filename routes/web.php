<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\InReportController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\MovieController;


Route::get('/', [ItemController::class, 'home'])->name('home');
Route::get('/category/{category_id}', [ItemController::class, 'itemsByCategory'])->name('items.by_category');

Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.post');
Route::get('/signup', [UserController::class, 'registerForm'])->name('signup');
Route::post('/signup', [UserController::class, 'register'])->name('signup.register');

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// User borrowing route (handled by BorrowingController for user-facing borrow flows)
// keep existing route if you use BorrowingController elsewhere
Route::post('/borrow/{item_id}', [BorrowingController::class, 'storeUserBorrow'])->name('borrow.store')->middleware('auth');
Route::post('/borrow/item/{id}', [ItemController::class, 'borrowStore'])->name('borrow.item')->middleware('auth');

// User profile and return
Route::get('/profile', [UserController::class, 'profile'])->name('profile')->middleware('auth');
Route::post('/return/{borrowing_id}', [BorrowingController::class, 'returnItem'])->name('return.item')->middleware('auth');
// Halaman khusus admin
Route::middleware('isAdmin')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // GANTI NAMA supaya tidak bentrok dengan admin.borrowings.chart di group /borrow
        Route::get(
            '/borrowings/chart-data',
            [BorrowingController::class, 'chartData']
        )->name('borrowings.chart_data'); // <-- changed from 'borrowings.chart' to 'borrowings.chart_data'

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Data user
        Route::prefix('/users')->name('users.')->group(function () {
        Route::get('/chart', [UserController::class, 'chart'])->name('chart');
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('delete');
        Route::get('/export', [UserController::class, 'exportExcel'])->name('export');
        Route::get('/trash', [UserController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [UserController::class, 'restore'])->name('restore');
        Route::delete('/deletepermanent/{id}', [UserController::class, 'deletePermanent'])->name('delete_Permanent');
    });
    // Data item
    Route::prefix('/items')->name('items.')->group(function () {
        Route::get('/chart', [ItemController::class, 'chart'])->name('chart');
        Route::get('/', [ItemController::class, 'index'])->name('index'); //menampilkan banyak data kalau show menampilkan 1 data
        Route::get('/create', [ItemController::class, 'create'])->name('create'); //menampilkan form
        Route::post('/store', [ItemController::class, 'store'])->name('store'); //memproses nambah data
        Route::patch('/deactivate/{id}', [ItemController::class, 'deactivate'])->name('deactivate'); //memproses nonaktifkan data
        Route::delete('/destroy/{id}', [ItemController::class, 'destroy'])->name('delete'); //memproses hapus data
        Route::get('/trash', [ItemController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [ItemController::class, 'restore'])->name('restore');

        Route::delete('/deletepermanent/{id}', [ItemController::class, 'deletePermanent'])->name('delete_Permanent');
        Route::get('/edit/{id}', [ItemController::class, 'edit'])->name('edit'); //menampilkan form edit data
        Route::put('/update/{id}', [ItemController::class, 'update'])->name('update'); //memproses update data
        Route::get('/export', [ItemController::class, 'exportExcel'])->name('export'); //memproses export data
    });
    // Data categories (master)
    Route::prefix('/categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [CategoryController::class, 'destroy'])->name('delete');
    });
    // Data Peminjaman & Pengembalian
    Route::prefix('/borrow')->name('borrowings.')->group(function () {
        Route::get('/chart', [BorrowingController::class, 'chart'])->name('chart');
        Route::get('/', [BorrowingController::class, 'index'])->name('index');
        Route::get('/create', [BorrowingController::class, 'create'])->name('create');
        Route::post('/store', [BorrowingController::class, 'store'])->name('store');
        Route::delete('/destroy/{id}', [BorrowingController::class, 'destroy'])->name('delete'); //memproses hapus data
        Route::get('/trash', [BorrowingController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [BorrowingController::class, 'restore'])->name('restore');
        Route::delete('/deletepermanent/{id}', [BorrowingController::class, 'deletePermanent'])->name('delete_Permanent');
         Route::get('/edit/{id}', [BorrowingController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [BorrowingController::class, 'update'])->name('update');
        Route::get('/export', [BorrowingController::class, 'export'])->name('export');
        Route::patch('/approve/{id}', [BorrowingController::class, 'approve'])->name('approve');
        Route::patch('/reject/{id}', [BorrowingController::class, 'reject'])->name('reject');
        Route::patch('/return/{id}', [BorrowingController::class, 'adminReturnItem'])->name('return');

        // verify endpoint untuk debugging (fetch /admin/borrow/chart/test harus mengembalikan JSON {ok:true})
        Route::get('/chart/test', function () {
            return response()->json(['ok' => true, 'time' => now()->toDateTimeString()]);
        })->name('chart.test');

        // use controller methods for chart endpoints instead of closures
        Route::get('/chart/bar', [BorrowingController::class, 'chartBar'])->name('chart.bar');
        Route::get('/chart/pie', [BorrowingController::class, 'chartPie'])->name('chart.pie');
        Route::get('/chart/item-status', [BorrowingController::class, 'chartItemStatus'])->name('chart.item_status');
        Route::get('/list', [BorrowingController::class, 'listJson'])->name('list');
    }); // end Route::prefix('/borrow')->name('borrowings.')->group(function () { ... })
}); // end Route::middleware('isAdmin')->prefix('admin')->name('admin.')->group(function () { ... })


