<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\House;
use App\Models\HouseDetail;
use DB, Auth, Crypt, DataTables;

class ManifestHouseDetailsController extends Controller
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
          $query = HouseDetail::where('HouseID', $request->id);

          return DataTables::eloquent($query)
                           ->addIndexColumn()
                           ->addColumn('actions', function($row){
                            $btn = '<button type="button"
                                            data-toggle="modal"
                                            data-target="#modal-item"
                                            class="btn btn-xs btn-warning elevation-2 mr-1 editDetail"
                                            data-house="'.Crypt::encrypt($row->HouseID).'"
                                            data-id="'.$row->id.'"
                                            data-hs="'.$row->HS_CODE.'"
                                            data-desc="'.$row->UR_BRG.'"
                                            data-cif="'.$row->CIF.'"
                                            data-fob="'.$row->FOB.'"
                                            data-bm="'.$row->BM_TRF.'"
                                            data-ppn="'.$row->PPN_TRF.'"
                                            data-pph="'.$row->PPH_TRF.'">
                                      <i class="fas fa-edit"></i>
                                    </button>';
                            $btn .= '<button class="btn btn-xs btn-danger elevation-2 hapusDetail"
                                        data-href="'. route('house-details.destroy', ['house_detail' => $row->id]) .'">
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
        $data = $this->getValidated();

        if($data){
          DB::beginTransaction();
          
          $house = House::findOrFail(Crypt::decrypt($request->house_id));

          try {
            // $data->merge(['HouseID' => $house->id]);
            $hasil = array_merge($data, ['HouseID' => $house->id]);
            $house_detail = HouseDetail::create($hasil);
            // $house->details()->create($data);

            DB::commit();

            createLog('App\Models\HouseDetail', $house_detail->id, 'Create House Detail');

            DB::commit();

            return response()->json([
              'status' => 'OK',
              'house' => $house->id,
              'message' => 'Create House Item Success.'
            ]);

          } catch (\Throwable $th) {
            DB::rollback();

            return response()->json(['status' => 'FAILED', 'message' => $th->getMessage()]);
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
    public function update(Request $request, HouseDetail $house_detail)
    {
        $data = $this->getValidated();

        if($data){
          DB::beginTransaction();
          
          try {
            $house_detail->update($data);

            DB::commit();

            createLog('App\Models\HouseDetail', $house_detail->id, 'Update House Detail');

            DB::commit();

            return response()->json([
              'status' => 'OK',
              'house' => $house_detail->HouseID,
              'message' => 'Update House Item Success.'
            ]);

          } catch (\Throwable $th) {
            DB::rollback();

            return response()->json(['status' => 'FAILED', 'message' => $th->getMessage()]);
          }
          

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(HouseDetail $house_detail)
    {
        if(!Auth::user()->can('edit_manifest_consolidations|edit_manifest_shipments')){
          return abort(403);
        }

        DB::beginTransaction();

        try {
          $house = $house_detail->HouseID;
          $hid = $house_detail->id;

          $house_detail->delete();

          createLog('App\Models\HouseDetail', $hid, 'Delete House Item');

          DB::commit();

          // return redirect('/manifest/'.$this->group.'/'.Crypt::encrypt($master).'/edit#tab-houses-content')->with('sukses', 'Delete House Success.');
          return response()->json(['status' => 'OK', 'house' => $house]);

        } catch (\Throwable $th) {
          DB::rollback();

          return response()->json(['status' => 'FAILED', 'message' => $th->getMessage()]);
          // throw $th;
        }
    }

    public function getValidated()
    {
      return request()->validate([
        'HS_CODE' => 'required',
        'UR_BRG' => 'nullable',
        'CIF' => 'nullable|numeric',
        'FOB' => 'nullable|numeric',
        'BM_TRF' => 'nullable|numeric',
        'PPN_TRF' => 'nullable|numeric',
        'PPH_TRF' => 'nullable|numeric',
      ]);
    }
}
