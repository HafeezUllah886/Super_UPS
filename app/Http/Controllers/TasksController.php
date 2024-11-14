<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TasksController extends Controller
{
    public function index()
    {
        $tasks = DB::table('tasks')
                ->orderBy('status', 'desc') // or 'desc' for descending order
                ->orderBy('due', 'asc') // or 'desc' for descending order
                ->get();

        return view('tasks.index', compact('tasks'));
    }

    public function store(request $req)
    {
        tasks::create($req->all());
        return back()->with('success', 'Task Created');
    }

    public function mark($id)
    {
        tasks::findOrFail($id)->update(
            [
                'status' => "Completed",
            ]
        );
        return back()->with('success', 'Task Completed');
    }

    public function update(request $req)
    {
        tasks::findOrFail($req->id)->update($req->all());

        return back()->with('success', 'Task Updated');
    }

    public function delete($id)
    {
        tasks::findOrFail($id)->delete();
        
       return redirect('/tasks')->with('error', 'Task Deleted');
    }
}
