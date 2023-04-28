<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{

  /** Зарегистрировать нового пользователя
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function register(RegisterRequest $request)
  {

    if (User::where('email', $request->email)->first()) {
      $this->makeErrorResponse('email already in use', 400);
    }
    $api_token = Str::random(150);

    $user = User::create([
      "name" => $request->name,
      "password" => Hash::make($request->password),
      "email" => $request->email,
      "api_token" => $api_token,
    ]);

    return $this->makeSuccessResponse([
      "token" => $api_token,
      "user" => [
        "name" => $user->name,
        "email" => $user->email,
      ]
    ],201);
  }

  /** Авторизировать пользователя
   * @param Request $request
   */

  public function login(LoginRequest $request) {
    $user = User::where('email', $request->email)->first();

    if (!$user) {
      return $this->makeErrorResponse('incorrect username or password', 401);
    }

    if (Hash::check($request->password,$user->password)) {
      $api_token = Str::random(150);
      $user->api_token = $api_token;
      $user->save();

      return $this->makeSuccessResponse([
        "token" => $api_token,
        "user" => [
          "name" => $user->name,
        ]
      ]);

    } else {
      return $this->makeErrorResponse('incorrect username or password', 401);
    }
  }

  /**
   * Обновить профильные данные пользователя
   */

  public function update_profile(Request $request) {
    $user = User::where('api_token',request()->bearerToken())->first();

    $request_data = $request->only(['name','old_password','password','password_confirm','email']);

    if (count($request_data) == 0) {
      return $this->makeErrorResponse('All fields are empty', 422);
    }

    $rules_const = [
      "name" => "required|min:6",
      "old_password" => "required_with:password",
      'password' => 'required_with:password_confirm|same:password_confirm',
      'password_confirm' => 'min:6',
      "email" => "required|email",
    ];

    $rules = [];


    foreach ($request_data as $key => $value) {
      if ($value) {
        $rules[$key] = $rules_const[$key];
      }
    }


    $validator = Validator::make($request_data, $rules);

    if ($validator->fails()) {
      return $this->makeErrorResponse($validator->messages(), 422);
    }

    if (!$user) {
      return $this->makeErrorResponse('User not found');
    }

    if ($request->old_password && !Hash::check($request->old_password,$user->password)) {
      return $this->makeErrorResponse('Invalid old password');
    }

    unset($request_data['old_password']);
    unset($request_data['password_confirm']);


    foreach ($request_data as $key => $data) {
      if ($data) {
        if ($key === 'password') {
          $user->$key = Hash::make($data);
        } else {
          $user->$key = $data;
        }
      }
    }

    $user->save();

    return $this->makeSuccessResponse($user);


  }
}
