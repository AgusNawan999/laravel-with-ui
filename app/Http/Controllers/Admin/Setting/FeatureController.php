<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\FeatureRequest;
use App\Http\Resources\setting\FeatureResources;
use App\Http\Resources\StructureMenu;
use App\Repositories\Contracts\FeatureAccessRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
  protected $user;
  protected $feature;

  public function __construct(
    UserRepositoryInterface $user,
    FeatureAccessRepositoryInterface $feature
  ) {
    $this->user = $user;
    $this->feature = $feature;
  }

  public function list(Request $request)
  {
    [$response, $error] = $this->feature->list($request);

    if (!is_null($error)) {
      return response()->json([
        'status' => 'error',
        'message' => $error,
        'data' => []
      ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    return FeatureResources::collection($response)
      ->additional(['status' => 'success']);
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
  public function store(FeatureRequest $request)
  {
    $request->validated();

    $parent = null;
    if ($request->has('induk'))
      $parent = $this->feature->findParentBySlug($request->induk);

    $feature = $this->feature->make([
      'v_name' => $request->nama,
      'v_alias' => $request->alias,
      'v_type' => $request->tipe,
      'si_order' => $request->order ?? 0,
      'v_parent_id' => $parent->v_id ?? null,
      'tx_description' => $request->keterangan,
      'v_route' => $request->route,
      'v_icon' => $request->icon,
      'v_created_by' => auth()->user()->username,
      'dt_created_at' => now(),
    ]);

    [$response, $error] = $this->feature->store($feature);
    if (!is_null($error)) {
      return response()->json([
        'status' => 'error',
        'message' => $error,
        'data' => []
      ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Data berhasil disimpan',
      'data' => new FeatureResources($response)
    ]);
  }
  public function edit(FeatureRequest $request)
  {
    $request->validated();
    $parent = null;

    if ($request->has('induk'))
      $parent = $this->feature->findParentBySlug($request->induk);

    $attributes = [
      'v_name' => $request->nama,
      'v_alias' => $request->alias,
      'v_type' => $request->tipe,
      'si_order' => $request->order,
      'v_parent_id' => $parent ? $parent->v_id : null,
      'tx_description' => $request->keterangan,
      'v_route' => $request->route,
      'v_icon' => $request->icon,
      'v_updated_by' => auth()->user()->username,
      'dt_updated_at' => now(),
    ];

    $feature = $this->feature->fillBySlug($request->slug, $attributes);
    [$response, $error] = $this->feature->store($feature, 'update');
    if (!is_null($error)) {
      return response()->json([
        'status' => 'error',
        'message' => $error,
        'data' => []
      ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Data berhasil diubah',
      'data' => new FeatureResources($response)
    ]);
  }

  public function drop(FeatureRequest $request)
  {
    $request->validated();

    $feature = $this->feature->findBySlug($request->slug);
    [, $error] = $this->feature->drop($feature);

    if (!is_null($error)) {
      return response()->json([
        'status' => 'error',
        'message' => $error,
        'data' => []
      ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Data berhasil dihapus',
      'data' => []
    ]);
  }
}
