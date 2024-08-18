<?php

namespace App\Http\Controllers;
use App\Models\Task;
use App\Models\user;
use App\Models\User_tasks;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public $assignees;
    

    public function index()
{
    $users = User::all();
    $tasks = Task::all(); 

    return view('dashboard', compact('users', 'tasks'));   
}



    public function store(Request $request)
    {
        // Create a new task
        $task = Task::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'user_id' => auth()->id(),
        ]);
    
        // Assign the task to multiple users if user_ids are provided
        if ($request->has('user_ids')) {
            foreach ($request->input('user_ids') as $user_id) {
                \DB::table('user_tasks')->insert([
                    'task_id' => $task->id,
                    'user_id' => $user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    
        // Redirect to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'Task created successfully!');
    }
    

    public function getUserTasks($userId)
{
    $tasks = Task::whereHas('users', function($query) use ($userId) {
        $query->where('user_id', $userId);
    })->get();

    return view('tasks.user_tasks', ['tasks' => $tasks]); 

}

 // Update the specified task in storage
 public function update(Request $request, $id)
 {
     $task = Task::findOrFail($id);

     // Update the task with the new data
     $task->update([
        'title' => $request->input('title'),
        'description' => $request->input('description'),
        'start_date' => $request->input('start_date'),
        'end_date' => $request->input('end_date'),
        // 'status' => $request->input('status'),
    ]);


     return redirect()->route('dashboard')->with('success', 'Task updated successfully!');
 }

public function edit($id)
{
    $task = Task::findOrFail($id);
    return view('tasks.edit', compact('task'));
}

public function destroy($id)
{
    $task = Task::findOrFail($id);
    $task->delete();
    return redirect()->route('dashboard')->with('success', 'Task deleted successfully!');
}


}
