<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TpsLog;
use App\Models\Master;
use App\Models\House;
use App\Models\HouseDetail;
use DataTables;

class LogsController extends Controller
{
    public function show(Request $request)
    {
      if($request->ajax()){
        $query = TpsLog::query();
        $type = $request->type;
        $id = $request->id;

        switch ($type) {
          case 'master':
            $master = Master::selectRaw('tps_master.id as mid, tps_pjth.id as hid, tps_pjtd.id as did')
                            ->join('tps_pjth', 'tps_master.id', '=', 'tps_pjth.MasterID', 'left outer')
                            ->join('tps_pjtd', 'tps_pjth.id', '=', 'tps_pjtd.HouseID', 'left outer')
                            ->where('tps_master.id', $id)
                            ->get();
                           
            $house = $master->unique('hid')
                            ->pluck('hid')
                            ->toArray();
            $detail = $master->where('did', '<>', null)
                             ->unique('did')
                             ->pluck('did')
                             ->toArray();
            
            $query->where(function($m) use ($id){
                    $m->where('logable_type', 'App\Models\Master')
                      ->where('logable_id', $id);
                  })
                  ->orWhere(function($h) use ($house){
                    $h->where('logable_type', 'App\Models\House')
                          ->whereIn('logable_id', $house);
                  })
                  ->orWhere(function($d) use ($detail){
                    $d->where('logable_type', 'App\Models\HouseDetail')
                           ->whereIn('logable_id', $detail);
                  });
            
            break;
          
          case 'house':
            $house = House::findOrFail($request->id);
            $detail = $house->details()->pluck('id')->toArray();

            // $query->where(function($h) use ($house){
            //         $h->where('logable_type', 'App\Models\House')
            //               ->whereIn('logable_id', $house->id);
            //       })
            $query->where('logable_type', 'App\Models\House')
                  ->where('logable_id', $house->id)
                  ->orWhere(function($d) use ($detail){
                    $d->where('logable_type', 'App\Models\HouseDetail')
                          ->whereIn('logable_id', $detail);
                  });
            break;
          
            default:
            $query = '';
            break;
        }

        $query->orderBy('created_at', 'desc');

        return DataTables::eloquent($query)
                         ->addIndexColumn()
                         ->addColumn('user', function($row){
                          return $row->user->name;
                         })
                         ->editColumn('created_at', function($row){
                          return $row->created_at->translatedFormat('l, d F Y H:i');
                         })
                         ->rawColumns(['keterangan'])
                         ->toJson();
      }
      
    }
}