<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TodoRequest;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use App\Repositories\TodoRepository;
use App\Repositories\TodoRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class TodoController extends Controller
{
    private $todoRepository;

    public function __construct(TodoRepositoryInterface $todoRepository) {
        $this->todoRepository = $todoRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $todos = $this->todoRepository->userTodos(Auth::user());
            return response()->json([
                'code' => 101,
                'message' => 'Current user todos',
                'data' => TodoResource::collection($todos)
            ]);
        } catch (QueryException $e) {
            Log::error('[TodoController index] ' . $e);
            abort('500');
        } catch (\PDOException $e) {
            Log::error('[TodoController index] ' . $e);
            abort('500');
        } catch (\Exception $e) {
            Log::error('[TodoController index] ' . $e);
            abort('500');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TodoRequest $request, Todo $todo)
    {
        try{
            $this->todoRepository->store($todo, $request);

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
        } catch (QueryException $e) {
            Log::error('[TodoController store] ' . $e);
            abort('500');
        } catch (\PDOException $e) {
            Log::error('[TodoController store] ' . $e);
            abort('500');
        } catch (\Exception $e) {
            Log::error('[TodoController store] ' . $e);
            abort('500');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        try{
            if ($todo->user_id == Auth::id()){
                return response()->json([
                    'code' => 101,
                    'message' => 'Todo task',
                    'data' => new TodoResource($todo)
                ]);
            } else {
                return response()->json([
                    'code' => 102,
                    'message' => 'No todo task found'
                ], 404);
            }
        } catch (QueryException $e) {
            Log::error('[TodoController show] ' . $e);
            abort('500');
        } catch (\PDOException $e) {
            Log::error('[TodoController show] ' . $e);
            abort('500');
        } catch (\Exception $e) {
            Log::error('[TodoController show] ' . $e);
            abort('500');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TodoRequest $request, Todo $todo)
    {
        try{
            if ($todo->user_id == Auth::id()) {
                $is_updated = $this->todoRepository->update($todo, $request);
                if($is_updated){
                    return response()->json([
                        'code' => 101,
                        'message' => 'Todo task has been updated',
                        'data' => new TodoResource($todo)
                    ]);
                }
            } else {
                return response()->json([
                    'code' => 102,
                    'message' => 'No todo task found'
                ], 404);
            }
        } catch (QueryException $e) {
            Log::error('[TodoController index] ' . $e);
            abort('500');
        } catch (\PDOException $e) {
            Log::error('[TodoController index] ' . $e);
            abort('500');
        } catch (\Exception $e) {
            Log::error('[TodoController index] ' . $e);
            abort('500');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        try{
            if($todo->user_id == Auth::id()){
                $this->todoRepository->delete($todo);
                return response()->json([
                    'code' => 101,
                    'message' => 'Todo has been deleted'
                ]);
            } else {
                return response()->json([
                    'code' => 102,
                    'message' => 'No todo task found'
                ], 404);
            }
        } catch (QueryException $e) {
            Log::error('[TodoController destroy] ' . $e);
            abort('500');
        } catch (\PDOException $e) {
            Log::error('[TodoController destroy] ' . $e);
            abort('500');
        } catch (\Exception $e) {
            Log::error('[TodoController destroy] ' . $e);
            abort('500');
        }
    }
}
