<?php

namespace App\Http\Controllers;
use App\Models\House;
use Carbon\Carbon;
use DataTables;

use Illuminate\Http\Request;

class BeaCukaiAbandonController extends Controller
{
  public function index(Request $request)
  {
      if($request->ajax()){
        $tanggal = today()->subDays(30)->format('Y-m-d');

        $query = House::with(['master', 'details'])                      
                      ->whereNull('ExitDate')
                      ->whereNotNull('SCAN_IN_DATE')
                      ->where('SCAN_IN_DATE', '<', $tanggal);

        return DataTables::eloquent($query)
                         ->addIndexColumn()                         
                         ->addColumn('NO_PLP', function($row){
                          return "NO PLP";
                         })
                         ->addColumn('TGL_PLP', function($row){
                          return "TGL PLP";
                         })
                         ->addColumn('Age', function($row){
                          $diff = 0;
                          if($row->SCAN_IN_DATE){
                            $lama = Carbon::parse($row->SCAN_IN_DATE);

                            $diff = $lama->diffInDays(today());
                          }

                          return $diff;
                         })
                         ->addColumn('Penegahan', function($row){
                          $btn = '';
                          if($row->activeTegah->isEmpty()){
                            $btn = '<button id="btnTegah_'.$row->id.'"
                                          data-toggle="modal"
                                          data-target="#modal-tegah"
                                          class="btn btn-xs btn-danger elevation-2 tegah"
                                          data-id="'.Crypt::encrypt($row->id).'">
                                          <i class="fas fa-stop"></i> Stop</button>';
                          }                            

                          return $btn;
                         })
                         ->toJson();
      }

      $items = collect([
        'id' => 'id',
        'TGL_HOUSE_BLAWB' => 'Tgl HAWB',
        'NO_HOUSE_BLAWB' => 'No HAWB',
        'NO_PLP' => 'Nomor PLP',
        'TGL_PLP' => 'Tanggal PLP',
        'SCAN_IN_DATE' => 'Tanggal Masuk Gudang',
        'BC_CODE' => 'Kode BC',
        'BC_DATE' => 'Tanggal BC 11',
        'BC_STATUS' => 'BC Status',
        'NM_PENGIRIM' => 'Nama Pengirim',
        'NM_PENERIMA' => 'Consignee',
        'AL_PENERIMA' => 'Alamat',
        'LM_TRACKING' => 'LM Tracking',
        'AGE' => 'Age',
        'Penegahan' => 'Penegahan'
      ]);

      return view('pages.beacukai.abandon', compact('items'));
  }
}
