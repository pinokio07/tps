<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Master extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tps_master';
    protected $guarded = ['id'];
    // protected $cast = ['arrivals'];

    public function getArrivalsAttribute($value)
    {
      return ($this->ArrivalDate) ? Carbon::parse($this->ArrivalDate)->format('d-m-Y') .' '. $this->ArrivalTime : '';
    }

    public function getDateMawbAttribute($value)
    {
      return ($this->MAWBDate) ? Carbon::parse($this->MAWBDate)->format('d-m-Y') : '';
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
    
}
