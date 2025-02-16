<?php
namespace App\Repositories;

use App\Models\Content\Banner;
use App\Repositories\Contracts\BannerRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BannerRepository implements BannerRepositoryInterface
{
  protected $banner;
  protected $mappedColumn = [];

  public function __construct(Banner $banner)
  {
    $this->banner = $banner;
    $this->mappedColumn = [
      'title' => 'tm_banner.v_title',
      'deskripsi' => 'tm_banner.tx_deskripsi',
    ];
  }

  public function list(Request $request): array
  {
    $error = null;
    $response = null;

    try {
      $selectedColumns = explode(',', $request->columns);
      $filtered = collect($this->mappedColumn)
        ->reject(fn ($v, $k) => !in_array($k, $selectedColumns))
        ->values();

      $perPage = $request->limit ?? $this->banner->getPerPage();
      $query = Banner::select([
        'i_id',
        'v_title',
        'tx_description'
      ])
      ->when(
        $request->search,
        fn ($sql, $searchText) => $sql->searchByColumn($searchText, $filtered)
      )
      ->orderBy('i_id', 'ASC');
      $response = $query->paginate($perPage);
    } catch (\Throwable $th) {
      $error = 'Error saat mengambil data master banner';
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
      return $this->banner->make($attributes);
    }

      /**
   * Find parent feature by given slug_path
   *
   * @param string $slug
   * @return \App\Models\Content\Banner
   */
    public function findParentBySlug($slug): Banner
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
     * Find ManualBook by given slug_path
     *
     * @param  string $slug
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findBySlug($slug)
    {
      $response = $this->banner->findBySlug($slug);

      return $response;
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

    public function drop(Model $eloquentModel): array
    {
      $error = null;
      $response = null;

      try {
        if (!$eloquentModel->delete())
          throw new \Exception('Error saat hapus data kategori aduan', -99);

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

}
