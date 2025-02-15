<?php

namespace App\Repositories;

use App\Http\Resources\Setting\FeatureResources;
use App\Http\Resources\StructureMenu;
use Illuminate\Http\Request;
use App\Models\Setting\FeatureAccess;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\{DB, Log};
use App\Repositories\Contracts\FeatureAccessRepositoryInterface;

class FeatureAccessRepository implements FeatureAccessRepositoryInterface
{
  protected $feature;
  protected $mappedColumn = [];

  public function __construct(FeatureAccess $feature)
  {
    $this->feature = $feature;
    $this->mappedColumn = [
      'nama_fitur' => 'tm_feature_access.v_name',
      'alias' => 'tm_feature_access.v_alias',
      'tipe_fitur' => 'tm_feature_access.v_type',
      'deskripsi' => 'tm_feature_access.tx_description',
      'nama_induk' => 'parent.v_name',
    ];
  }


  public function menu()
  {
    $userMenu = $this->user->menu();

    $menu = [
      'category' => 'root',
      'items' => StructureMenu::collection($userMenu)
    ];

    return response()->json([
      'status' => 'success',
      'data' => [ $menu ]
    ]);
  }

  public function hierarcy(Request $request)
  {
    $request->merge([
      'with_child' => true,
      'format' => 'hierarcy'
    ]);

    $response = $this->feature->getHierarcy();

    return FeatureResources::collection($response)
      ->additional(['status' => 'success']);
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
        ->reject(fn($v, $k) => !in_array($k, $selectedColumns))
        ->values();

      $perPage = $request->limit ?? $this->feature->getPerPage();
      $query = $this->feature
        ->leftJoin('tm_feature_access as parent', 'tm_feature_access.v_parent_id', '=', 'parent.v_id')
        ->select([
          'tm_feature_access.v_id',
          'tm_feature_access.v_name',
          'tm_feature_access.v_alias',
          'tm_feature_access.v_type',
          'tm_feature_access.si_order',
          'tm_feature_access.v_route',
          'tm_feature_access.v_icon',
          'tm_feature_access.tx_description',
          DB::raw('parent.v_name as induk'),
        ])
        ->when(
          $request->search,
          fn ($sql, $searchText) => $sql->searchByColumn($searchText, $filtered)
        )
        ->orderByDesc('tm_feature_access.v_parent_id')
        ->orderBy('tm_feature_access.si_order')
        ->orderBy('tm_feature_access.v_name');

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
    return $this->feature->make($attributes);
  }

  /**
   * Find parent feature by given slug_path
   *
   * @param string $slug
   * @return \App\Models\Setting\FeatureAccess
   */
  public function findParentBySlug($slug): FeatureAccess
  {
    return $this->findBySlug($slug);
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
    $feature = $this->findBySlug($slug);
    return $feature->fill($attributes);
  }

  /**
   * Create or update a feature of models and persist them to the database
   *
   * @param \Illuminate\Database\Eloquent\Model $eloquentModel
   * @param string $mode
   * @return array [response, error]
   */
  public function store(Model $eloquentModel, $mode = 'insert'): array
  {
    $error = null;
    $response = null;

    try {
      if (!$eloquentModel->save())
        throw new \Exception("Error saat {$mode} data master feature access", -99);

      $response = $eloquentModel->refresh();
    } catch (\Throwable $th) {
      $error = $th->getCode() == -99
        ? $th->getMessage()
        : 'Terjadi kesalahan pada server. Silakan hubungi Admin.';

      Log::error($error, [
        'payload' => $eloquentModel->toArray(),
        'error' => [ 'message' => $th->getMessage() ]
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
  public function findBySlug($slug): FeatureAccess
  {
    return $this->feature->findBySlug($slug);
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

    try {
      if (!$eloquentModel->delete())
        throw new \Exception('Error saat hapus data master feature access', -99);

      $response = true;
    } catch (\Throwable $th) {
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
   * Get feature as hierarcy
   *
   * @param boolean $onlyMenu
   * @return mixed
   */
  public function getHierarcy($onlyMenu = false) : mixed
  {
    return $this->feature
      ->whereNull('v_parent_id')
      ->orderBy('si_order')
      ->orderBy('v_id')
      ->get();
  }
}
