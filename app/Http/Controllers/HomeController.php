<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        //TODO get all tasks and return to view

        return view('home');
    }

    public function addTask(Request $request) {
        //TODO add a task

        return Redirect::back();
    }

    public function editTask($id, Request $request) {
        //TODO add a task

        return Redirect::back();
    }

    public function deleteTask($id) {
        //TODO add a task

        return Redirect::back();
    }
}
