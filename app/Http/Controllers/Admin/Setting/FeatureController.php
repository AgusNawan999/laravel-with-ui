<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Resources\Setting\FeatureResources;
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
}
