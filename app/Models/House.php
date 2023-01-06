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
      $second = substr($num, 3, 11);
      // $third = substr($num, 7, 4);

      return $first .'-'. $second;
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

    public function estimatedTariff()
    {
      return $this->tariff()->estimate()->orderBy('urut');
    }

    public function actualTariff()
    {
      return $this->tariff()->actual()->orderBy('urut');
    }

    public function tegah()
    {
      return $this->hasMany(HouseTegah::class, 'house_id');
    }

    public function activeTegah()
    {
      return $this->tegah()->active();
    }

    public function sppb()
    {
      return $this->hasOne(Sppb::class, 'NO_BL_AWB', 'NO_HOUSE_BLAWB');
    }
}
