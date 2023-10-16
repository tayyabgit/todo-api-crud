<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TodoRequest;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = Auth::user()->todos;
        return response()->json([
            'code' => 101,
            'message' => 'Current user todos',
            'data' => TodoResource::collection($todos)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TodoRequest $request, Todo $todo)
    {
        $todo->user_id = Auth::id();
        $todo->title = $request->title;
        $todo->description = $request->description;

        if($todo->save()){
            return response()->json([
                'code' => 101,
                'message' => 'Todo task has been added',
                'data' => new TodoResource($todo)
            ]);
        } else {
            return response()->json([
                'code' => 102,
                'message' => 'Todo task has not been added'
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        if($todo){
            return response()->json([
                'code' => 101,
                'message' => 'Todo task',
                'data' => new TodoResource($todo)
            ]);
        } else {
            return response()->json([
                'code' => 102,
                'message' => 'No todo found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TodoRequest $request, Todo $todo)
    {
        $todo->title = $request->title;
        $todo->description = $request->description;

        if ($todo->user_id == Auth::id()) {
            if($todo->update()){
                return response()->json([
                    'code' => 101,
                    'message' => 'Todo task has been updated',
                    'data' => new TodoResource($todo)
                ]);
            } else {
                return response()->json([
                    'code' => 102,
                    'message' => 'Todo task has not been updated'
                ], 400);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        if($todo->user_id == Auth::id()){
            if(Todo::destroy($todo->id)){
                return response()->json([
                    'code' => 101,
                    'message' => 'Todo has been deleted'
                ]);
            } else {
                return response()->json([
                    'code' => 102,
                    'message' => 'Todo has not been deleted'
                ], 400);
            }
        } else {
            return response()->json([
                'code' => 103,
                'message' => 'No todo task found'
            ], 404);
        }
    }
}
