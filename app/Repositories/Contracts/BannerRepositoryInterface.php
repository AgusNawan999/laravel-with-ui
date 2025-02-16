<?php

namespace App\Repositories\Contracts;
use Illuminate\Http\Request;

interface BannerRepositoryInterface
{
  /**
   * undocumented function summary
   *
   * Undocumented function long description
   *
   * @param Request $request
   * @return array [response, error]
   **/
  public function list(Request $request): array;
}
