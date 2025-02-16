<?php

namespace App\Http\Resources\content;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResources extends JsonResource
{
  public function toArray(Request $request): array
  {
    $data = [
      'slug' => $this->slug_path,
      'title' => $this->v_title,
      'description' => $this->tx_description,
      'url_banner' => $this->getFileDokumenAttribute(),
      'nama_banner' => $this->getFileNameAttribute(),
    ];
    $media = $this
    ->media()
    ->where('model_id', $this->i_id)
    ->where('collection_name', 'banner')
    ->first();

    if ($media != null) {
      $data['file_dokumen'] = route('download.show', ['media' => $media->id]);
      $data['file_name'] = $media->name;
    }

    $media = $this->media;

    if ($media->count()) {
      $media = $this->media->first();
      $data['file_dokumen'] = route('download.show', ['media' => $media->id]);
      $data['file_name'] = $media->name;
    }
    return $data;
  }
}
