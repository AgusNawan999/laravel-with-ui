<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface FeatureAccessRepositoryInterface
{
  /**
   * Show feature list by given term of search
   *
   * @param \Illuminate\Http\Request $request
   * @return array [response, error]
   */
  public function list(Request $request): array;
}
