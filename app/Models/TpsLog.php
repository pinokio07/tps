<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TpsLog extends Model
{
    use HasFactory;
    protected $table = 'tps_logs';
    protected $guarded = ['id'];

    public function logable()
    {
      return $this->morphTo();
    }
}
