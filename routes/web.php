<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

// Home Route
Route::get('/', function () {
    return view('welcome');
});

// Dashboard Route
Route::get('/dashboard', function () {
    $user = User::all();
    $user_id = auth()->id();

    // Fetch tasks assigned to the logged-in user
    $tasks = DB::table('tasks')
        ->join('users', 'tasks.user_id', 'users.id')
        ->select('tasks.id', 'tasks.title', 'tasks.description', 'tasks.start_date', 'tasks.end_date', 'tasks.status', 'users.name as user_name')
        ->where('tasks.user_id', $user_id)
        ->orderByRaw("CASE WHEN tasks.status = 'closed' THEN 1 ELSE 0 END") 
        ->orderBy('tasks.end_date', 'asc') 
        ->get();

        // fetct task for asignee
      

  $works = DB::table('user_tasks')
    ->join('tasks', 'user_tasks.task_id', '=', 'tasks.id')
    ->join('users AS assigned', 'user_tasks.user_id', '=', 'assigned.id')
    // ->join('users AS supervisor', 'tasks.user_id', '=', 'supervisor.id')
    ->select('tasks.id',
        // 'user_tasks.id',
        'tasks.title',
        'tasks.description',
        'tasks.start_date',
        'tasks.end_date',
        'tasks.status',
        'assigned.name as assigned_name'
        // 'supervisor.name as supervisor_name'
    )
    ->where('user_tasks.user_id', $user_id)
    ->get();


    return view('dashboard', compact('user', 'tasks','works'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Task Management Routes
Route::middleware('auth')->group(function () {
    Route::resource('tasks', TaskController::class);
    
    Route::post('/dashboard', [TaskController::class, 'store'])->name('task.store');
    Route::get('/user-tasks', [TaskController::class, 'getUserTasks'])->name('user.tasks');
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::patch('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');


});

// Authentication Routes
require __DIR__.'/auth.php';
