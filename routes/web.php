<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JsonReaderController;
use App\Http\Controllers\StudentController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\CustomRegisteredUserController;
use App\Http\Controllers\StudentFormController;



Route::get('/read-json', [JsonReaderController::class, 'readJson']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('', [DashboardController::class, 'index'])
        ->middleware(['require.student.profile'])
        ->name('dashboard');
        

    Route::middleware(['auth', 'role:parent', 'can:view-parent-page', 'require.student.profile'])->group(function () { //route for parent role
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('', [ProfileController::class, 'index'])->name('show');
            Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
            Route::post('/update', [ProfileController::class, 'update'])->name('update');

            Route::prefix('guardian')->name('guardian.')->group(function () {
                Route::get('/create', [GuardianController::class, 'create'])->name('create');
                Route::post('/store', [GuardianController::class, 'store'])->name('store');
                Route::get('/{guardian}', [GuardianController::class, 'show'])->name('show');
                Route::get('/{guardian}/edit', [GuardianController::class, 'edit'])->name('edit');
                Route::post('/{guardian}', [GuardianController::class, 'update'])->name('update');
                Route::delete('/{guardian}/delete', [GuardianController::class, 'delete'])->name('delete');
            });
        });

        Route::prefix('student')->name('student.')->group(function () {
            Route::get('', [StudentController::class, 'show'])->name('show');
            Route::get('/create', [StudentController::class, 'create'])->name('create');
            Route::post('/store', [StudentController::class, 'store'])->name('store');
            Route::get('/edit/{student}', [StudentController::class, 'edit'])->name('edit');
            Route::post('/update/{student}', [StudentController::class, 'update'])->name('update');
            Route::get('delete/{student}', [StudentController::class, 'delete'])->name('delete');
        });

        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('', [AttendanceController::class, 'show'])->name('show');
            Route::get('/export', [AttendanceController::class, 'export'])->name('export');
        });
    });
});

Route::middleware(['auth', 'role:admin'])->group(function () { //route for admin role

    Route::middleware(['can:view-admin-page'])->group(function () { //can view admin page only
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('show');
            Route::get('/create', [UserController::class, 'add'])->name('add');
            Route::post('/store', [UserController::class, 'store'])->name('store');
            Route::get('/edit/{userId}', [UserController::class, 'edit'])->name('edit');
            Route::post('/update/{userId}', [UserController::class, 'update'])->name('update');
            Route::delete('delete/{userId}', [UserController::class, 'delete'])->name('delete');
        });
    });
});

Route::middleware(['auth', 'role:admin|country'])->group(function () { //route for admin and country role
    Route::middleware(['can:view-country-page'])->group(function () { //can view country page only
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('', [CountryController::class, 'index'])->name('country');
            Route::get('/country_export', [CountryController::class, 'export'])->name('country_export');
        });
    });
});

Route::middleware(['auth', 'role:admin|country|state'])->group(function () { //route for admin, country, state role
    Route::middleware(['can:view-state-page'])->group(function () { //can view state page only
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/state/{state}', [StateController::class, 'index'])->name('state');
            Route::get('/state_export/{state_id}', [StateController::class, 'export'])->name('state_export');
        });
    });
});

Route::middleware(['auth', 'role:admin|country|state|ppd'])->group(function () { //route for admin, country, state, ppd (district) role
    Route::middleware(['can:view-ppd-page'])->group(function () { //can view ppd page only
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/ppd/{ppd}', [DistrictController::class, 'index'])->name('ppd');
            Route::get('/ppd_export/{ppd_id}', [DistrictController::class, 'export'])->name('ppd_export');
        });
    });
});

Route::middleware(['auth', 'role:admin|country|state|ppd|school'])->group(function () { //route for admin, country, state, ppd (district),school role
    Route::middleware(['can:view-school-page'])->group(function () { //can view school and student attendances page only
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/school/{school}', [SchoolController::class, 'index'])->name('school');
            Route::get('/student-attendance/{student}', [SchoolController::class, 'showStudentAttendance'])->name('student-attendance');
            Route::get('/school_export/{school_id}', [SchoolController::class, 'export'])->name('school_export');
        });
    });
});

Route::get('/guardian-form-partial', [GuardianController::class, 'guardianFormPartial'])->name('guardian.form.partial');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [CustomRegisteredUserController::class, 'store'])
    ->middleware('guest');

Route::get('/student-form-partial', [StudentFormController::class, 'partial']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
});