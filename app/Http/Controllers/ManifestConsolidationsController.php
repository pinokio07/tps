<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Master;
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
                            $url = url()->current().'/'.$row->id.'/edit#tab-summary-content';

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

            $master->logs()->create([
              'user_id' => Auth::id(),
              'keterangan' => 'Create Consolidations',
            ]);

            DB::commit();

            return redirect('/manifest/consolidations/'.$master->id.'/edit')->with('sukses', 'Create Consolidation success.');

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

            $consolidation->logs()->create([
              'user_id' => Auth::id(),
              'keterangan' => 'Update Consolidations',
            ]);

            DB::commit();

            return redirect('/manifest/consolidations/'.$consolidation->id.'/edit')->with('sukses', 'Update Consolidation success.');

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
        'PUDate' => 'required|date',
        'OriginWarehouse' => 'nullable',
        'MasukGudang' => 'exclude',
        'NO_SEGEL' => 'required',
      ]);
    }
}
