<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;

interface UserRepositoryInterface
{
  /**
   * Create login history for user
   *
   * @param Request $request
   * @param Authenticatable $user
   * @return mixed
   */
  public function loginHistory(Request $request, Authenticatable $user);

  /**
   * Get all user features
   *
   * @param string $userId
   * @return array <roles, permissions>
   */
  public function features($userId = null) : array;

   /**
    * Get all user menu
    *
    * @param string $parentId
    * @return mixed
    */
  public function menu($parentId = null): mixed;

}
