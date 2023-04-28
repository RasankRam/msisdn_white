<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    protected $guarded = [];
    use HasFactory, SoftDeletes;

    public function user() {
      return $this->belongsTo(User::class);
    }

    public function imsi() {
      return $this->hasOne(Imsi::class);
    }

    public function black_list() {
      return $this->belongsToMany(Msisdn::class,'device_msisdn');
    }

    public function msisdn() {
      return $this->HasOne(Msisdn::class);
    }
}
