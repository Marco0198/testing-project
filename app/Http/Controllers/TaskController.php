<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;

use Illuminate\Http\Request;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return TaskCollection|Task[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllTasks()
    {
        return Task::all();
      //  return new TaskCollection(Task::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return TaskResource|false|string
     */
    public function createTask(Request $request)
    {
       $request->validate([
            'title' => 'required|',
            'description' => 'required',
            'status' => 'required',
        ]);

        $task = Task::create($request->only(
            ['title',
            'description',
            'status'
            ]));
        $name = $request->file('attach')->getClientOriginalName();
        $path = $request->file('attach')->storeAs('public/upload',$name);

        $task->attach= $path;
        $task->save();

       // return new TaskResource($task);
        return $task;

   }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return TaskResource
     */
    public function getTaskById($id)
    {
        $task=Task::where('id',$id)->first();

    return $task;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return TaskResource
     */
    public function updateTask(Request $request, Task $task)
    {
        $task->update($request->only([ 'title',
            'description',
            'attach',
            'status']));
        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTask( $id)
    {
        if($task=Task::where('id',$id)->first()){
            $task->delete();
            return response()->json(['success'=>true, "message" => ' successfully deleted',]);
        }
        else
        return response()->json(['success'=>true, "message" => 'task is already deleted',]);
    }


    /**
     * Handle the User "forceDeleted" event.
     *
     * @param  \App\Models\Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDeleted(Task $task): \Illuminate\Http\JsonResponse
    {
       $task->forceDelete();

        return response()->json(['success'=>true, "message" => ' this task was permanently deleted',]);
    }
}
