<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HouseDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tps_pjtd';
    protected $guarded = ['id'];

    public function house()
    {
      return $this->belongsTo(House::class, 'HouseID');
    }

    public function logs()
    {
      return $this->morphMany(TpsLog::class, 'logable');
    }
}
