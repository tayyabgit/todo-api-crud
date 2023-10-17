<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;

class TodoRepository implements TodoRepositoryInterface
{
  public function userTodos($user){
    return $user->todos;
  }

  function store($todo, $request) {
    $todo->user_id = Auth::id();
    $todo->title = $request->title;
    $todo->description = $request->description;
    return $todo->save();
  }

  function update($todo, $request) {
    $todo->title = $request->title;
    $todo->description = $request->description;

    return $todo->update();
  }

  function delete($todo) {
    $todo->delete();
  }
}
