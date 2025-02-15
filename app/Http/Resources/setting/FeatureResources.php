<?php

namespace App\Http\Resources\Setting;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeatureResources extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    $data = [];

    if ($request->has('with_child')) {
      $data['nodes'] = FeatureResources::collection($this->hasChild()->orderBy('si_order')->get());
    }

    if (
      $request->has('format')
      && $request->format == 'hierarcy'
    ) {
      $data['id'] = encode_slug($this->slug_path, $this->v_id, 9);
      $data['label'] = $this->v_name;
      $data['checked'] = false;
      $data['expanded'] = false;

      // override checked
      if ($request->has('selected')) {
        $fmt = sprintf('|%s|', $this->v_id);
        $data['checked'] = mb_strpos($request->selected, $fmt) !== false;
        $data['expanded'] = true;
      }

      return $data;
    }

    $data['slug'] = !$request->has('use_decode')
      ? $this->slug_path
      : encode_slug($this->slug_path, $this->v_id, 9);

    $data['nama_fitur'] = $this->v_name;
    if ($request->has('name_only')) return $data;

    $data = [
      'slug' => $this->slug_path,
      'alias' => $this->v_alias,
      'deskripsi' => $this->tx_description,
      'nama_fitur' => $this->v_name,
      'nama_induk' => $this->induk,
      'tipe_fitur' => $this->v_type,
      'route' => $this->v_route,
      'icon' => $this->v_icon,
      'order' => $this->si_order,
    ];

    return $data;
  }
}
