<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Msisdn extends Model
{
    public const UPDATED_AT = null; // выключение updated_at, однако created_at будет существовать
    protected $guarded = [];
    use HasFactory, SoftDeletes;

    public function device() {
      return $this->belongsTo(Device::class);
    }

}
