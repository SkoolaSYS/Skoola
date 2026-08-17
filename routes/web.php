<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\ClassAttendanceController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\Api\ReceiveController;
use App\Http\Controllers\School\ReportController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\FpxController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\SchoolAuthController;
use App\Http\Controllers\SsoController;



Route::get('/debug-host', function() {
    return request()->getHost();
});

Route::domain('school.my3sss.com')->get('/test', function() {
    return "School subdomain works!";
});

Route::get('/debug-session', function () {
    return session()->all();
});

Route::domain('school.my3sss.com')->middleware(['web'])->group(function () {

    Route::get('/login', [\App\Http\Controllers\SchoolAuthController::class, 'showLogin'])
        ->name('school.login')
        ->middleware('guest');

    Route::post('/login', [\App\Http\Controllers\SchoolAuthController::class, 'login'])
        ->name('school.login.submit')
        ->middleware('guest');

    Route::post('/logout', [\App\Http\Controllers\SchoolAuthController::class, 'logout'])
        ->name('school.logout');

    Route::middleware(['auth', 'role:school'])->group(function () {
        Route::get('/dashboard/{school}', [\App\Http\Controllers\SchoolController::class, 'index'])
            ->name('school.dashboard');
    });
});

Route::post('/indirect', [FpxController::class, 'handleCallback'])->name('indirect.callback');
Route::post('/direct', [FpxController::class, 'handleDirect']);

Route::get('/sso/login', [
    SsoController::class,
    'login'
]);

Route::get('/read-json', [JsonReaderController::class, 'readJson']);

Route::get('/verify-email/{token}', [EmailVerificationController::class, 'verify']);



// ✅ Send verification email (PWA)
//Route::post('/attendance-parent/send', [EmailVerificationController::class, 'sendVerification'])
    //->name('attendance.send');

// ✅ Handle verification link from email
//Route::get('/attendance-parent/verify/{token}', [EmailVerificationController::class, 'verifyEmail'])
    //->name('attendance.verify');

// ✅ Parent dashboard (PWA iframe)
//Route::get('/attendance-parent', [DashboardController::class, 'pwaParentDashboard'])
    //->name('attendance.parent');

    // Login-based dashboard (normal auth)
Route::get('parent/dashboard', [ParentController::class, 'dashboard'])
    ->name('parent.dashboard');

Route::get('/test-query', function (Request $request) {
    return $request->all();
});

Route::get('/debug-email', function (Request $request) {
    return [
        'full_url' => $request->fullUrl(),
        'query' => $request->query(),
        'raw_query' => $_SERVER['QUERY_STRING'] ?? null
    ];
});


Route::match(['GET', 'POST'], '/attendance-parent', [ParentController::class, 'attendancePage'])
    ->name('parent.attendancePage');

Route::get('attendance-parent-verify', [ParentController::class, 'verifyEmail']);

Route::get('/parents/email-test', function () {
    return view('parents.email_test');
})->name('parents.email_test');
Route::get('parent/pwa-dashboard', [ParentController::class, 'pwaDashboard'])
    ->name('parent.pwa');

// Show parent email page (from PWA)
Route::get('/parent-email', [EmailVerificationController::class, 'show'])
    ->name('parent.email');

    



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
            Route::get('/school/{school}/students/management',
                [SchoolController::class, 'studentManagement']
            )->name('school.students.management');
            Route::get('/class-management', [SchoolController::class, 'classManagement'])
            ->name('school.class_management');
            Route::post('/activate-grade', [SchoolController::class, 'activateGrade'])
            ->name('school.activate_grade');
            Route::delete('/school/deactivate-grade', 
                [SchoolController::class, 'deactivateGrade']
            )->name('school.deactivate_grade');
            Route::post('/dashboard/school/add-grade', [SchoolController::class, 'addGrade'])
            ->name('school.add_grade');
        Route::get('/edit-class', [SchoolController::class, 'editClass'])
            ->name('school.edit_class');
        Route::post('/update-class-names', [SchoolController::class, 'updateClassNames'])
            ->name('school.update_class_names');



        });
    });
});

