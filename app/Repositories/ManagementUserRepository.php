<?php

namespace App\Repositories;

use App\Models\{User, UserEtpp};
use App\Models\Setting\UserGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\ManagementUserRepositoryInterface;
use Illuminate\Support\Facades\{Log};
use Illuminate\Support\Facades\Hash;


class ManagementUserRepository implements ManagementUserRepositoryInterface
{
  protected $user;

  protected $mappedColumn = [];


  /**
   * Construct for user and etc
   *
   * @param \App\Models\User $user
   *
   */

   public function __construct(User $user)
   {
     $this->user = $user;
     $this->mappedColumn = [
        'username' => 'tm_users.username',
        'v_full_name' => 'tm_users.v_full_name',
        'group' => 'tm_group.v_group_name'
     ];
   }

  /**
   * Show feature list by given term of search
   *
   * @param \Illuminate\Http\Request $request
   * @return array [response, error]
   */

  public function list(Request $request): array
  {
    $error = null;
    $response = null;

    try {
      $selectedColumns = explode(',', $request->columns);
      $filtered = collect($this->mappedColumn)
        ->reject(fn ($v, $k) => !in_array($k, $selectedColumns))
        ->values();

      $perPage = $request->limit ?? $this->user->getPerPage();

      $query = $this->user
        ->leftJoin('tm_user_groups', 'tm_users.username', '=', 'tm_user_groups.username')
        ->leftJoin('tm_group', 'tm_user_groups.v_group_code', '=', 'tm_group.v_group_code')
        ->select([
          'tm_users.i_id',
          'tm_users.username',
          'tm_users.v_full_name',
          'tm_users.v_email',
          'tm_users.password',
          'tm_users.si_user_enable'
        ])
        ->when(
          $request->search,
          fn ($sql, $searchText) => $sql->searchByColumn($searchText, $filtered)
        )
        ->when(
          $request->has('status') && $request->status != 'semua',
          fn ($sql) => $sql->where('si_user_enable', $request->status == 'aktif' ? 1 : 0)
        )
        ->orderByDesc('tm_users.i_id')
        ->orderBy('tm_users.username')
        ->orderBy('tm_users.v_full_name')
        ->groupBy(
          'tm_users.i_id',
          'tm_users.username',
          'tm_users.v_full_name',
          'tm_users.v_email',
          'tm_users.password',
          'tm_users.si_user_enable'
        );

        $response = $query->paginate($perPage);

    } catch (\Throwable $th) {
      $error = 'Error saat mengambil data master pengguna';
      Log::error($error, [
        'payload' => $request->all(),
        'error' => [
          'message' => $th->getMessage()
        ]
      ]);
    }

    return [$response, $error];

  }

  /**
   * Show feature getGroupUsers by given term of search
   *
   * @param \Illuminate\Http\Request $request
   * @return array [response, error]
   */

  public function getGroupUsers(Request $request): array
  {
    $error = null;
    $response = null;
    try {
      $filtered = $request->columns;

      $query = DB::table('tm_group')
      ->select([
        'v_group_code',
        'v_group_name'
      ])
      ->when($request->search, function($sql, $search) use ($filtered) {
        foreach ($filtered as $key => $filter) {
          $fn = $key == 0 ? 'where' : 'orWhere';
          $sql->{$fn}(DB::raw("lower({$filter})"), 'like', '%' . strtolower($search) . '%');
        }
      })
      ->orderByDesc('tm_group.v_group_code')
      ->orderBy('tm_group.v_group_name');

      $response = $query->get();

    } catch (\Throwable $th) {
      $error = 'Error saat mengambil data master group ';
      Log::error($error, [
        'payload' => $request->all(),
        'error' => [
          'message' => $th->getMessage()
        ]
      ]);
    }

    return [$response, $error];
  }

  public function save(array $input)
  {
    $user_id = auth()->user()->username;
    try {
      $data = [
        'username' => $input['username'],
        'v_full_name' => $input['username'],
        'v_email' => $input['email'],
        'password' => Hash::make($input['password']),
        'v_created_by' => $user_id,
      ];

      $user = $this->user->create($data);
      $group = $input['groups'];
      if(!empty($user) ) {
        if(!empty($group)) {
          $data_group = array();
          foreach ($group as $key => $value) {
            array_push($data_group, array(
                'username'=>$input['username'],
                'v_group_code'=>$value['v_group_code'],
                'v_input_by' => $user_id,
                'dt_created_at' => Carbon::now())
            );
          }
          UserGroup::insert($data_group);
          createLog('tm_user_groups', 'INS', $data_group);
        }
      }
      createLog('tm_users', 'INS', $data);
      return true;

    } catch (\Throwable $th) {
      Log::error($th);
      return false;

    }

  }

  public function update(array $input)
  {
    $user_id = auth()->user()->username;
    $users = User::findBySlug($input['slug']);
    try {
      $data = [
        'username' => $input['username'],
        'v_full_name' => $input['full_name'],
        'v_email' => $input['email'],
        'si_user_enable' => $input['status'],
        'v_updated_by' => $user_id,
        'dt_updated_at' => Carbon::now()
      ];
      if($input['password'] != null ){
        array_push($data, array(
          'password' => $input['password']
        ));
      }
      $user = $users->update($data);
      createLog('tm_users', 'UPD', $data);
      UserGroup::where('username', $input['username'])->delete();
      $group = $input['groups'];
      if($user) {
        if(!empty($group)) {
          $data_group = array();
          foreach ($group as $key => $value) {
            array_push($data_group, array(
              'username'=>$input['username'],
              'v_group_code'=>$value['v_group_code'],
              'v_input_by' => $user_id,
              'dt_created_at' => Carbon::now())
            );
            createLog('tm_user_groups', 'UPD', $data_group);
          }

          UserGroup::insert($data_group);
        }
      }
      return true;
    } catch (\Throwable $th) {
      Log::error($th);
      return false;
    }
  }

  public function drop(array $input)
  {
    $user = User::findBySlug($input['slug']);

    try {
      $userGroup = UserGroup::where('username', $user->username)->delete();

      createLog('tm_user_groups', 'DEL', $userGroup);
      $user->delete();
      createLog('tm_users', 'DEL', $user);

      return true;
    } catch (\Throwable $th) {
      Log::error($th);
      return false;

    }
  }
}
