<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Http\Request;

interface ManagementUserRepositoryInterface
{

  /**
   * Show feature list by given term of search
   *
   * @param \Illuminate\Http\Request $request
   * @return array [response, error]
   */
  public function list(Request $request): array;

  /**
   * Show feature get Group Users by given term of search
   *
   * @param \Illuminate\Http\Request $request
   * @return array [response, error]
   */
  public function getGroupUsers(Request $request): array;




  public function save(array $input);

  public function update(array $input);
  public function drop(array $input);


}
