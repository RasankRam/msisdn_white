<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CallRequest;
use App\Http\Requests\StoreUpdateDeviceRequest;
use App\Http\Resources\DeviceCollection;
use App\Models\Device;
use App\Models\Imsi;
use App\Models\Msisdn;
use Illuminate\Http\Request;
use App\Http\Resources\Device as DeviceResource;
use Illuminate\Support\Facades\Artisan;

class MainController extends Controller
{
    public function index(Request $request) {

      $devicesQuery = Device::query();
      $type_search = null;
      $keyword = null;

      $orderBy_array = ['title', 'created_at','imei'];
      $orderBy_flag = false;

      foreach ($orderBy_array as $orderBy) {
        if ($request->filled($orderBy) && in_array($request->$orderBy, ['asc','desc'])) {
          $devicesQuery->orderBy($orderBy,$request->$orderBy);
          $orderBy_flag = true;
        }
      }

      if ($request->filled('mine')) {
        $user = AppHelper::get_user();
        if ($user) {
          $devicesQuery->where('user_id',$user->id);
        }
      }

      if ($request->filled('type') && $request->filled('search')) {
        $keyword = $request->search;
        switch ($request->type) {
          case 'device_title':
            $type_search = 'title';
            $devicesQuery->where($type_search, 'LIKE', "%$keyword%");
          break;
          case 'imei':
            $type_search = 'imei';
            $devicesQuery->where($type_search, 'LIKE', "%$keyword%");
            break;
          default:
            $type_search = $request->type;
            $devicesQuery->whereHas($type_search, function ($query) use ($keyword, $type_search) {
              $query->where($type_search,'like',"%$keyword%");
            });
        }
      }

      $devices = $orderBy_flag ? $devicesQuery->paginate(10) : $devicesQuery->orderBy('updated_at', 'desc')->paginate(10);

      if ($request->filled('search') && $request->filled('type')) {
        $devices->map(function ($row) use ($keyword, $type_search) {
          if (gettype($row->$type_search) === 'object') {
            $row->$type_search->$type_search = preg_replace('/(' . $keyword . ')/iu', "<b>$1</b>", $row->$type_search->$type_search);
          } else {
            $row->$type_search = preg_replace('/(' . $keyword . ')/iu', "<b>$1</b>", $row->$type_search);
          }
          return $row;
        });
      }

      //$this->makeSuccessResponse(new DeviceCollection($devices));

      return response()->json([
        "status" => true,
        "response" => new DeviceCollection($devices)
      ]);

    }


    public function get_my_phones() {
      $user = AppHelper::get_user();
      if (!$user) $this->makeErrorResponse('Access Denied',403);
      return $this->makeSuccessResponse(Device::where('user_id',$user->id)->get());
    }


  /**
   * Update an entry
   * @param $request
   * @param Device $device
   * @return \Illuminate\Http\JsonResponse|object
   */

    public function update_patch(StoreUpdateDeviceRequest $request) {

      $device = Device::where('id',$request->id)->first();


      if (!$device) {
        return $this->makeErrorResponse("Device not found");
      }

      // Принадлежит ли device зарегистрированному пользователю
      if ($device->creator && (!AppHelper::get_user() || $device->creator->id !== AppHelper::get_user()->id)) {
        return $this->makeErrorResponse('Access Denied', 403);
      }

      $imsi = Imsi::where('imsi',$request->imsi)->first();

      if (!$imsi) {
        $imsi = Imsi::create(['imsi' => $request->imsi]);
      }

      $msisdn = Msisdn::where('msisdn',$request->msisdn)->first();


      if (!$msisdn) {
        $msisdn = Msisdn::create(['msisdn' => $request->msisdn]);
      }

      $imsi->device_id = $device->id;
      $msisdn->device_id = $device->id;
      $imsi->save();
      $msisdn->save();

      $device->title = $request->title;
      $device->imei = $request->imei;

      $device->save();

      return $this->makeSuccessResponse("Device has been updated");
    }

  /**
   * Store new device
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse|object
   */

