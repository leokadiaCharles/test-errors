<?php

namespace App\Http\Controllers;
use App\Models\Task;
use App\Models\user;
use App\Models\User_tasks;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\TaskAssigned;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public $assignees;
    

    public function index()
{
    $users = User::all();
    // $tasks = Task::all();
    

    // $tasks = Auth::user()->tasks()->get();

    return view('dashboard', compact('users'));   
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
                    'completed' => false,  // Set completed field to false by default
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

         // Retrieve the user
         $user = User::find($user_id);

         // Debug the task ID and user's email
        //  dd([
        //      'task_id' => $task->id,
        //      'user_id' => $user_id,
        //      'user_email' => $user ? $user->email : 'User not found'
        //  ]);

          // Ensure the user exists before trying to send an email
          if ($user) {
            // Send an email to the assigned user
            Mail::to($user->email)->send(new TaskAssigned($task, $user));
        }
    
        // Redirect to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'Task created successfully!');
    }
    

    public function getUserTasks()
{
    $user_id = auth()->id();
    $tasks = DB::table('tasks')
    ->join('users', 'tasks.user_id', 'users.id')
    ->select('tasks.id','tasks.title','tasks.description','tasks.start_date','tasks.end_date','tasks.status','users.name as user_name')
    ->where('tasks.user_id', $user_id)
    ->orderByRaw("CASE WHEN tasks.status = 'closed' THEN 1 ELSE 0 END") 
    ->orderBy('tasks.end_date', 'asc') 
    ->get(); 
}

 // Update the specified task in storage
 public function update(Request $request, $id)
 {
     $task = Task::findOrFail($id);

    // Determine if the task is completed 
    $isCompleted = $request->has('completed');

    $task->update([
        'title' => $request->input('title'),
        'description' => $request->input('description'),
        'start_date' => $request->input('start_date'),
        'end_date' => $request->input('end_date'),
        'status' => $isCompleted ? 'closed' : 'open', // Update status based on completion
    ]);

    

    // Update the completed status in the pivot table for the assigned users
    if ($request->has('user_ids')) {
        foreach ($request->input('user_ids') as $user_id) {
            \DB::table('user_tasks')
                ->where('task_id', $task->id)
                ->where('user_id', $user_id)
                ->update([
                    'completed' => $isCompleted,  // Update the completion status in the pivot table
                    'updated_at' => now(),
                ]);
        }
    }

    return redirect()->route('dashboard')->with('success', 'Task updated successfully!');
 }


 

 public function edit($id)
 {
     $task = Task::with('users')->findOrFail($id);
     $users = User::all(); // Get all users to display in the dropdown
 
     return view('tasks.edit', compact('task', 'users'));
 }
 

public function destroy($id)
{
    $task = Task::findOrFail($id);
    $task->delete();
    return redirect()->route('dashboard')->with('success', 'Task deleted successfully!');
}


public function updateStatus(Request $request, Task $task)
    {
       

        // Update the status of the task
        $task->status = $request->input('status');

        $task->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Task status updated successfully.');
    }

    // Other methods...
}




