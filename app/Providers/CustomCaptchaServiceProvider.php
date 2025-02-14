<?php

namespace App\Providers;

use App\Helpers\CustomCaptcha;
use Illuminate\Support\ServiceProvider;

class CustomCaptchaServiceProvider extends ServiceProvider {
  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register(): void
  {
    // Merge configs
    $this->mergeConfigFrom(
      config_path('/captcha.php'),
      'captcha'
    );

    // Bind captcha
    $this->app->bind('captcha', function ($app) {
      return new CustomCaptcha(
        $app['Illuminate\Filesystem\Filesystem'],
        $app['Illuminate\Contracts\Config\Repository'],
        $app['Intervention\Image\ImageManager'],
        $app['Illuminate\Session\Store'],
        $app['Illuminate\Hashing\BcryptHasher'],
        $app['Illuminate\Support\Str']
      );
    });
  }
}
