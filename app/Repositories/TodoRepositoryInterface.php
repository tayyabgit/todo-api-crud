<?php

namespace App\Repositories;

interface TodoRepositoryInterface {
  public function userTodos($user);
  public function store($todo, $request);
  public function update($todo, $request);
  public function delete($todo);
}