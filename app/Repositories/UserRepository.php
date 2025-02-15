<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Services\LayananRequest;
use Illuminate\Support\Facades\{DB, Log};
use App\Models\{User};
use Illuminate\Contracts\Auth\Authenticatable;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
  protected $user;
  protected $people;
  protected $complaint;
  protected $serviceReq;
  protected $dbLink;

  public function __construct(
    User $user,

  ) {
    $this->user = $user;
  }

  /**
   * Create login history for user
   *
   * @param Request $request
   * @param Authenticatable $user
   * @return mixed
   */
  public function loginHistory(Request $request, Authenticatable $user)
  {
    $data = [];

    try {
      $userAgent = $request->header('User-Agent');
      $viaApi = $request->is('api/*');
      $tokenName = null;
      $newToken = null;

      if ($viaApi) {
        // combine device_name with token_name
        $tokenName = config('app.api_token_name') . '_via_' . Str::lower(Str::snake($request->device_name));
        $userAgent .= '.' . $tokenName;

        // delete previous token
        $user
          ->tokens()
          ->where('name', $tokenName)
          ->delete();
      }

      $data = [
        'v_userip' => getIp(),
        'v_useragent' => $userAgent,
        'dt_last_loggedin' => now(),
        'v_created_by' => $user->username, // kalo lagi mode impersonate nanti diisi user yang impersonate
      ];

      $user->logHistory()->create($data);
      [$roles, $permissions] = $this->features();

      $sessionData = [
        'id' => $user->username,
        'name' => Str::title($user->v_full_name),
        'roles' => $roles,
        'permissions' => $permissions,
        'admin_home_permission' => config('app.admin_home_permission'),
      ];
      session(['user' => $sessionData]);

      return [$sessionData, $newToken];
    } catch (\Throwable $th) {
      Log::error('Error saat simpan user login history', [
        'payload' => $data,
        'error' => [
          'message' => $th->getMessage()
        ]
      ]);
    }
  }

  /**
   * Find user by given id or default using auth
   *
   * @param string $userId
   * @return mixed
   */
  protected function findUser($userId) : mixed
  {
    return $userId
      ? $this->user->find($userId)
      : auth()->user();
  }

  /**
   * Get all user features
   *
   * @param string $userId
   * @return array <roles, permissions>
   */
  public function features($userId = null): array
  {
    $groups = null;

    // find user
    $user = $this->findUser($userId);
    $groups = $user->groups()->get();

    $roles = $groups
      ->pluck('v_group_code')
      ->unique()
      ->toArray();

    $permissions = $groups
      ->loadMissing('features')
      ->flatMap(fn ($group) => $group->features)
      ->unique('v_id')
      ->pluck('v_alias')
      ->toArray();

    return [$roles, $permissions];
  }

  /**
   * Get all user menu
   *
   * @param string $parentId
   * @return mixed
   */
  public function menu($parentId = null): mixed
  {
    $conditional = function($query) use ($parentId) {
      $query
        ->with([
          'hasChild' => function($query) {
            $query
              ->whereExists(function($q) {
                $q->select(DB::raw(1))
                  ->from('tm_feature_access_group')
                  ->where('tm_feature_access_group.v_id_feature_access', DB::raw('tm_feature_access.v_id'))
                  ->whereIn('tm_feature_access_group.v_group_code', session('user.roles'));
              })
              ->orderBy('si_order');
          }
        ])
        ->where(DB::raw('lower(v_type)'), 'menu')
        ->orderBy('si_order');

      $parentId
        ? $query->where('v_parent_id', $parentId)
        : $query->whereNull('v_parent_id');
    };
    // find user
    $user = auth()->user();
    $groups = $user->groups()->get();

    return $groups
      ->load(['features' => fn ($sql) => $conditional($sql)])
      ->flatMap(fn ($group) => $group->features);
  }

}
