<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
  public function register()
  {
    $repositoryNamespace = 'App\\Repositories\\';
    $contractNamespace = 'App\\Repositories\\Contracts\\';
    $files = File::files(app_path('Repositories'));
    foreach ($files as $file) {
      $fileName = $file->getFilename();
      $baseName = substr($fileName, 0, strpos($fileName, '.'));
      $contractStringClass = $contractNamespace . $baseName . 'Interface';
      $repositoryStringClass = $repositoryNamespace . $baseName;
      $this->app->bind(
        $contractStringClass,
        $repositoryStringClass
      );
    }
  }
}
