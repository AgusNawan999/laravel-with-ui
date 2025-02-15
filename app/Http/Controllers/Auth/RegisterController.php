<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Repositories\Contracts\RegisterRepositoryInterface;

class RegisterController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Register Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles the registration of new users as well as their
  | validation and creation. By default this controller uses a trait to
  | provide this functionality without requiring any additional code.
  |
  */

  use RegistersUsers;

  /**
   * Where to redirect users after registration.
   *
   * @var string
   */
  protected $redirectTo = RouteServiceProvider::HOME;
  protected $registerInterface;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(RegisterRepositoryInterface $registerInterface)
  {
    $this->middleware('guest');
    $this->registerInterface = $registerInterface;
  }

  /**
   * Get a validator for an incoming registration request.
   *
   * @param  array  $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
  protected function validator(array $data)
  {
    return Validator::make($data, [
      'username' => ['required', 'string', 'max:255'],
      'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);
  }

  /**
   * Create a new user instance after a valid registration.
   *
   * @param  array  $data
   * @return \App\Models\User
   */

  public function register(Request $request)
  {
    $data = json_decode(decrypt_params($request->data));
    $user = $this->getUsereTppByNrk($data);
    if (count($user) == 0) {
      $data = [
        'status' => 'error',
        'message' => 'User Tidak terdaftar di e-TPP',
      ];
      return json_encode($data);
    }

    $verified = Hash::check($data->password, $user['v_userpass']);
    if ($verified) {
      $user = $user;
      $userpass_new = bcrypt($data->password);
      $user['userpass_new'] = $userpass_new;
      return $this->registerInterface->daftar($user);
    }
    $data = [
      'status' => 'error',
      'message' => 'Username atau password salah',
    ];
    return json_encode($data);
  }

  public function getUsereTppByNrk($user)
  {
    return $this->registerInterface->getUsereTppByNrk($user);
  }
}
