<?php


namespace App\Helpers;


use App\Models\User;

class AppHelper
{
  /**
   * Сгенерировать случайные числа с длиной 15 цифр
   * @param int $count
   * @param string $code_start
   * @return string
   */
  public static function code(int $count =  15, int $code_start = -1): string
  {
    $code = ($code_start === -1) ? mt_rand(1, 9) : $code_start;
    for ($i = 0; $i < $count - 1; $i++) {
      $code .= mt_rand(0, 9);
    }

    return $code;
  }

  /**
   * Взять данные о пользователе, который делает запрос
   * @param Request $request
   */

  public static function get_user() {
    $api_token = request()->bearerToken();

    if(!$api_token) {
      return false;
    }

    $user = User::where('api_token', $api_token)->first();

    if(!$user) {
      return false;
    }

    return $user;
  }

  public static function o_t_o_filled($class, $field, $emitter, $emitter_text) { // Device::class, device_id, Msisdn::class, msisdn
    $all_items = $class::all(); // берем все элементы главного класса

    foreach ($all_items as $item) { // проходимся по ним
      if ($item->$emitter_text === null) { // берём отношение элемента, у которого поле klass_id
        $result = $emitter::where($field,null)->inRandomOrder()->first();
        $result->$field = $item->id;
        $result->save();
      }
    }

  }
}
