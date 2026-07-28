<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\ParentPortalController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\StudentManagementController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [App\Http\Controllers\PublicController::class, 'landing'])->name('public.landing');
Route::post('/enquiry', [App\Http\Controllers\PublicController::class, 'submitEnquiry'])->name('public.enquiry');

Route::get('/dashboard', function (Illuminate\Http\Request $request) {
    if (!$request->user()) return redirect()->route('login');
    return redirect()->route($request->user()->role . '.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Camera/IVS API
    Route::get('/api/camera-feeds', [App\Http\Controllers\CameraFeedController::class, 'index'])->name('api.camera.index');
    Route::get('/api/camera-feeds/{cameraFeed}', [App\Http\Controllers\CameraFeedController::class, 'show'])->name('api.camera.show');

    // Announcements API
    Route::get('/api/announcements', [App\Http\Controllers\AnnouncementController::class, 'index'])->name('api.announcements.index');
    Route::post('/api/announcements', [App\Http\Controllers\AnnouncementController::class, 'store'])->name('api.announcements.store');

    // Internal Messaging (Chat)
    Route::get('/chat', [App\Http\Controllers\MessagingController::class, 'index'])->name('chat.index');
    Route::get('/api/chat/messages/{contact}', [App\Http\Controllers\MessagingController::class, 'getMessages'])->name('api.chat.messages');
    Route::post('/api/chat/send', [App\Http\Controllers\MessagingController::class, 'sendMessage'])->name('api.chat.send');

    // Security Monitoring
    Route::get('/security-cam', function () {
        return Inertia::render('SecurityCam/Index');
    })->middleware('role:admin,super_admin,staff,teacher')->name('security-cam.index');

    // ── Super Admin Portal ──
    Route::middleware(['role:super_admin'])->prefix('super-admin')->name('super_admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\SuperAdminPortalController::class, 'index'])->name('dashboard');
        Route::post('/switch-school/{school}', [App\Http\Controllers\SuperAdminPortalController::class, 'switchSchool'])->name('switch-school');
    });

    // ── Admin Portal ──
    Route::middleware(['role:admin,super_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AdminPortalController::class, 'index'])->name('dashboard');
        Route::get('/theme', [App\Http\Controllers\ThemeController::class, 'show'])->name('theme.show');
        Route::put('/theme', [App\Http\Controllers\ThemeController::class, 'update'])->name('theme.update');

        // Student Management
        Route::get('/students', [StudentManagementController::class, 'index'])->name('students.index');
        Route::get('/students/create', [StudentManagementController::class, 'create'])->name('students.create');
        Route::post('/students', [StudentManagementController::class, 'store'])->name('students.store');
        Route::get('/students/{student}/edit', [StudentManagementController::class, 'edit'])->name('students.edit');
        Route::put('/students/{student}', [StudentManagementController::class, 'update'])->name('students.update');
        Route::delete('/students/{student}', [StudentManagementController::class, 'destroy'])->name('students.destroy');

        // User Management
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::post('/users/{user}/toggle-suspend', [UserManagementController::class, 'toggleSuspend'])->name('users.toggle-suspend');

        // Staff Management (HR)
        Route::get('/staff', [App\Http\Controllers\StaffManagementController::class, 'index'])->name('staff.index');
        Route::get('/staff/create', [App\Http\Controllers\StaffManagementController::class, 'create'])->name('staff.create');
        Route::post('/staff', [App\Http\Controllers\StaffManagementController::class, 'store'])->name('staff.store');
        Route::get('/staff/{staff}/edit', [App\Http\Controllers\StaffManagementController::class, 'edit'])->name('staff.edit');
        Route::put('/staff/{staff}', [App\Http\Controllers\StaffManagementController::class, 'update'])->name('staff.update');
        Route::delete('/staff/{staff}', [App\Http\Controllers\StaffManagementController::class, 'destroy'])->name('staff.destroy');

        Route::get('/communication/announcements', [App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('/communication/announcements', [App\Http\Controllers\AnnouncementController::class, 'store'])->name('announcements.store');
        Route::delete('/communication/announcements/{announcement}', [App\Http\Controllers\AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        // Transport Management
        Route::get('/transport/fleet', [App\Http\Controllers\TransportController::class, 'fleetIndex'])->name('transport.fleet.index');
        Route::post('/transport/fleet', [App\Http\Controllers\TransportController::class, 'storeFleet'])->name('transport.fleet.store');
        
        Route::get('/transport/routes', [App\Http\Controllers\TransportController::class, 'routeIndex'])->name('transport.routes.index');
        Route::post('/transport/routes', [App\Http\Controllers\TransportController::class, 'storeRoute'])->name('transport.routes.store');
        
        Route::get('/transport/assign', [App\Http\Controllers\TransportController::class, 'assignIndex'])->name('transport.assign.index');
        Route::post('/transport/assign', [App\Http\Controllers\TransportController::class, 'storeAssignment'])->name('transport.assign.store');

        // Examination Hub (Admin)
        Route::get('/academics/exams', [App\Http\Controllers\ExaminationController::class, 'examIndex'])->name('exams.index');
        Route::post('/academics/exams', [App\Http\Controllers\ExaminationController::class, 'storeExam'])->name('exams.store');
        Route::get('/academics/exam-grading', [App\Http\Controllers\ExaminationController::class, 'gradeIndex'])->name('exams.grading.index');
        Route::post('/academics/exam-grading', [App\Http\Controllers\ExaminationController::class, 'storeGrade'])->name('exams.grading.store');
        Route::get('/academics/exams/{exam}/schedule', [App\Http\Controllers\ExaminationController::class, 'scheduleIndex'])->name('exams.schedule.index');
        Route::post('/academics/exams/{exam}/schedule', [App\Http\Controllers\ExaminationController::class, 'storeSchedule'])->name('exams.schedule.store');

        // CMS & Lead Management
        Route::get('/cms', [App\Http\Controllers\PublicController::class, 'cmsIndex'])->name('cms.index');
        Route::post('/cms/sector', [App\Http\Controllers\PublicController::class, 'updateSector'])->name('cms.sector.update');
        Route::get('/enquiries', [App\Http\Controllers\PublicController::class, 'enquiryIndex'])->name('enquiries.index');
    });

    // ── Teacher/Staff Portal ──
    Route::middleware(['role:staff,teacher'])->prefix('teacher')->name('staff.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\TeacherPortalController::class, 'index'])->name('dashboard');
        Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance', [App\Http\Controllers\AttendanceController::class, 'storeAttendance'])->name('attendance.store');
        Route::get('/behavioral', [App\Http\Controllers\BehavioralController::class, 'index'])->name('behavioral.index');
        Route::post('/behavioral', [App\Http\Controllers\BehavioralController::class, 'storeBehavioral'])->name('behavioral.store');

        // Marks Entry
        Route::get('/marks-entry/{schedule}', [App\Http\Controllers\ExaminationController::class, 'marksIndex'])->name('marks.index');
        Route::post('/marks-entry', [App\Http\Controllers\ExaminationController::class, 'storeMark'])->name('marks.store');

        // Assignment Management
        Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
        Route::get('/assignments/create', [AssignmentController::class, 'create'])->name('assignments.create');
        Route::post('/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
        Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
        Route::post('/assignments/{assignment}/toggle-publish', [AssignmentController::class, 'togglePublish'])->name('assignments.toggle-publish');
        Route::post('/submissions/{submission}/grade', [AssignmentController::class, 'gradeSubmission'])->name('submissions.grade');

        // Grade Book
        Route::get('/gradebook', [GradeController::class, 'index'])->name('gradebook.index');
        Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
    });

    // ── Accountant Portal ──
    Route::middleware(['role:accountant,super_admin'])->prefix('accountant')->name('accountant.')->group(function () {
        Route::get('/dashboard', function () { return Inertia::render('Accountant/Dashboard'); })->name('dashboard');
        
        // Fee Management
        Route::prefix('fees')->name('fees.')->group(function () {
            Route::get('/groups', [App\Http\Controllers\FinanceController::class, 'feeGroupIndex'])->name('groups.index');
            Route::post('/groups', [App\Http\Controllers\FinanceController::class, 'storeGroup'])->name('groups.store');
            
            Route::get('/types', [App\Http\Controllers\FinanceController::class, 'feeTypeIndex'])->name('types.index');
            Route::post('/types', [App\Http\Controllers\FinanceController::class, 'storeType'])->name('types.store');
            
            Route::get('/masters', [App\Http\Controllers\FinanceController::class, 'feeMasterIndex'])->name('masters.index');
            Route::post('/masters', [App\Http\Controllers\FinanceController::class, 'storeMaster'])->name('masters.store');

            Route::get('/collect', [App\Http\Controllers\FinanceController::class, 'collectionIndex'])->name('collect.index');
            Route::get('/collect/{student}', [App\Http\Controllers\FinanceController::class, 'showCollection'])->name('collect.show');
            Route::post('/collect/{student}', [App\Http\Controllers\FinanceController::class, 'storeDeposit'])->name('collect.store');

            // Inventory Management
            Route::get('/inventory/categories', [App\Http\Controllers\InventoryController::class, 'categoryIndex'])->name('inventory.categories.index');
            Route::post('/inventory/categories', [App\Http\Controllers\InventoryController::class, 'storeCategory'])->name('inventory.categories.store');
            
            Route::get('/inventory/items', [App\Http\Controllers\InventoryController::class, 'itemIndex'])->name('inventory.items.index');
            Route::post('/inventory/items', [App\Http\Controllers\InventoryController::class, 'storeItem'])->name('inventory.items.store');
            
            Route::get('/inventory/suppliers', [App\Http\Controllers\InventoryController::class, 'supplierIndex'])->name('inventory.suppliers.index');
            Route::post('/inventory/suppliers', [App\Http\Controllers\InventoryController::class, 'storeSupplier'])->name('inventory.suppliers.store');

            Route::get('/inventory/issue', [App\Http\Controllers\InventoryController::class, 'issueIndex'])->name('inventory.issue.index');
            Route::post('/inventory/issue', [App\Http\Controllers\InventoryController::class, 'storeIssue'])->name('inventory.issue.store');

            // Library Management
            Route::get('/library/books', [App\Http\Controllers\LibraryController::class, 'bookIndex'])->name('library.books.index');
            Route::post('/library/books', [App\Http\Controllers\LibraryController::class, 'storeBook'])->name('library.books.store');
            
            Route::get('/library/members', [App\Http\Controllers\LibraryController::class, 'memberIndex'])->name('library.members.index');
            Route::post('/library/members', [App\Http\Controllers\LibraryController::class, 'storeMember'])->name('library.members.store');
            
            Route::get('/library/issue', [App\Http\Controllers\LibraryController::class, 'issueIndex'])->name('library.issue.index');
            Route::post('/library/issue', [App\Http\Controllers\LibraryController::class, 'storeIssue'])->name('library.issue.store');
            Route::post('/library/return/{issue}', [App\Http\Controllers\LibraryController::class, 'returnBook'])->name('library.return');
        });
    });

    // ── Librarian Portal ──
    Route::middleware(['role:librarian'])->prefix('librarian')->name('librarian.')->group(function () {
        Route::get('/dashboard', function () { return Inertia::render('Librarian/Dashboard'); })->name('dashboard');
    });

    // ── Receptionist Portal ──
    Route::middleware(['role:receptionist'])->prefix('receptionist')->name('receptionist.')->group(function () {
        Route::get('/dashboard', function () { return Inertia::render('Receptionist/Dashboard'); })->name('dashboard');
    });

    // ── Parent Portal ──
    Route::middleware(['role:parent'])->prefix('parent')->name('parent.')->group(function () {
        Route::get('/dashboard', [ParentPortalController::class, 'index'])->name('dashboard');
        Route::get('/child/{student}/grades', [ParentPortalController::class, 'childGrades'])->name('child.grades');
        Route::get('/child/{student}/behavior', [ParentPortalController::class, 'childBehavior'])->name('child.behavior');
    });

    // ── Student Portal ──
    Route::middleware(['role:student'])->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentPortalController::class, 'index'])->name('dashboard');
        Route::get('/assignments', [StudentPortalController::class, 'assignments'])->name('assignments.index');
        Route::get('/assignments/{assignment}', [StudentPortalController::class, 'showAssignment'])->name('assignments.show');
        Route::post('/assignments/{assignment}/submit', [StudentPortalController::class, 'submitAssignment'])->name('assignments.submit');
        Route::get('/grades', [StudentPortalController::class, 'grades'])->name('grades.index');
        Route::get('/results', [App\Http\Controllers\ExaminationController::class, 'resultsIndex'])->name('results.index');
        Route::get('/results/{exam}/download', [App\Http\Controllers\ExaminationController::class, 'downloadMarksheet'])->name('results.download');
    });
});

require __DIR__.'/auth.php';
