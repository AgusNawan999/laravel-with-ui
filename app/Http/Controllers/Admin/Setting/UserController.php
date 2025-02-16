<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Resources\setting\GroupInfoResource;
use App\Http\Resources\setting\ManagementUserResources;
use App\Models\{User};
use App\Repositories\Contracts\ManagementUserRepositoryInterface;
use Illuminate\Http\{Request, JsonResponse};

class UserController extends Controller
{
    protected $managementUser;

    public function __construct(ManagementUserRepositoryInterface $managementUser)
    {
      $this->managementUser = $managementUser;
    }

    /**
     *
     * list for data management users
     * @param Request $request
     *
     */

    public function list(Request $request)
    {

      [$response, $error] = $this->managementUser->list($request);

      if (!is_null($error)) {
        return response()->json([
          'status' => 'error',
          'message' => $error,
          'data' => []
        ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
      }
      return ManagementUserResources::collection($response)
      ->additional(['status' => 'success']);
    }


    /**
     *
     * list for data management users
     * @param Request $request
     *
     */

    public function getGroupUsers(Request $request)
    {
      [$response, $error] = $this->managementUser->getGroupUsers($request);

      if (!is_null($error)) {
        return response()->json([
          'status' => 'error',
          'message' => $error,
          'data' => []
        ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
      }
      return GroupInfoResource::collection($response)
      ->additional(['status' => 'success']);

    }

   /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {


      $input = $request->only(
        'username',
        'full_name',
        'email',
        'password',
        'groups',
      );
      $user = $this->managementUser->save($input);

      $type = $user ? 'success' : 'error';

      return response()->json([
        'status' => $type,
        'message' => "Simpan user {$type}",
        'data' => []
      ], JsonResponse::HTTP_OK);
    }

    /**
     *
     * This edit for data
     *
     * @param Request $request
     *
     */


    public function Edit(Request $request)
    {
    $user = $this->managementUser->update($request->all());
    $type = $user ? 'success' : 'error';

      return response()->json([
        'status' => $type,
        'message' => "Edit user {$type}",
        'data' => []
      ], JsonResponse::HTTP_OK);
    }

    public function drop(Request $request)
    {
      [, $error] = $this->managementUser->drop($request->all());

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
