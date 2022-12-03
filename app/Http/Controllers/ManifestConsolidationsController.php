<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Models\Master;
use App\Models\House;
use DataTables;
use Auth;
use DB;

class ManifestConsolidationsController extends Controller
{
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
                            $url = url()->current().'/'.Crypt::encrypt($row->id).'/edit#tab-summary-content';

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
              $this->createHouse($master, $master->HAWBCount);
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
    public function edit(Master $consolidation)
    {
        $item = $consolidation->load(['houses']);

        return view('pages.manifest.consolidations.create-edit', compact(['item']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Master $consolidation)
    {
        $data = $this->validatedData();

        if($data){
          DB::beginTransaction();

          try {
            $consolidation->update($data);

            DB::commit();

            $consolidation->NPWP = $consolidation->branch->company->GC_TaxID;
            $consolidation->NM_PEMBERITAHU = $consolidation->branch->company->GC_Name;
            $consolidation->save();

            DB::commit();

            if($consolidation->HAWBCount > $consolidation->houses->count() ){
              $count = $consolidation->HAWBCount - $consolidation->houses->count();

              $this->createHouse($consolidation, $count);
            }

            createLog('App\Models\Master', $consolidation->id, 'Update Condolidation');

            DB::commit();

            return redirect('/manifest/consolidations/'.Crypt::encrypt($consolidation->id).'/edit')->with('sukses', 'Update Consolidation success.');

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

    public function createHouse(Master $master, $count)
    {
      for ($i=1; $i <= $count ; $i++) { 

        try {

          $house = House::create([
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
            'NO_SUBPOS_BC11' => str_pad($i, 3, 0, STR_PAD_LEFT),
            'NO_SUBSUBPOS_BC11' => 0000,
            'NO_MASTER_BLAWB' => $master->MAWBNumber,
            'TGL_MASTER_BLAWB' => $master->MAWBDate,
            'KD_NEG_PENGIRIM' => $master->unlocoOrigin->RL_RN_NKCountryCode,
            'NM_PEMBERITAHU' => $master->NM_PEMBERITAHU,
            'TGL_TIBA' => $master->ArrivalDate,
            'JAM_TIBA' => $master->ArrivalTime,
            'KD_PEL_TRANSIT' => $master->Transit,
            'KD_PEL_AKHIR' => $master->Destination
          ]);
  
          DB::commit();
  
          createLog('App\Models\House', $house->id, 'Create House');
  
          DB::commit();

        } catch (\Throwable $th) {
          throw $th;
        }
        
      }
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
