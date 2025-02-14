<?php

return [
  // Disabling the route() helper
  'skip-route-function' => true,

  //  exclude routes named
  'except' => [
    '_debugbar.*',
    'horizon.*',
    'ignition.*',
    'sanctum.*'
  ],

  'groups' => [
    'auth' => ['auth.*', 'backend.captcha.*'],
    'home' => ['backend.*','frontend.*'],
    'admin' => ['backend.*', 'admin.*', 'timestamp'],
  ]
];
