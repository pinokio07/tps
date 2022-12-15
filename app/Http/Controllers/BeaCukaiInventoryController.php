<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\House;
use App\Models\HouseDetail;
use Carbon\Carbon;
use DataTables, Crypt;

class BeaCukaiInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
          $query = House::with(['master', 'details']);

          return DataTables::eloquent($query)
                           ->addIndexColumn()
                           ->addColumn('BC_11', function($row){
                            return $row->master->BC_11;
                           })
                           ->addColumn('NO_PLP', function($row){
                            return "NO PLP";
                           })
                           ->addColumn('TGL_PLP', function($row){
                            return "TGL PLP";
                           })
                           ->addColumn('NO_SEGEL', function($row){
                            return $row->master->NO_SEGEL;
                           })
                           ->addColumn('UR_BRG', function($row){
                            $brg = '';
                            $count = $row->details->count();

                            if($count > 0){
                              foreach ($row->details as $key => $detail) {
                                $brg .= $detail->UR_BRG;
                                (($key + 1) < $count) ? $brg .= ', ' : '';
                              }
                            }

                            return $brg;
                           })
                           ->addColumn('Status', function($row){
                            $dateIn = Carbon::parse($row->SCAN_IN_DATE);

                            return ($dateIn->diffInDays(today(), false) > 30) 
                                      ? 'Abandon' : 'Current Now';
                           })
                           ->addColumn('Keterangan', function($row){
                            return 'Keterangan';
                           })
                           ->toJson();
        }

        $items = collect([
          'id' => 'id',
          'NM_PEMBERITAHU' => 'Nama Pemberitahu',
          'BC_11' => 'Nomor BC 11',
          'TGL_BC11' => 'Tanggal BC 11',
          'NO_POS_BC11' => 'Pos',
          'NO_FLIGHT' => 'Sarana Pengangkut',
          'NO_PLP' => 'Nomor PLP',
          'TGL_PLP' => 'Tanggal PLP',
          'NO_SEGEL' => 'Segel',
          'JML_BRG' => 'Jumlah Koli',
          'BRUTO' => 'Bruto',
          'NO_MASTER_BLAWB' => 'MAWB',
          'NO_HOUSE_BLAWB' => 'HAWB',
          'UR_BRG' => 'Uraian Barang',
          'NM_PENERIMA' => 'Consignee',
          'AL_PENERIMA' => 'Alamat',
          'NO_SPPB' => 'Nomor SPPB',
          'TGL_SPPB' => 'Tanggal SPPB',
          'Status' => 'Status',
          'SCAN_IN_DATE' => 'Tanggal dan Waktu Masuk TPS',
          'Keterangan' => 'Keterangan',
        ]);

        return view('pages.beacukai.inventory', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
