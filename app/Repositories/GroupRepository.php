<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Setting\Group;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\{DB, Log};
use App\Repositories\Contracts\GroupRepositoryInterface;

class GroupRepository implements GroupRepositoryInterface
{
  protected $group;
  protected $mappedColumn = [];

  public function __construct(Group $group)
  {
    $this->group = $group;
    $this->mappedColumn = [
      'kode' => 'tm_group.v_group_code',
      'nama' => 'tm_group.v_group_name',
      'features' => 'features->v_name',
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

      $perPage = $request->limit ?? $this->group->getPerPage();
      $query = $this->group
        ->when(
          $request->search,
          fn ($sql, $searchText) => $sql->searchByColumn($searchText, $filtered)
        )
        ->when(
          $request->has('status') && $request->status != 'semua',
          fn ($sql) => $sql->where('si_aktif', $request->status == 'aktif' ? 1 : 0)
        )
        ->when(
          $request->has('ispj') && $request->ispj != 'semua',
          fn ($sql) => $sql->where('si_is_pj', $request->ispj == 'aktif' ? 1 : 0)
        )
        ->orderBy('v_group_code')
        ->orderBy('v_group_name');

      $response = $query->paginate($perPage);
    } catch (\Throwable $th) {
      $error = 'Error saat mengambil data master feature access';
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
   * Create and return an un-saved model instance
   *
   * @param array $attributes
   * @return \Illuminate\Database\Eloquent\Model
   */
  public function make(array $attributes): Model
  {
    return $this->group->make($attributes);
  }

  /**
   * Fill the model with an array of attributes.
   *
   * @param string $slug
   * @param array $attributes
   * @return \Illuminate\Database\Eloquent\Model
   */
  public function fillBySlug($slug, array $attributes): Model
  {
    $group = $this->findBySlug($slug);
    return $group->fill($attributes);
  }

  /**
   * Create or update a feature of models and persist them to the database
   *
   * @param \Illuminate\Database\Eloquent\Model $eloquentModel
   * @param string $mode
   * @param array $features
   * @return array [response, error]
   */
  public function store(Model $eloquentModel, array $features, $mode = 'insert'): array
  {
    $error = null;
    $response = null;
    DB::beginTransaction();

    try {
      if (!$eloquentModel->save())
        throw new \Exception("Error saat {$mode} data master group", -99);

      $group = $eloquentModel->refresh();
      $group->features()->syncWithPivotValues(
        $features,
        [
          'v_input_by' => auth()->user()->username,
          'dt_input_date' => now()
        ]
      );

      $response = $eloquentModel->loadMissing('features');
      DB::commit();
    } catch (\Throwable $th) {
      DB::rollBack();
      $error = $th->getCode() == -99
        ? $th->getMessage()
        : 'Terjadi kesalahan pada server. Silakan hubungi Admin.';

      Log::error($error, [
        'payload' => $eloquentModel->toArray(),
        'error' => ['message' => $th->getMessage()]
      ]);
    }

    return [$response, $error];
  }

  /**
   * Find feature by given slug_path
   *
   * @param  string $slug
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  public function findBySlug($slug): Group | null
  {
    return $this->group->findBySlug($slug);
  }

  /**
   * Delete a feature of models and persist them to the database
   *
   * @param \Illuminate\Database\Eloquent\Model $eloquentModel
   * @return array [response, error]
   */
  public function drop(Model $eloquentModel): array
  {
    $error = null;
    $response = null;

    DB::beginTransaction();
    try {
      if (!$eloquentModel->features()->detach())
        throw new \Exception('Error saat detach features dari data master group', -99);

      if (!$eloquentModel->delete())
        throw new \Exception('Error saat hapus data master group', -99);

      $response = true;
      DB::commit();
    } catch (\Throwable $th) {
      DB::rollBack();
      $error = $th->getCode() == -99
        ? $th->getMessage()
        : 'Terjadi kesalahan pada server. Silakan hubungi Admin.';

      Log::error($error, [
        'payload' => $eloquentModel->toArray(),
        'error' => ['message' => $th->getMessage()]
      ]);
    }

    return [$response, $error];
  }

  /**
   * Get group by given by code
   *
   * @param array|string $code
   * @return mixed
   */
  public function findByCode(array|string $code): mixed
  {
    $groups = $this->group
      ->when(is_string($code), fn($sql) => $sql->where('v_group_code', $code))
      ->when(is_array($code), fn($sql) => $sql->whereIn('v_group_code', $code));

    return is_array($code) ? $groups->get() : $groups->first;
  }

  /**
   * Get other group by given current group code
   *
   * @param string $currentPic
   * @return mixed
   */
  public function findOtherPic(string $currentPic): mixed
  {
    return $this->group
      ->where('v_group_code', '<>', $currentPic)
      ->get();
  }
}
