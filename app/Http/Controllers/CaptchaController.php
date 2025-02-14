<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Crypt, Log};

class CaptchaController extends Controller
{
  public function index()
  {
    $captcha = custom_captcha('flat',true);
    return response()->json(array_intersect_key($captcha, array_flip(['img', 'val'])));
  }

  public function read(Request $request)
  {
    $base = '';
    $path = '/captcha-assets/audio';
    $audio = null;
    $angka = null;

    try {
      $audio = Crypt::decryptString($request->audio);
      $audio = base64_encode($audio);
      $angka = base64_decode($audio);
      $arr = str_split($angka);
      foreach ($arr as $snd) {
        $suara = public_path($path) . '/' . $snd . '.mp3';
        $base .= file_get_contents($suara);
      }
      return response($base, 200, ['Content-type' => 'audio/mp3']);
    } catch (DecryptException $e) {
      Log::error('Error saat membacakan captcha', [
        'payload' => [
          'encrypted' => $request->audio,
          'audio' => $audio,
          'angka' => $angka
        ],
        'error' => [
          'message' => $e->getMessage()
        ]
      ]);

      return response()->json([
        'message' => 'Data tidak valid',
        'status' => 'error'
      ], 400);
    }
  }
}