Route::get('/get-students-by-parent-ic', function (\Illuminate\Http\Request $request) {

    $guardians = \App\Models\Guardian::where('ic', $request->ic)->get();

    if ($guardians->isEmpty()) {
        return response()->json([]);
    }

    $studentIds = $guardians->pluck('student_id');

    $students = \App\Models\Student::whereIn('id', $studentIds)->get();

    return response()->json($students);
});

Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/teacher/dashboard', [DashboardController::class, 'index'])->name('teacher.dashboard');
    Route::get('/students', [StudentController::class, 'index'])->name('student.index');
    Route::get('/students/filter', [StudentController::class, 'filter'])->name('student.filter');
    Route::post('/students/remarks', [StudentController::class, 'saveRemarks'])->name('student.remarks');
    Route::get('/teacher/students/search', [StudentController::class, 'search'])
    ->name('teacher.students.search');
    Route::post('/teacher/student-remarks', [StudentController::class, 'studentStore'])
    ->name('student.remarks.store');


});

Route::prefix('school')->name('school.')->middleware(['role:school'])->group(function () {
    Route::get('/student/create', [StudentController::class, 'schoolCreate'])->name('student.create');
    Route::post('/student/store', [StudentController::class, 'schoolStore'])->name('student.store');
    Route::get('/students/template', [StudentController::class, 'downloadTemplate'])->name('students.template');
    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('/students/{student}/edit', [StudentController::class, 'schoolEdit'])->name('students.edit');
    Route::put('/students/{student}/update', [StudentController::class, 'schoolUpdate'])->name('students.update');
    Route::get('/students/{student}/details', [StudentController::class, 'details'])
        ->name('students.details');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');



    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])->name('teachers.show');
    Route::get('/teachers/{teacher}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::put('/teachers/{id}/toggle', [TeacherController::class, 'toggleStatus'])
    ->name('teachers.toggle');

});

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ms'])) {
        Session::put('applocale', $locale);
        App::setLocale($locale);
    }
    return Redirect::back();
})->name('lang.switch');

Route::prefix('school')->name('school.')->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});


Route::get('/class-attendance/pdf', [ClassAttendanceController::class, 'downloadPdf'])
    ->name('class_attendance.pdf');

Route::get('/class-attendance', [ClassAttendanceController::class, 'index'])
    ->name('class_attendance.index');

Route::post('/class-attendance', [ClassAttendanceController::class, 'store'])->name('class_attendance.store');
Route::get('/class_attendance/add', [ClassAttendanceController::class, 'add'])->name('class_attendance.add');
Route::post('/class-attendance/save', [ClassAttendanceController::class, 'saveClassAttendance'])
    ->name('class_attendance.save');

    Route::get('/class_attendance/edit', [ClassAttendanceController::class, 'edit'])
    ->name('class_attendance.edit');

    Route::post('/class-attendance/update', [ClassAttendanceController::class, 'updateAttendance'])
    ->name('class_attendance.update');

    Route::get('/class-attendance/pdf', [ClassAttendanceController::class, 'downloadPdf'])
    ->name('class_attendance.pdf');

Route::get('/class-attendance/{classId}', [ClassAttendanceController::class, 'show'])
    ->name('class_attendance.show');

      
    Route::post('/receive', function (\Illuminate\Http\Request $request) {
    // Get JSON payload
    $payload = $request->all(); // assuming the ASP sends JSON array

    // $data is an array of objects
    // For example: [{"card_id":"0000135566","time":"2025-10-29 07:30:12.001"}, {...}]
    if (isset($payload) && is_array($payload)) {
        foreach ($payload as $item) {
            DB::table('records')->insert([
                'card_id'       => $item['card_id'] ?? null,
                'time'          => $item['time'] ?? now(),
                'nama_pelajar'  => $item['nama_pelajar'] ?? null,
            ]);

        }
    }

    // Pass data to Blade view
    return view('receive', [
        'payload' => $payload,
    ]);
});

    

    //Route::post('/receive', function (Request $request) {

    //$payload = $request->all();

    //if (isset($payload['card_no'])) {

        //DB::table('records')->insert([
            //'card_id' => $payload['card_no'], 
            //'time'    => $payload['time'],
        //]);
    //}

   // return view('receive', [
     //   'payload' => $payload,
    //]);
//});



Route::post('/receive_post', function (Request $request) {
    return view('receive_post', [
        'nama' => $request->input('nama')
    ]);
});






Route::get('auth/{provider}', [SocialController::class, 'redirect']);
Route::get('auth/{provider}/callback', [SocialController::class, 'callback']);

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