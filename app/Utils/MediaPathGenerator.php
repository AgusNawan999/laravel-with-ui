<?php

namespace App\Utils;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class MediaPathGenerator implements PathGenerator
{
  public function getPath(Media $media): string
  {
    return $this->getBasePath($media) . '/';
  }

  public function getPathForConversions(Media $media): string
  {
    return $this->getPath($media) . '/conversions/';
  }

  public function getPathForResponsiveImages(Media $media): string
  {
    return $this->getPath($media) . '/responsive-images/';
  }

  protected function getBasePath(Media $media): string
  {
    return $media->created_at->format('Y/m/d') . '/' . $media->getKey();
  }
}
