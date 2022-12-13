<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class House extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tps_houses';
    protected $guarded = ['id'];

    public function resolveRouteBinding($encryptedId, $field = null)
    {
        return $this->where('id', Crypt::decrypt ($encryptedId))->firstOrFail();
    }

    public function getMawbParseAttribute()
    {
      $num = str_replace(' ', '', $this->NO_MASTER_BLAWB);
      $first = substr($num, 0, 3);
      $second = substr($num, 3, 4);
      $third = substr($num, 7, 4);

      return $first .' '. $second .' '. $third;
    }

    public function master()
    {
      return $this->belongsTo(Master::class, 'MasterID');
    }
    
    public function details()
    {
      return $this->hasMany(HouseDetail::class, 'HouseID');
    }

    public function customs()
    {
      return $this->belongsTo(RefCustomsOffice::class, 'KD_KANTOR', 'Kdkpbc');
    }

    public function logs()
    {
      return $this->morphMany(TpsLog::class, 'logable');
    }

    public function tariff()
    {
      return $this->hasMany(HouseTariff::class, 'house_id');
    }
}
