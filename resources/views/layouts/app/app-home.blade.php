<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">

  <!-- Call App Mode on ios devices -->
  <meta name="apple-mobile-web-app-capable" content="yes" />

  <!-- Remove Tap Highlight on Windows Phone IE -->
  <meta name="msapplication-tap-highlight" content="no">

  <link rel="shortcut icon" href="{{ Vite::asset('resources/images/dki-logo.png') }}" type="image/x-icon">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Page Title -->
  <title>@yield('title', config('app.name', 'Laravel'))</title>

  <!-- Scripts -->
  @vite([
    'theme/css/vendors~bundle.css',
    'theme/css/app~bundle.css',
    'resources/sass/app.scss',

    'resources/js/app/home.js'
  ])
</head>
<body>
  <div id="app">
    <main class="py-4">
      @yield('content')
    </main>
  </div>
  <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
    @csrf
  </form>

  @include('helpers.color-profile')
  @routes('landing')

  <script>
    /**
     * this is a hack for error: global is not defined
     */
    var global = global || window
  </script>

  <script type="application/json" data-settings-selector="settings-json">
    {!! json_encode([
      'locale' => app()->getLocale(),
      'timezone' => config('app.timezone'),
      'appname' => config('app.name'),
      'is_local' => config('app.env') == 'production' ? false : true,
      'current_date' => Str::now('Y-m-d'),
      'current_fulldate' => Str::now('Y-m-d H:i:s'),
      'home' => route('home'),
    ]) !!}
  </script>
</body>
</html>
