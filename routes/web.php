<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\EnrollmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard routes
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Course routes
Route::middleware('auth')->group(function () {
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::patch('/courses/{course}/toggle-published', [CourseController::class, 'togglePublished'])->name('courses.toggle-published');
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
Route::middleware(['auth', 'permission:view quizzes'])->group(function () {
    Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
});

Route::middleware(['auth', 'permission:create quizzes'])->group(function () {
    Route::get('/lessons/{lesson}/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
    Route::post('/lessons/{lesson}/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
});

Route::middleware(['auth', 'permission:edit quizzes'])->group(function () {
    Route::get('/quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('/quizzes/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
});

Route::middleware(['auth', 'permission:delete quizzes'])->group(function () {
    Route::delete('/quizzes/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy');
});

Route::middleware(['auth', 'permission:take quizzes'])->group(function () {
    Route::get('/quizzes/{quiz}/take', [QuizController::class, 'take'])->name('quizzes.take');
    Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
});

// Enrollment routes
Route::middleware(['auth', 'permission:view enrollments'])->group(function () {
    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::get('/enrollments/{enrollment}', [EnrollmentController::class, 'show'])->name('enrollments.show');
});

Route::middleware(['auth', 'permission:create enrollments'])->group(function () {
    Route::post('/enrollments', [EnrollmentController::class, 'store'])->name('enrollments.store');
});

Route::middleware(['auth'])->group(function () {
    Route::patch('/enrollments/{enrollment}/progress', [EnrollmentController::class, 'updateProgress'])->name('enrollments.update-progress');
    Route::patch('/enrollments/{enrollment}/complete', [EnrollmentController::class, 'complete'])->name('enrollments.complete');
    Route::delete('/enrollments/{enrollment}', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
