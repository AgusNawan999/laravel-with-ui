<?php

namespace App\Http\Controllers\Admin\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\GroupRequest;
use App\Http\Resources\setting\{FeatureResources, GroupResources};
use App\Repositories\Contracts\{FeatureAccessRepositoryInterface, GroupRepositoryInterface};

class GroupController extends Controller
{
  protected $group;
  protected $feature;

  public function __construct(
    GroupRepositoryInterface $group,
    FeatureAccessRepositoryInterface $feature,
  ) {
    $this->group = $group;
    $this->feature = $feature;
  }

  public function list(Request $request)
  {
    [$response, $error] = $this->group->list($request);

    if (!is_null($error)) {
      return response()->json([
        'status' => 'error',
        'message' => $error,
        'data' => []
      ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    return GroupResources::collection($response)
      ->additional(['status' => 'success']);
  }

  public function store(GroupRequest $request)
  {
    $request->validated();

    // prepare feature
    $cleanFeatures = collect($request->features)->map(fn($feature) => decode_slug($feature, 9))->toArray();

    // prepare group
    $group = $this->group->make([
      'v_group_code' => $request->kode,
      'v_group_name' => $request->nama,
      'v_input_by' => auth()->user()->username,
      'dt_input_date' => now(),
    ]);

    [$response, $error] = $this->group->store($group, $cleanFeatures);
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
      'data' => new GroupResources($response)
    ]);
  }

  public function edit(GroupRequest $request)
  {
    // validated request
    $request->validated();

    // prepare feature
    $cleanFeatures = collect($request->features)->map(fn ($feature) => decode_slug($feature, 9))->toArray();

    // set attributes
    $attributes = [
      'v_group_code' => $request->kode,
      'v_group_name' => $request->nama,
      'v_update_by' => auth()->user()->username,
      'dt_update_date' => now(),
    ];

    // fill model
    $group = $this->group->fillBySlug($request->slug, $attributes);

    // store
    [$response, $error] = $this->group->store($group, $cleanFeatures, 'update');
    if (!is_null($error)) {
      return response()->json([
        'status' => 'error',
        'message' => $error,
        'data' => []
      ],
        JsonResponse::HTTP_INTERNAL_SERVER_ERROR
      );
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Data berhasil diubah',
      'data' => new GroupResources($response)
    ]);
  }

  public function drop(GroupRequest $request)
  {
    $request->validated();

    $group = $this->group->findBySlug($request->slug);
    [, $error] = $this->group->drop($group);

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

  public function features(Request $request, $slug)
  {
    $group = $this->group->findBySlug($slug);
    $selectedFeatures = sprintf('|%s|', $group->features()->pluck('v_id')->join('|'));

    $request->merge([
      'with_child' => true,
      'format' => 'hierarcy',
      'selected' => $selectedFeatures
    ]);

    $response = $this->feature->getHierarcy();

    return FeatureResources::collection($response)
      ->additional(['status' => 'success']);
  }
}
