<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::all();
        $users = User::all();

        $data = [
            'tasks' => $tasks,
            'users' => $users
        ];

        return view('home' , compact('data'));
    }

    public function addTask(Request $request) {
        $task = [
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => Auth::id(),
            'assign' => $request->assign
        ];

        $task = Task::create($task);

        $task->user_id = $task->user->name;
        $task->assigned = $task->assignTo->name;

        return response()->json($task);
    }

    public function editTask($id, Request $request) {
        $task = Task::find(1);

        $task->delete();

        return response()->json($task);
    }

    public function deleteTask($id) {
        //TODO add a task

        return Redirect::back();
    }
}
