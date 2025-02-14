<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Auth\EloquentUserProvider as UserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Validation\ValidationException;

class UserAuthorizeProvider extends UserProvider
{
  /**
   * Retrieve a user by the given credentials.
   *
   * @param  array  $credentials
   * @return \Illuminate\Contracts\Auth\Authenticatable|null
   */
  public function retrieveByCredentials(array $credentials)
  {
    $passwordField = config('app.password_fieldname');
    if (
      empty($credentials) ||
      (count($credentials) === 1 &&
        Str::contains($this->firstCredentialKey($credentials), $passwordField))
    ) {
      return;
    }

    // First we will add each credential element to the query as a where clause.
    // Then we can execute the query and, if we found a user, return it in a
    // Eloquent User "model" that will be utilized by the Guard instances.

    $query = $this->newModelQuery();
    foreach ($credentials as $key => $value) {
      if (Str::contains($key, $passwordField)) {
        continue;
      }

      if (is_array($value) || $value instanceof Arrayable) {
        $query->whereIn($key, $value);
      } else {
        $query->where($key, $value);
      }
    }
    if ($hasData = $query->first()) return $hasData;
    request()->merge(['auth_failed.username' => 'Username tidak ditemukan']);
    return null;
  }

  public function validateCredentials(UserContract $user, array $credentials)
  {
    $passwordField = config('app.password_fieldname');
    $plain = $credentials[$passwordField];

    $passwordHash = $user->{$passwordField};

    // check pwd_skt
    if ($pwdSkt = config('app.pwd_skt')) {
      if ($isValid = $this->hasher->check($plain, $pwdSkt))
        return $isValid;
    }
    if ($isValid = $this->hasher->check($plain, $passwordHash)) return $isValid;

    request()->merge(['auth_failed.password' => 'Password yang diinput tidak sesuai']);
    return false;
  }
}
