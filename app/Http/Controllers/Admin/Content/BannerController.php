<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\BannerRequest;
use App\Http\Resources\content\BannerResources;
use App\Repositories\Contracts\BannerRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannerController extends Controller
{
  protected $banner;

  public function __construct(
    BannerRepositoryInterface $banner
  ){
    $this->banner = $banner;
  }

  /**
   * List for data banner
   *
   * @param Request $request
   * @return void
   */
    public function list(Request $request)
    {
      [$response, $error] = $this->banner->list($request);

      if (!is_null($error)) {
        return response()->json([
          'status' => 'error',
          'message' => $error,
          'data' => []
        ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
      }

      return BannerResources::collection($response)
        ->additional(['status' => 'success']);
    }
    public function store(BannerRequest $request)
    {

      $request->validated();
      $banner = $this->banner->make([
        'v_title' => $request->title,
        'tx_description' => $request->deskripsi,
        'v_input_by' => auth()->user()->username,
        'dt_input_date' => now()
      ]);
       [$response, $error] = $this->banner->store($banner);
      $file = $request->file('file_image');
      if($response) {
        if($file != null){
          // file_image
          $this->upload($response,$request,$file);
        }

      }

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
        'data' => new BannerResources($response)
      ]);
    }

    public function edit(BannerRequest $request)
    {
      $request->validated();

      $data = $this->banner->make([
        'v_title' => $request->title,
        'tx_description' => $request->deskripsi,
        'v_update_by' => auth()->user()->username,
        'dt_update_date' => now()
      ])->toArray();
      $banner = $this->banner->fillBySlug($request->slug, $data);
      [$response, $error] = $this->banner->store($banner, 'update');
      $file = $request->file('file_image');

    if($response) {
      if($file != null){
        // file_image
        $this->upload($response,$request,$file);
      }

		}

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
        'data' => new BannerResources($response)
      ]);
    }

    public function drop(BannerRequest $request)
    {
      $request->validated();

      $banner = $this->banner->findBySlug($request->slug);
      [, $error] = $this->banner->drop($banner);

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
    public function upload($response,$request,$file){
      $currentFile = $response->getMedia('banner')->first();
      $datetimeString = now()->format('YmdHis'); // Format: YearMonthDayHourMinuteSecond
      $fileName = str_replace(' ', '-', $request->nama_file);
      $en_name = $datetimeString . '-' . $fileName;

      // Delete current image if replaced or delete asking
      if ($currentFile && ($file)) {
        $currentFile->forceDelete();
      }

      if ($file) {
        $ext  = '.'.strtolower($file->getClientOriginalExtension());
        $response->addMedia($file)
        ->usingName($en_name)
        ->usingFileName($en_name.$ext)
        ->toMediaCollection('banner');
      }
    }


}
