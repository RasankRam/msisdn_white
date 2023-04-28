<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  protected function makeSuccessResponse($response, $status = 200) {
    return response()->json([
      "status" => true,
      "response" => $response
    ], $status);
  }

  protected function makeErrorResponse($error, $status = 404) {
    return response()->json([
      "status" => false,
      "error" => $error
    ], $status);
  }

}
