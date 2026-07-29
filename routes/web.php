<?php

use App\Http\Controllers\AdminPortalController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BehavioralController;
use App\Http\Controllers\CameraFeedController;
use App\Http\Controllers\ExaminationController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\MessagingController;
use App\Http\Controllers\ParentPortalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\StaffManagementController;
use App\Http\Controllers\StudentManagementController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\SuperAdminPortalController;
use App\Http\Controllers\TeacherPortalController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [PublicController::class, 'landing'])->name('public.landing');
Route::post('/enquiry', [PublicController::class, 'submitEnquiry'])->name('public.enquiry');

Route::get('/dashboard', function (Request $request) {
    if (! $request->user()) {
        return redirect()->route('login');
    }

    return redirect()->route($request->user()->role.'.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Camera/IVS API
    Route::get('/api/camera-feeds', [CameraFeedController::class, 'index'])->name('api.camera.index');
    Route::get('/api/camera-feeds/{cameraFeed}', [CameraFeedController::class, 'show'])->name('api.camera.show');

    // Announcements API
    Route::get('/api/announcements', [AnnouncementController::class, 'index'])->name('api.announcements.index');
    Route::post('/api/announcements', [AnnouncementController::class, 'store'])->name('api.announcements.store');

    // Internal Messaging (Chat)
    Route::get('/chat', [MessagingController::class, 'index'])->name('chat.index');
    Route::get('/api/chat/messages/{contact}', [MessagingController::class, 'getMessages'])->name('api.chat.messages');
    Route::post('/api/chat/send', [MessagingController::class, 'sendMessage'])->name('api.chat.send');

    // Security Monitoring
    Route::get('/security-cam', function () {
        return Inertia::render('SecurityCam/Index');
    })->middleware('role:admin,super_admin,staff,teacher')->name('security-cam.index');

    // ── Super Admin Portal ──
    Route::middleware(['role:super_admin'])->prefix('super-admin')->name('super_admin.')->group(function () {
        Route::get('/dashboard', [SuperAdminPortalController::class, 'index'])->name('dashboard');
        Route::post('/switch-school/{school}', [SuperAdminPortalController::class, 'switchSchool'])->name('switch-school');
    });

    // ── Admin Portal ──
    Route::middleware(['role:admin,super_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminPortalController::class, 'index'])->name('dashboard');
        Route::get('/theme', [ThemeController::class, 'show'])->name('theme.show');
        Route::put('/theme', [ThemeController::class, 'update'])->name('theme.update');

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
        Route::get('/staff', [StaffManagementController::class, 'index'])->name('staff.index');
        Route::get('/staff/create', [StaffManagementController::class, 'create'])->name('staff.create');
        Route::post('/staff', [StaffManagementController::class, 'store'])->name('staff.store');
        Route::get('/staff/{staff}/edit', [StaffManagementController::class, 'edit'])->name('staff.edit');
        Route::put('/staff/{staff}', [StaffManagementController::class, 'update'])->name('staff.update');
        Route::delete('/staff/{staff}', [StaffManagementController::class, 'destroy'])->name('staff.destroy');

        Route::get('/communication/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('/communication/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::delete('/communication/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        // Transport Management
        Route::get('/transport/fleet', [TransportController::class, 'fleetIndex'])->name('transport.fleet.index');
        Route::post('/transport/fleet', [TransportController::class, 'storeFleet'])->name('transport.fleet.store');

        Route::get('/transport/routes', [TransportController::class, 'routeIndex'])->name('transport.routes.index');
        Route::post('/transport/routes', [TransportController::class, 'storeRoute'])->name('transport.routes.store');

        Route::get('/transport/assign', [TransportController::class, 'assignIndex'])->name('transport.assign.index');
        Route::post('/transport/assign', [TransportController::class, 'storeAssignment'])->name('transport.assign.store');

        // Examination Hub (Admin)
        Route::get('/academics/exams', [ExaminationController::class, 'examIndex'])->name('exams.index');
        Route::post('/academics/exams', [ExaminationController::class, 'storeExam'])->name('exams.store');
        Route::get('/academics/exam-grading', [ExaminationController::class, 'gradeIndex'])->name('exams.grading.index');
        Route::post('/academics/exam-grading', [ExaminationController::class, 'storeGrade'])->name('exams.grading.store');
        Route::get('/academics/exams/{exam}/schedule', [ExaminationController::class, 'scheduleIndex'])->name('exams.schedule.index');
        Route::post('/academics/exams/{exam}/schedule', [ExaminationController::class, 'storeSchedule'])->name('exams.schedule.store');

        // CMS & Lead Management
        Route::get('/cms', [PublicController::class, 'cmsIndex'])->name('cms.index');
        Route::post('/cms/sector', [PublicController::class, 'updateSector'])->name('cms.sector.update');
        Route::get('/enquiries', [PublicController::class, 'enquiryIndex'])->name('enquiries.index');
    });

    // ── Teacher/Staff Portal ──
    Route::middleware(['role:staff,teacher'])->prefix('teacher')->name('staff.')->group(function () {
        Route::get('/dashboard', [TeacherPortalController::class, 'index'])->name('dashboard');
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance', [AttendanceController::class, 'storeAttendance'])->name('attendance.store');
        Route::get('/behavioral', [BehavioralController::class, 'index'])->name('behavioral.index');
        Route::post('/behavioral', [BehavioralController::class, 'storeBehavioral'])->name('behavioral.store');

        // Marks Entry
        Route::get('/marks-entry/{schedule}', [ExaminationController::class, 'marksIndex'])->name('marks.index');
        Route::post('/marks-entry', [ExaminationController::class, 'storeMark'])->name('marks.store');

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
        Route::get('/dashboard', function () {
            return Inertia::render('Accountant/Dashboard');
        })->name('dashboard');

        // Fee Management
        Route::prefix('fees')->name('fees.')->group(function () {
            Route::get('/groups', [FinanceController::class, 'feeGroupIndex'])->name('groups.index');
            Route::post('/groups', [FinanceController::class, 'storeGroup'])->name('groups.store');

            Route::get('/types', [FinanceController::class, 'feeTypeIndex'])->name('types.index');
            Route::post('/types', [FinanceController::class, 'storeType'])->name('types.store');

            Route::get('/masters', [FinanceController::class, 'feeMasterIndex'])->name('masters.index');
            Route::post('/masters', [FinanceController::class, 'storeMaster'])->name('masters.store');

            Route::get('/collect', [FinanceController::class, 'collectionIndex'])->name('collect.index');
            Route::get('/collect/{student}', [FinanceController::class, 'showCollection'])->name('collect.show');
            Route::post('/collect/{student}', [FinanceController::class, 'storeDeposit'])->name('collect.store');

            // Inventory Management
            Route::get('/inventory/categories', [InventoryController::class, 'categoryIndex'])->name('inventory.categories.index');
            Route::post('/inventory/categories', [InventoryController::class, 'storeCategory'])->name('inventory.categories.store');

            Route::get('/inventory/items', [InventoryController::class, 'itemIndex'])->name('inventory.items.index');
            Route::post('/inventory/items', [InventoryController::class, 'storeItem'])->name('inventory.items.store');

            Route::get('/inventory/suppliers', [InventoryController::class, 'supplierIndex'])->name('inventory.suppliers.index');
            Route::post('/inventory/suppliers', [InventoryController::class, 'storeSupplier'])->name('inventory.suppliers.store');

            Route::get('/inventory/issue', [InventoryController::class, 'issueIndex'])->name('inventory.issue.index');
            Route::post('/inventory/issue', [InventoryController::class, 'storeIssue'])->name('inventory.issue.store');

            // Library Management
            Route::get('/library/books', [LibraryController::class, 'bookIndex'])->name('library.books.index');
            Route::post('/library/books', [LibraryController::class, 'storeBook'])->name('library.books.store');

            Route::get('/library/members', [LibraryController::class, 'memberIndex'])->name('library.members.index');
            Route::post('/library/members', [LibraryController::class, 'storeMember'])->name('library.members.store');

            Route::get('/library/issue', [LibraryController::class, 'issueIndex'])->name('library.issue.index');
            Route::post('/library/issue', [LibraryController::class, 'storeIssue'])->name('library.issue.store');
            Route::post('/library/return/{issue}', [LibraryController::class, 'returnBook'])->name('library.return');
        });
    });

    // ── Librarian Portal ──
    Route::middleware(['role:librarian'])->prefix('librarian')->name('librarian.')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Librarian/Dashboard');
        })->name('dashboard');
    });

    // ── Receptionist Portal ──
    Route::middleware(['role:receptionist'])->prefix('receptionist')->name('receptionist.')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Receptionist/Dashboard');
        })->name('dashboard');
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
        Route::get('/results', [ExaminationController::class, 'resultsIndex'])->name('results.index');
        Route::get('/results/{exam}/download', [ExaminationController::class, 'downloadMarksheet'])->name('results.download');
    });
});

require __DIR__.'/auth.php';
