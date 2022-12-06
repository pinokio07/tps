<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\House;
use App\Models\Master;
use DB, DataTables, Crypt, Auth;

class ManifestHousesController extends Controller
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
          $query = House::where('MasterID', $request->id);

          return DataTables::of($query)
                            ->addIndexColumn()
                            ->addColumn('X_Ray', function($row){
                              return "X-Ray Date";
                            })
                            ->addColumn('mGrossWeight', function($row){
                              return $row->master->mGrossWeight;
                            })
                            ->addColumn('actions', function($row){

                              $btn = '<button class="btn btn-xs btn-warning elevation-2 mr-1 edit"
                                              data-toggle="tooltip"
                                              data-target="collapseHouse"
                                              title="Edit"
                                              data-id="'.$row->id.'">
                                        <i class="fas fa-edit"></i>
                                      </button>';

                              $btn .= '<button class="btn btn-xs btn-info elevation-2 mr-1 codes"
                                              data-toggle="tooltip"
                                              data-target="collapseHSCodes"
                                              title="HS Codes"
                                              data-id="'.$row->id.'"
                                              data-house="'.Crypt::encrypt($row->id).'"
                                              data-code="'.$row->NO_HOUSE_BLAWB.'">
                                        <i class="fas fa-clipboard-list"></i>
                                      </button>';
                              $btn .= '<button class="btn btn-xs btn-success elevation-2 mr-1 response"
                                              data-toggle="tooltip"
                                              data-target="collapseResponse"
                                              title="Response"
                                              data-id="'.$row->id.'"
                                              data-code="'.$row->NO_HOUSE_BLAWB.'">
                                        <i class="fas fa-sync"></i>
                                      </button>';
                              $btn .= '<button class="btn btn-xs btn-danger elevation-2 hapusHouse"
                                              data-href="'. route('houses.destroy', ['house' => $row->id]) .'">
                                        <i class="fas fa-trash"></i>
                                      </button>';

                              return $btn;
                            })
                            ->rawColumns(['actions'])
                            ->toJson();
        }
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
    public function show(House $house)
    {
        $house->load(['details']);       
        
        return response()->json($house);
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
    public function update(Request $request, House $house)
    {
        if(!Auth::user()->can('edit_manifest_consolidations|edit_manifest_shipments')){
          return response()->json(['status' => 'Failed', 'message' => 'You are not authorized to edit this data.']);
        }
        $data = $this->validatedHouse();

        if($data){
          DB::beginTransaction();

          try {
            $house->update($data);

            createLog('App\Models\House', $house->id, 'Updated House');

            DB::commit();

            return response()->json(['status' => 'OK']);

          } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 'Failed', 'message' => $th->getMessage()]);
          }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(House $house)
    {
        if(!Auth::user()->can('edit_manifest_consolidations|edit_manifest_shipments')){
          return abort(403);
        }
        DB::beginTransaction();

        try {
          $master = $house->MasterID;
          $hid = $house->id;

          $house->delete();

          createLog('App\Models\House', $hid, 'Delete House');

          DB::commit();

          // return redirect('/manifest/'.$this->group.'/'.Crypt::encrypt($master).'/edit#tab-houses-content')->with('sukses', 'Delete House Success.');

          return response()->json(['status' => "OK"]);

        } catch (\Throwable $th) {
          DB::rollback();

          return response()->json(['status' => 'FAILED', 'message' => $th->getMessage()]);
          // throw $th;
        }
    }

    public function validatedHouse()
    {
      return request()->validate([
        'JNS_AJU' => 'required|numeric',
        'KD_JNS_PIBK' => 'required|numeric',
        'SPPBNumber' => 'nullable',
        'SPPBDate' => 'nullable|date',
        'BCF15_Status' => 'nullable',
        'BCF15_Number' => 'nullable',
        'BCF15_Date' => 'nullable|date',
        'NO_HOUSE_BLAWB' => 'required',
        'TGL_HOUSE_BLAWB' => 'required|date',
        'NM_PENGIRIM' => 'required',
        'AL_PENGIRIM' => 'required',
        'NM_PENERIMA' => 'required',
        'AL_PENERIMA' => 'required',
        'NO_ID_PENERIMA' => 'nullable',
        'JNS_ID_PENERIMA' => 'nullable|numeric',
        'TELP_PENERIMA' => 'nullable',
        'NETTO' => 'nullable|numeric',
        'BRUTO' => 'nullable|numeric',
        'FOB' => 'nullable|numeric',
        'FREIGHT' => 'nullable|numeric',
        'VOLUME' => 'nullable|numeric',
        'ASURANSI' => 'nullable|numeric',
        'JML_BRG' => 'nullable|numeric',
        'JNS_KMS' => 'nullable',
        'MARKING' => 'nullable',
        'NPWP_BILLING' => 'nullable',
        'NAMA_BILLING' => 'nullable',
        'NO_INVOICE' => 'nullable',
        'TGL_INVOICE' => 'nullable|date',
        'TOT_DIBAYAR' => 'nullable|numeric',
      ]);
    }
}
