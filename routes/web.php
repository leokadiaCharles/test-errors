<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Models\user;
use App\Models\Task;
use Carbon\Carbon;


Route::get('/', function () {
    return view('welcome');
});


// Route::get('/dashboard', function () {
//     return view('dashboards');
// });

Route::get('/dashboard', function () {
    $user =User::all();
    $tasks = Task::all(); 
    return view('dashboard',compact('user', 'tasks'));
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/dashboards', [TaskController::class, 'index'])->name('task.index');
Route::post('/dashboard', [TaskController::class, 'store'])->name('task.store');
Route::get('/user-tasks/{userId}', [TaskController::class, 'getUserTasks'])->name('user.tasks');
Route::resource('tasks', TaskController::class);

require __DIR__.'/auth.php';
