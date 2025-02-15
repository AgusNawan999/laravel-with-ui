<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Repositories\Contracts\UserRepositoryInterface;

class GenerateTokenController extends Controller
{
  use AuthenticatesUsers;

  protected $user;

  /**
   * Login username to be used by the controller.
   *
   * @var string
   */
  protected $username;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(UserRepositoryInterface $user)
  {
    $this->user = $user;
    $this->username = $this->findUsername();
    // $this->middleware('api')->except('logout');
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
      'device_name' => 'required',
    ];

    $validator = Validator::make(
      $request->all(),
      $needValidate,
      [
        "{$this->username()}.required" => 'Username harus diisi',
        "{$this->username()}.string" => 'Username harus dalam bentuk string',
        'password.required' => 'Password harus diisi',
        'password.string' => 'Password harus dalam bentuk string',
        'device_name.required' => 'Device Name harus diisi',
      ]
    );

    if ($validator->fails())
      throw ValidationException::withMessages($validator->errors()->getMessages());
  }

  public function login(Request $request)
  {
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
   * Attempt to log the user into the application.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return bool
   */
  protected function attemptLogin(Request $request)
  {
    return $this->guard()->attempt(
      $this->credentials($request),
      $request->filled('remember')
    );
  }

  /**
   * Send the response after the user was authenticated.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
   */
  protected function sendLoginResponse(Request $request)
  {
    $this->clearLoginAttempts($request);

    if ($response = $this->authenticated($request, $this->guard()->user())) {
      return $response;
    }

    return $request->wantsJson()
      ? new JsonResponse([], 204)
      : redirect()->intended($this->redirectPath());
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
    [$session_data, $token] = $this->user->loginHistory($request, $user);

    return response()
      ->json(
        [
          'status' => 'success',
          'message' => 'Login berhasil',
          'data' => $session_data,
        ],
        JsonResponse::HTTP_OK,
        [
          'token_generate' => $token
        ]
      );
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
