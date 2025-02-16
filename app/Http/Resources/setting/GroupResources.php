<?php

namespace App\Http\Resources\setting;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\setting\FeatureResources;

class GroupResources extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    $data = [];
    $request->merge(['use_decode' => true, 'name_only' => true]);

    $data = [
      'slug' => $this->slug_path,
      'kode' => $this->v_group_code,
      'nama' => $this->v_group_name,
      'status' => $this->si_aktif ? 'Aktif' : 'Tidak Aktif',
    ];

    if (!$request->has('ignore_features')) {
      $data['features'] = [];

      if ($this->features()->count()) {
        $data['features'] = FeatureResources::collection($this->features);
      }
    }

    return $data;
  }
}
