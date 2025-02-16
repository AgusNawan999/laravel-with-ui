<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MediaDocument;
use App\Http\Controllers\Controller;

class DownloadController extends Controller
{
  public function show(MediaDocument $media)
  {
    $headers = [
      'Content-Type' => $media->mime_type,
      'Content-Length' => $media->size,
      'Content-disposition' => 'inline; filename="' . $media->name . '"',
      'Cache-Control' => 'public, must-revalidate, max-age=0',
      'Pragma' => 'public',
      'Expires' => 'Sat, 26 Jul 1997 05:00:00 GMT',
      'Last-Modified' => now()->format('D, d M Y H:i:s') . ' GMT',
    ];

    return response()->download($media->getPath(), $media->name, $headers, 'inline');
  }

  public function showNonMedia($params)
  {
    $decode = json_decode(decrypt_params($params));
    // index 0 must filename
    $file_name = $decode[0];
    // index 1 must date from created_at
    $date = \Carbon\Carbon::parse($decode[1]);
    $headers = [];
    $path = file_path($date, $file_name);
    return response()->download($path, $file_name, $headers, 'inline');
  }

  public function showCustomName(MediaDocument $media, $custom_name)
  {
    $headers = ['Content-Type' => $media->mime_type];
    return response()->download($media->getPath(), $custom_name, $headers, 'inline');
  }

}
