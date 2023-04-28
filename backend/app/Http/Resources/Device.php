<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Device extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//      dump(gettype($this->imsi));
        return [
          "id" => $this->id,
          "title" => $this->title,
          "imei" => $this->imei,
          "class_name" => "device",
          "imsi" => $this->imsi,
          "msisdn" => $this->msisdn,
          "black_list" => $this->black_list->map(function ($item) { return $item->msisdn; }),
          "creator" => $this->user,
          "created_at" => $this->created_at,
          "updated_at" => $this->updated_at,
        ];
    }
}
