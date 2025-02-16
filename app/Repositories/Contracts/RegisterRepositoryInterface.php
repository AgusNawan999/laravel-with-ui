<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;

interface RegisterRepositoryInterface
{
  public function daftar(Request $request);
}
