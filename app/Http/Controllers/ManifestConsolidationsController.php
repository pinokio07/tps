<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Models\Master;
use App\Models\House;
use DataTables, Auth, DB, Arr;

class ManifestConsolidationsController extends Controller
{
    public function __construct()
    {
      $this->path = Request::capture()->path();
      $this->group = strtolower(explode("/", $this->path)[1]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
          $query = Master::query();

          return DataTables::eloquent($query)
                           ->addIndexColumn()
                           ->editColumn('AirlineCode', function($row){
                            $url = url()->current().'/'.Crypt::encrypt($row->id).'#tab-summary-content';

                            $show = '<a href="'.$url.'">'.$row->AirlineCode.'</a>';

                            return $show;
                           })
                           ->editColumn('ArrivalDate', function($row){
                            if($row->ArrivalDate){
                              $time = Carbon::parse($row->ArrivalDate);
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
                           ->rawColumns(['AirlineCode'])
                           ->toJson();
        }

        $items = collect([
          'id' => 'id',
          'AirlineCode' => 'Airline Code',
          'MAWBNumber' => 'MAWB Number',
          'ArrivalDate' => 'Arrival Date',
          'MasukGudang' => 'Masuk Gudang',
          'PUNumber' => 'PU Number',
          'mNoOfPackages' => 'Total Collie',
          'mGrossWeight' => 'Gross Weight',
          'HAWBCount' => 'HAWB Count',
        ]);

        return view('pages.manifest.consolidations.index', compact(['items']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new Master;

        return view('pages.manifest.consolidations.create-edit', compact(['item']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validatedData();

        if($data){
          DB::beginTransaction();

          try {            
            $master = Master::create($data);

            DB::commit();

            $master->NPWP = $master->branch->company->GC_TaxID;
            $master->NM_PEMBERITAHU = $master->branch->company->GC_Name;
            $master->save();

            DB::commit();

            createLog('App\Models\Master', $master->id, 'Create Condolidation');

            DB::commit();

            if($master->HAWBCount > 0){              
              for ($i = 1; $i <= $master->HAWBCount; $i++) { 
                $data = $this->getHouse($master, $i);

                $house = House::create($data);

                DB::commit();

                createLog('App\Models\House', $house->id, 'Create House');

                DB::commit();

              }
            }

            DB::commit();

            return redirect('/manifest/consolidations/'.Crypt::encrypt($master->id).'/edit')->with('sukses', 'Create Consolidation success.');

          } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
          }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Master $master)
    {
        $item = $master->load(['houses']);
        $disabled = 'disabled';
        $headerHouse = $this->headerHouse();
        $headerDetail = $this->headerHouseDetail();

        return view('pages.manifest.consolidations.create-edit', compact(['item', 'disabled', 'headerHouse', 'headerDetail']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Master $master)
    {
        $item = $master->load(['houses']);
        $disabled = false;
        $headerHouse = $this->headerHouse();
        $headerDetail = $this->headerHouseDetail();

        return view('pages.manifest.consolidations.create-edit', compact(['item', 'disabled', 'headerHouse', 'headerDetail']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Master $master)
    {        
        $data = $this->validatedData();

        if($data){
          DB::beginTransaction();

          try {
            $master->update($data);

            DB::commit();

            $master->NPWP = $master->branch->company->GC_TaxID;
            $master->NM_PEMBERITAHU = $master->branch->company->GC_Name;
            $master->save();

            $master->refresh();

            DB::commit();

            for ($i = 1; $i <= $master->HAWBCount; $i++) { 
              $data = $this->getHouse($master, $i);
              $updated = Arr::except($data, ['MasterID', 'NO_SUBPOS_BC11']);

              $house = House::updateOrCreate([
                  'MasterID' => $master->id,
                  'NO_SUBPOS_BC11' => $data['NO_SUBPOS_BC11'],
                ], $updated );

              DB::commit();

              if($house->wasRecentlyCreated){
                $info = 'Create House';
              } else {
                $info = 'Update House';
              }

              createLog('App\Models\House', $house->id, $info);
              
              DB::commit();

            }

            createLog('App\Models\Master', $master->id, 'Update Consolidation');

            DB::commit();

            return redirect('/manifest/'.$this->group.'/'.Crypt::encrypt($master->id).'/edit')->with('sukses', 'Update Consolidation success.');

          } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
          }
        }
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

    public function getHouse(Master $master, $count)
    {
      $data = [        
        'MasterID' => $master->id,
        'KD_KANTOR' => $master->KPBC,
        'NM_PENGANGKUT' => $master->NM_SARANA_ANGKUT,
        'NO_FLIGHT' => $master->FlightNo,
        'KD_PEL_MUAT' => $master->Origin,
        'KD_PEL_BONGKAR' => $master->Destination,
        'KD_GUDANG' => $master->OriginWarehouse,
        'KD_NEGARA_ASAL' => $master->unlocoOrigin->RL_RN_NKCountryCode,
        'JML_BRG' => $master->mNoOfPackages,
        'NO_BC11' => $master->PUNumber,
        'TGL_BC11' => $master->PUDate,
        'NO_POS_BC11' => $master->POSNumber,
        'NO_SUBPOS_BC11' => str_pad($count, 4, 0, STR_PAD_LEFT),
        'NO_SUBSUBPOS_BC11' => 0000,
        'NO_MASTER_BLAWB' => $master->MAWBNumber,
        'TGL_MASTER_BLAWB' => $master->MAWBDate,
        'KD_NEG_PENGIRIM' => $master->unlocoOrigin->RL_RN_NKCountryCode,
        'NO_ID_PEMBERITAHU' => $master->NPWP,
        'NM_PEMBERITAHU' => $master->NM_PEMBERITAHU,
        'AL_PEMBERITAHU' => $master->branch->CB_Address,
        'TGL_TIBA' => $master->ArrivalDate,
        'JAM_TIBA' => $master->ArrivalTime,
        'KD_PEL_TRANSIT' => $master->Transit,
        'KD_PEL_AKHIR' => $master->Destination,
        'BRANCH' => $master->mBRANCH,
        'PART_SHIPMENT' => $master->Partial,
      ];

      return $data;
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

    public function validatedData()
    {
      return request()->validate([
        'KPBC' => 'required',
        'mBRANCH' => 'required',
        'NPWP' => 'exclude',
        'AirlineCode' => 'required',
        'NM_SARANA_ANGKUT' => 'required',
        'FlightNo' => 'required',
        'ArrivalDate' => 'required|date',
        'ArrivalTime' => 'required',
        'Origin' => 'required',
        'Transit' => 'nullable',
        'Destination' => 'required',
        'ShipmentNumber' => 'nullable',
        'MAWBNumber' => 'required|numeric',
        'MAWBDate' => 'required|date',
        'HAWBCount' => 'required|numeric',
        'mNoOfPackages' => 'nullable|numeric',
        'mGrossWeight' => 'nullable|numeric',
        'mChargeableWeight' => 'nullable|numeric',
        'Partial' => 'nullable',
        'PUNumber' => 'nullable',
        'POSNumber' => 'nullable',
        'PUDate' => 'nullable|date',
        'OriginWarehouse' => 'nullable',
        'MasukGudang' => 'exclude',
        'NO_SEGEL' => 'required',
      ]);
    }
}
