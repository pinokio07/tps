<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\House;
use App\Models\HouseDetail;
use App\Models\Tariff;
use Carbon\Carbon;
use DataTables, Auth, Crypt, Str, DB;

class ManifestShipmentsController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
          $query = House::with(['master']);

          return DataTables::of($query)
                           ->addIndexColumn()
                           ->editColumn('NO_BARANG', function($row){
                            $btn = '<a href="'.route('manifest.shipments.show', ['shipment' => Crypt::encrypt($row->id)]).'">'.$row->NO_BARANG.'</a>';

                            return $btn;
                           })
                           ->addColumn('ArrivalDate', function($row){
                              if($row->master->ArrivalDate){
                                $time = Carbon::parse($row->master->ArrivalDate);
                                $display = $time->format('d/m/Y');
                                $timestamp = $time->timestamp;
                              } else {
                                $display = "-";
                                $timestamp = 0;
                              }

                              $show = [
                                'display' => $display,
                                'timestamp' => $timestamp
                              ];

                              return $show; 
                           })
                           ->editColumn('ExitDate', function($row){
                              if($row->ExitDate){
                                $time = Carbon::parse($row->ExitDate);
                                $display = $time->format('d/m/Y');
                                $timestamp = $time->timestamp;
                              } else {
                                $display = "-";
                                $timestamp = 0;
                              }

                              $show = [
                                'display' => $display,
                                'timestamp' => $timestamp
                              ];

                              return $show; 
                           })
                           ->addColumn('ArrivalTime', function($row){
                              return $row->master->ArrivalTime;
                           })
                           ->editColumn('NO_MASTER_BLAWB', function($row){
                            $first = '';
                            $second = '';
                            $third = '';

                            $num = str_replace(' ', '', $row->NO_MASTER_BLAWB);
                            if($num != ''){
                              $first = substr($num, 0, 3);
                              $second = substr($num, 3, 4);
                              $third = substr($num, 7, 4);
                            }

                            $show = [
                              'display' => $first .' '. $second .' '. $third,
                              'filter' => $row->NO_MASTER_BLAWB
                            ];

                            return $show;
                           })
                           ->rawColumns(['NO_BARANG', 'AL_PENERIMA'])
                           ->toJson();
        }

        $items = collect([
          'id' => 'id',
          'NM_PEMBERITAHU' => 'Nama Pemberitahu',
          'NO_MASTER_BLAWB' => 'No Master BLAWB',
          'NO_BARANG' => 'No Barang',
          'NM_PENERIMA' => 'Nama Penerima',
          'AL_PENERIMA' => 'Alamat Penerima',
          'ArrivalDate' => 'Tanggal Tiba',
          'ArrivalTime' => 'Jam Tiba',
          'ExitDate' => 'Exit Date',
          'ExitTime' => 'Exit Time',
        ]);

        return view('pages.manifest.shipments.index', compact(['items']));
    }
    
    public function create()
    {
        //
    }
    
    public function store(Request $request)
    {
        //
    }
    
    public function show(House $shipment)
    {
        $item = $shipment->load(['details']);
        $headerHouse = $this->headerHouse();
        $headerDetail = $this->headerHouseDetail();
        $tariff = Tariff::all();

        return view('pages.manifest.shipments.create-edit', compact(['item', 'headerHouse', 'headerDetail', 'tariff']));
    }
    
    public function edit(House $shipment)
    {
        $item = $shipment->load(['details']);
        $headerHouse = $this->headerHouse();
        $headerDetail = $this->headerHouseDetail();
        $tariff = Tariff::all();

        return view('pages.manifest.shipments.create-edit', compact(['item', 'headerHouse', 'headerDetail', 'tariff']));
    }
    
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function headerHouse()
    {
      $data = collect([
        'id' => 'id',
        'NO_HOUSE_BLAWB' => 'HAWB No',
        'X_Ray' => 'X-Ray Date',
        'NO_FLIGHT' => 'Flight No',
        'NO_BC11' => 'BC 1.1',
        'NO_POS_BC11' => 'POS BC 1.1',
        'NO_SUBPOS_BC11' => 'Sub POS BC 1.1',
        'NM_PENERIMA' => 'Consignee',
        'JML_BRG' => 'Total Items',
        'mGrossWeight' => 'Gross Weight',
        'TPS_GateInDateTime' => 'TPSO Gate In',
        'TPS_GateOutDateTime' => 'TPSO Gate Out',
        'BC_CODE' => 'KD Response',
        'BC_STATUS' => 'Keterangan',
        'actions' => 'Actions',
      ]);

      return $data;
    }

    public function headerHouseDetail()
    {
      $data = collect([
        'id' => 'id',
        'HS_CODE' => 'HS Code',
        'UR_BRG' => 'Description',
        'CIF' => 'CIF',
        'BM_TRF' => 'BM Trf',
        'PPN_TRF' => 'PPN Trf',
        'PPH_TRF' => 'PPH Trf',
        'BActualBM' => 'BM',
        'BActualPPN' => 'PPN',
        'BActualPPH' => 'PPH',
        'actions' => 'Actions',
      ]);

      return $data;
    }
}
