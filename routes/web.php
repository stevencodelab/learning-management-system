<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizQuestionController;
use App\Http\Controllers\QuizTakingController;
use App\Http\Controllers\EnrollmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Public listing routes (no auth required) - MUST be before auth routes to avoid conflicts
Route::get('/courses', [WelcomeController::class, 'courses'])->name('landing.courses');
Route::get('/quizzes', [WelcomeController::class, 'quizzes'])->name('landing.quizzes');
Route::get('/teachers', [WelcomeController::class, 'teachers'])->name('landing.teachers');
Route::get('/testimonials', [App\Http\Controllers\TestimonialController::class, 'index'])->name('testimonials.index');

// Public detail routes (no auth required)
Route::get('/courses/{course}/detail/{slug?}', [CourseController::class, 'detail'])->name('courses.detail');
Route::get('/quizzes/{quiz}/detail/{slug?}', [QuizController::class, 'detail'])->name('quizzes.detail');

// Dashboard routes
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Course routes (auth required) - Note: /courses listing is public (landing.courses), auth users can access via dashboard
Route::middleware('auth')->group(function () {
    Route::get('/courses/manage', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::patch('/courses/{course}/toggle-published', [CourseController::class, 'togglePublished'])->name('courses.toggle-published');
    Route::get('/courses/{course}/{slug?}', [CourseController::class, 'show'])->name('courses.show');
});

// Module routes
Route::middleware(['auth', 'permission:view modules'])->group(function () {
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::get('/modules/{module}', [ModuleController::class, 'show'])->name('modules.show');
});

Route::middleware(['auth', 'permission:create modules'])->group(function () {
    Route::get('/courses/{course}/modules/create', [ModuleController::class, 'create'])->name('modules.create');
    Route::post('/courses/{course}/modules', [ModuleController::class, 'store'])->name('modules.store');
});

Route::middleware(['auth', 'permission:edit modules'])->group(function () {
    Route::get('/modules/{module}/edit', [ModuleController::class, 'edit'])->name('modules.edit');
    Route::put('/modules/{module}', [ModuleController::class, 'update'])->name('modules.update');
});

Route::middleware(['auth', 'permission:delete modules'])->group(function () {
    Route::delete('/modules/{module}', [ModuleController::class, 'destroy'])->name('modules.destroy');
});

// Lesson routes
Route::middleware(['auth', 'permission:view lessons'])->group(function () {
    Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index');
    Route::get('/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
});

Route::middleware(['auth', 'permission:create lessons'])->group(function () {
    Route::get('/modules/{module}/lessons/create', [LessonController::class, 'create'])->name('lessons.create');
    Route::post('/modules/{module}/lessons', [LessonController::class, 'store'])->name('lessons.store');
});

Route::middleware(['auth', 'permission:edit lessons'])->group(function () {
    Route::get('/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
    Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
});

Route::middleware(['auth', 'permission:delete lessons'])->group(function () {
    Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');
});

// Quiz routes
// Admin/Instructor can manage quizzes (create, edit, delete)
// Note: /quizzes listing is public (landing.quizzes), auth users can access via dashboard
Route::middleware(['auth'])->group(function () {
    // Quiz taking routes - All authenticated users (students)
    Route::get('/quizzes/{quiz}/start', [QuizTakingController::class, 'start'])->name('quiz.taking.start');
    Route::get('/quizzes/{quiz}/attempts/{attempt}', [QuizTakingController::class, 'show'])->name('quiz.taking.show');
    Route::post('/quizzes/{quiz}/attempts/{attempt}/save', [QuizTakingController::class, 'saveAnswer'])->name('quiz.taking.save');
    Route::post('/quizzes/{quiz}/attempts/{attempt}/submit', [QuizTakingController::class, 'submit'])->name('quiz.taking.submit');
    Route::get('/quizzes/{quiz}/attempts/{attempt}/result', [QuizTakingController::class, 'result'])->name('quiz.taking.result');
    Route::get('/quizzes/{quiz}/attempts/{attempt}/progress', [QuizTakingController::class, 'progress'])->name('quiz.taking.progress');
});

// Admin/Instructor only - Quiz management
Route::middleware(['auth'])->group(function () {
    Route::get('/quizzes/manage', [QuizController::class, 'index'])->name('quizzes.index');
    Route::get('/lessons/{lesson}/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
    Route::post('/lessons/{lesson}/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('/quizzes/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy');
    
    // Quiz question management routes - Admin/Instructor only
    Route::get('/quizzes/{quiz}/questions', [QuizQuestionController::class, 'index'])->name('quiz.questions.index');
    Route::get('/quizzes/{quiz}/questions/create', [QuizQuestionController::class, 'create'])->name('quiz.questions.create');
    Route::post('/quizzes/{quiz}/questions', [QuizQuestionController::class, 'store'])->name('quiz.questions.store');
    Route::get('/quizzes/{quiz}/questions/{question}/edit', [QuizQuestionController::class, 'edit'])->name('quiz.questions.edit');
    Route::put('/quizzes/{quiz}/questions/{question}', [QuizQuestionController::class, 'update'])->name('quiz.questions.update');
    Route::delete('/quizzes/{quiz}/questions/{question}', [QuizQuestionController::class, 'destroy'])->name('quiz.questions.destroy');
    Route::post('/quizzes/{quiz}/questions/reorder', [QuizQuestionController::class, 'reorder'])->name('quiz.questions.reorder');
    
    // Show quiz - must be last to avoid conflicts
    Route::get('/quizzes/{quiz}/{slug?}', [QuizController::class, 'show'])->name('quizzes.show');
});

// Enrollment routes - Students can enroll themselves
Route::middleware('auth')->group(function () {
    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::get('/enrollments/{enrollment}/{slug?}', [EnrollmentController::class, 'show'])->name('enrollments.show');
    Route::post('/enrollments', [EnrollmentController::class, 'store'])->name('enrollments.store');
    Route::patch('/enrollments/{enrollment}/progress', [EnrollmentController::class, 'updateProgress'])->name('enrollments.update-progress');
    Route::patch('/enrollments/{enrollment}/complete', [EnrollmentController::class, 'complete'])->name('enrollments.complete');
    Route::delete('/enrollments/{enrollment}', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');
});

// Student routes
Route::middleware(['auth', 'permission:view students'])->group(function () {
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
});

Route::middleware(['auth', 'permission:create students'])->group(function () {
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
});

Route::middleware(['auth', 'permission:edit students'])->group(function () {
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
});

Route::middleware(['auth', 'permission:delete students'])->group(function () {
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Testimonial routes
// IMPORTANT: Specific routes (manage) must come BEFORE parameterized routes ({testimonial})
Route::middleware('auth')->group(function () {
    // Admin management route - must be first to avoid route conflicts
    Route::get('/testimonials/manage', [App\Http\Controllers\TestimonialController::class, 'manage'])->name('testimonials.manage');
    
    // CRUD routes
    Route::get('/testimonials/create', [App\Http\Controllers\TestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('/testimonials', [App\Http\Controllers\TestimonialController::class, 'store'])->name('testimonials.store');
    Route::get('/testimonials/{testimonial}/edit', [App\Http\Controllers\TestimonialController::class, 'edit'])->name('testimonials.edit');
    Route::put('/testimonials/{testimonial}', [App\Http\Controllers\TestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('/testimonials/{testimonial}', [App\Http\Controllers\TestimonialController::class, 'destroy'])->name('testimonials.destroy');
    
    // Admin moderation actions
    Route::patch('/testimonials/{testimonial}/approve', [App\Http\Controllers\TestimonialController::class, 'approve'])->name('testimonials.approve');
    Route::patch('/testimonials/{testimonial}/reject', [App\Http\Controllers\TestimonialController::class, 'reject'])->name('testimonials.reject');
    Route::patch('/testimonials/{testimonial}/toggle-featured', [App\Http\Controllers\TestimonialController::class, 'toggleFeatured'])->name('testimonials.toggle-featured');
});

// Public testimonial routes - must be last to avoid conflicts
Route::get('/testimonials/{testimonial}', [App\Http\Controllers\TestimonialController::class, 'show'])->name('testimonials.show');

require __DIR__.'/auth.php';
