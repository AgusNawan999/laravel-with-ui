<?php

namespace App\Helpers;

use Mews\Captcha\Captcha;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

/**
 * Class Captcha
 * @package Mews\Captcha
 */
class CustomCaptcha extends Captcha
{
  public function createCaptcha(string $config = 'default', $api = false)
  {
    if (ob_get_contents()) {
      ob_clean();
    }
    $path = $this->create($config, $api);
    return $path;
  }

  //check captcha only not remove session
  public function checkCaptcha(string $value)
  {
    if (!$this->session->has('captcha')) {
      return false;
    }

    $key = $this->session->get('captcha.key');
    $sensitive = $this->session->get('captcha.sensitive');
    $encrypt = $this->session->get('captcha.encrypt');

    if (!$sensitive) {
      $value = $this->str->lower($value);
    }

    if ($encrypt) $key = Crypt::decrypt($key);
    $check = $this->hasher->check($value, $key);

    return $check;
  }

  public function create(string $config = 'default', bool $api = false)
  {
      $this->backgrounds = $this->files->files(public_path('captcha-assets/backgrounds'));
      $this->fonts = $this->files->files(public_path('captcha-assets/fonts'));

      if (version_compare(app()->version(), '5.5.0', '>=')) {
          $this->fonts = array_map(function ($file) {
              /* @var File $file */
              return $file->getPathName();
          }, $this->fonts);
      }

      $this->fonts = array_values($this->fonts); //reset fonts array index

      $this->configure($config);

      $generator = $this->generate();
      $this->text = $generator['value'];

      $this->canvas = $this->imageManager->canvas(
          $this->width,
          $this->height,
          $this->bgColor
      );

      if ($this->bgImage) {
          $this->image = $this->imageManager->make($this->background())->resize(
              $this->width,
              $this->height
          );
          $this->canvas->insert($this->image);
      } else {
          $this->image = $this->canvas;
      }

      if ($this->contrast != 0) {
          $this->image->contrast($this->contrast);
      }

      $this->text();

      $this->lines();

      if ($this->sharpen) {
          $this->image->sharpen($this->sharpen);
      }
      if ($this->invert) {
          $this->image->invert();
      }
      if ($this->blur) {
          $this->image->blur($this->blur);
      }

      if ($api) {
          Cache::put($this->get_cache_key($generator['key']), $generator['value'], $this->expire);
      }

      return $api ? [
          'sensitive' => $generator['sensitive'],
          'key' => $generator['key'],
          'img' => $this->image->encode('data-url')->encoded,
          'val' => Crypt::encryptString(collect($generator['value'])->implode(''))
      ] : $this->image->response('png', $this->quality);
  }
}