    public function store(StoreUpdateDeviceRequest $request) {

      $msisdn = Msisdn::where('msisdn',$request->msisdn)->first();

      if ($msisdn && $msisdn->device_id) {
        return $this->makeErrorResponse("MSISDN занят другим абонентом", 400);
      }

      if (!$msisdn) {
        $msisdn = Msisdn::create(['msisdn' => $request->msisdn]);
      }

      $imsi = Imsi::where('imsi',$request->imsi)->first();

      if ($imsi && $imsi->device_id) {
        return $this->makeErrorResponse("IMSI занят другим абонентом", 400);
      }

      if (!$imsi) {
        $imsi = Imsi::create(['imsi' => $request->imsi]);
      }

      $device = Device::create([
        'title' => $request->title,
        'imei' => $request->imei,
        'user_id' => AppHelper::get_user() ? AppHelper::get_user()->id : null
      ]);


      $imsi->device_id = $device->id;
      $msisdn->device_id = $device->id;
      $imsi->save();
      $msisdn->save();


      return $this->makeSuccessResponse(new DeviceResource($device), 201);
    }

  /** Destroy device by specified id
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */

    public function destroy($id) {
      $device = Device::where('id',$id)->first();
      if (!$device) {
        return $this->makeErrorResponse("Device not found");
      }

      // Принадлежит ли device зарегистрированному пользователю
      if ($device->creator && (!AppHelper::get_user() || $device->creator->id !== AppHelper::get_user()->id)) {
        return $this->makeErrorResponse('Access Denied', 403);
      }

      $device->delete();

      return $this->makeErrorResponse($device, 200);
    }

  /** Create device_msisdn
   * @param Request $request
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */

    public function store_msisdn(Request $request, $id) {

      if (!$request->msisdn) {
        return $this->makeErrorResponse("MSISDN обязательно к заполнению", 422);
      }

      $device = Device::where('id',$id)->first();

      if (!$device) {
        return $this->makeErrorResponse("Device not found");
      }

      // Принадлежит ли device зарегистрированному пользователю
      if ($device->creator && (!AppHelper::get_user() || $device->creator->id !== AppHelper::get_user()->id)) {
        return $this->makeErrorResponse('Access Denied', 403);
      }

      $msisdn = Msisdn::where('msisdn',$request->msisdn)->first();

      if (!$msisdn) {
        $msisdn = Msisdn::create([
          'msisdn' => $request->msisdn
        ]);
      }

      $msisdns_device = $device->black_list;

      if ($msisdns_device && $msisdns_device->filter(function ($item) use($msisdn) {
        return $item->msisdn === $msisdn->msisdn;
      })->count() > 0) {
        return $this->makeErrorResponse("Номер уже добавлен в белый список", 400);
      }

      $device->black_list()->attach($msisdn->id);

      return $this->makeSuccessResponse($msisdn->id);

    }

    public function remove_device_msisdn($device_id, $msisdn) {

      $device = Device::where('id',$device_id)->first();

      if (!$device) {
        return $this->makeErrorResponse("Device not found");
      }

      // Принадлежит ли device зарегистрированному пользователю
      if ($device->creator && (!AppHelper::get_user() || $device->creator->id !== AppHelper::get_user()->id)) {
        return $this->makeErrorResponse('Access Denied', 403);
      }

      $msisdn = Msisdn::where('msisdn',$msisdn)->first();


      if (!$msisdn) {
        return $this->makeErrorResponse("MSISDN not found");
      }

      $device->black_list()->detach($msisdn);

      return $this->makeSuccessResponse("MSISDN has been successfully removed");

    }

    public function reset() {
      Artisan::call('migrate:fresh --seed');
      return $this->makeSuccessResponse("Site in initial state");
    }

    public function call(CallRequest $request) {
      $device_msisdn = Msisdn::where('msisdn',$request->to_phone)->first()->device;
      if (!$device_msisdn) {
        return $this->makeSuccessResponse(true);
      }
      $black_list = $device_msisdn->black_list;
      $result = true;
      foreach ($black_list as $item) {
        if ($item->msisdn === $request->from_phone) {
          $result = false;
        }
      }
      if (!$result) return $this->makeErrorResponse($result, 400);
      return $this->makeSuccessResponse($result);
    }

    public function my_devices() {
      $user = AppHelper::get_user();
      if (!$user) {
        return $this->makeErrorResponse('Access Denied', 403);
      }
      $devices = Device::where('user_id',$user->id)->with('msisdn')->get();
      return $this->makeSuccessResponse($devices);
    }

    public function all_msisdns() {
      return $this->makeSuccessResponse(Msisdn::get());
    }
}
