<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Master extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tps_master';
    protected $guarded = ['id'];

    public function resolveRouteBinding($encryptedId, $field = null)
    {
        return $this->where('id', Crypt::decrypt ($encryptedId))->firstOrFail();
    }

    public function getArrivalsAttribute()
    {
      return ($this->ArrivalDate) ? Carbon::parse($this->ArrivalDate)->format('d-m-Y') .' '. $this->ArrivalTime : '';
    }

    public function getDateMawbAttribute()
    {
      return ($this->MAWBDate) ? Carbon::parse($this->MAWBDate)->format('d-m-Y') : '';
    }

    public function getMawbParseAttribute()
    {
      $num = str_replace(' ', '', $this->MAWBNumber);
      $first = substr($num, 0, 3);
      $second = substr($num, 3, 4);
      $third = substr($num, 7, 4);

      return $first .' '. $second .' '. $third;
    }

    public function branch()
    {
      return $this->belongsTo(GlbBranch::class, 'mBRANCH');
    }

    public function customs()
    {
      return $this->belongsTo(RefCustomsOffice::class, 'KPBC', 'Kdkpbc');
    }

    public function unlocoOrigin()
    {
      return $this->belongsTo(RefUnloco::class, 'Origin', 'RL_Code');
    }

    public function unlocoTransit()
    {
      return $this->belongsTo(RefUnloco::class, 'Transit', 'RL_Code');
    }

    public function unlocoDestination()
    {
      return $this->belongsTo(RefUnloco::class, 'Destination', 'RL_Code');
    }

    public function warehouseLine1()
    {
      return $this->belongsTo(RefBondedWarehouse::class, 'OriginWarehouse', 'warehouse_code');
    }

    public function houses()
    {
      return $this->hasMany(House::class, 'MasterID');
    }

    public function logs()
    {
      return $this->morphMany(TpsLog::class, 'logable');
    }
    
}
