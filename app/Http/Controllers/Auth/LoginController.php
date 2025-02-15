<?php

namespace App\Http\Controllers\Auth;

use App\Rules\CheckCaptcha;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Repositories\Contracts\UserRepositoryInterface;

class LoginController extends Controller
{
  /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

  use AuthenticatesUsers;

  protected $user;

  /**
   * Login username to be used by the controller.
   *
   * @var string
   */
  protected $username;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected $redirectTo = RouteServiceProvider::HOME;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(UserRepositoryInterface $user)
  {
    $this->user = $user;
    $this->username = $this->findUsername();
    $this->middleware('guest')->except('logout');
  }

  /**
   * set username column
   *
   * @return column for login check
   */
  public function username()
  {
    return $this->username;
  }

  /**
   * Get the login username to be used by the controller.
   *
   * @return string
   */
  public function findUsername()
  {
    $login = request('username');
    $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    request()->merge([$fieldType => $login]);
    return $fieldType;
  }

  /**
   * Get the needed authorization credentials from the request.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return array
   */
  protected function credentials(Request $request)
  {
    return [
      'v_userid' => $request->{$this->username()},
      'v_userpass' => $request->password,
      'si_user_enable' => true
    ];
  }

  /**
   * Show the form login.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function showLogin()
  {
    return view('layouts.modules.login');
  }

  /**
   * Handle a login request to the application.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function login(Request $request)
  {
    $credentials = json_decode(decrypt_params($request->credentials));

    $request->merge([
      'username' => $credentials->username,
      'password' => $credentials->password,
      'captcha' => $credentials->captcha,
    ]);

    $this->validateLogin($request);

    // If the class is using the ThrottlesLogins trait, we can automatically throttle
    // the login attempts for this application. We'll key this by the username and
    // the IP address of the client making these requests into this application.
    if (
      method_exists($this, 'hasTooManyLoginAttempts') &&
      $this->hasTooManyLoginAttempts($request)
    ) {
      $this->fireLockoutEvent($request);

      return $this->sendLockoutResponse($request);
    }
    $attemp = $this->attemptLogin($request);
    if ($attemp) {
      if ($request->hasSession()) {
        $request->session()->put('auth.password_confirmed_at', time());
      }

      return $this->sendLoginResponse($request);
    }

    // If the login attempt was unsuccessful we will increment the number of attempts
    // to login and redirect the user back to the login form. Of course, when this
    // user surpasses their maximum number of attempts they will get locked out.
    $this->incrementLoginAttempts($request);

    return $this->sendFailedLoginResponse($request);
  }

  /**
   * Validate the user login request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return void
   *
   * @throws \Illuminate\Validation\ValidationException
   */

  protected function validateLogin(Request $request)
  {
    $needValidate = [
      $this->username() => ['required', 'string'],
      'password' => ['required', 'string'],
    ];

    if (app()->env == 'production') {
      $needValidate['captcha'] = ['required', new CheckCaptcha];
    }

    $request->validate($needValidate);
  }

  /**
   * Attempt to log the user into the application.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return bool
   */
  protected function attemptLogin(Request $request)
  {
    $dataCredentials = $request->validate([
      'username' => ['required'],
      'password' => ['required'],
    ]);

    return Auth::attempt($dataCredentials);
  }

  /**
   * The user has been authenticated.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  mixed  $user
   * @return mixed
   */
  protected function authenticated(Request $request, $user)
  {
    $this->user->loginHistory($request, $user);
  }

  /**
   * Get the failed login response instance.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Symfony\Component\HttpFoundation\Response
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  protected function sendFailedLoginResponse(Request $request)
  {
    $field = $this->username();
    $message = trans('auth.failed');

    if ($request->has('auth_failed.username')) {
      $message = $request->get('auth_failed.username');
    }

    if ($request->has('auth_failed.password')) {
      $field = 'password';
      $message = $request->get('auth_failed.password');
    }

    throw ValidationException::withMessages([$field => [$message]]);
  }
}
