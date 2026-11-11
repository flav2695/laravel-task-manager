<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    //task with search and status filter
    public function index(Request $request)
    {
        $query = $request->User()->tasks();

        if ($request->filled('search')) {
            // $search = $request->input('search');
            $query->where('title', 'like', '%'.$request->search.'%');
                    // ->orWhere('description', 'like', "%$search%");
            // });
        }

       if(in_array($request->input('status'), ['pending', 'in_progress', 'completed'])) {
            $query->where('status', $request->input('status'));
        }


        $tasks = $query -> latest()
            ->paginate(10)
            ->withQueryString();

        $counts = $request->user()->tasks()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
            // ->toArray();    

        return view('tasks.index', compact('tasks', 'counts'));
    }

    //create or show task page
    public function create(){
        return view('tasks.create');
    }

    //save a task
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed'])],
        ]);

        $request->user()->tasks()->create($data);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    //show edit page
    public function edit(Request $request, Task $task)
    {
        abort_unless($task->user_id === $request->user()->id, 403);

        return view('tasks.edit', compact('task'));
    }


    //update task
    public function update(Request $request, Task $task)
    {
        abort_unless($task->user_id === $request->user()->id, 403);     
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed'])],
        ]);

        $task->update($data);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    //delete task
    public function destroy(Request $request, Task $task)
    {
        abort_unless($task->user_id === $request->user()->id, 403);

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

}    
