<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HouseTegah extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tps_bc_tegah';
    protected $guarded = ['id'];

    public function house()
    {
      return $this->belongsTo(House::class, 'house_id');
    }
}
