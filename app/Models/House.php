<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class House extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tps_pjth';
    protected $guarded = ['id'];

    public function master()
    {
      return $this->belongsTo(Master::class, 'MasterID');
    }
    
    public function details()
    {
      return $this->hasMany(HouseDetail::class, 'HouseID');
    }

    public function logs()
    {
      return $this->morphMany(TpsLog::class, 'logable');
    }
}
