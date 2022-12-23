<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlpOnline extends Model
{
    use HasFactory;
    protected $table = 'tps_plp_online';
    protected $guarded = ['id'];

    public function master()
    {
      $this->belongsTo(Master::class, 'master_id');
    }

    public function scopePending($query)
    {
      return $query->where('status', 'Pending');
    }

    public function scopePendingBatal($query)
    {
      return $query->where('status', 'PendingBatal');
    }

    public function scopeApproved($query)
    {
      return $this->where('status', 'Approved');
    }

    public function scopeApprovedBatal($query)
    {
      return $this->where('status', 'ApprovedBatal');
    }

    public function logs()
    {
      return $this->hasMany(PlpOnlineLog::class, 'plp_id');
    }
}
