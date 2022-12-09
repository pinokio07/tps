<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\House;
use App\Models\Master;
use DB, DataTables, Crypt, Auth;

class ManifestHousesController extends Controller
{        
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
                                              data-id="'.Crypt::encrypt($row->id).'">
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
                                              data-id="'.Crypt::encrypt($row->id).'"
                                              data-code="'.$row->NO_HOUSE_BLAWB.'">
                                        <i class="fas fa-sync"></i>
                                      </button>';
                              $btn .= '<button class="btn btn-xs btn-danger elevation-2 hapusHouse"
                                              data-href="'. route('houses.destroy', ['house' => Crypt::encrypt($row->id)]) .'">
                                        <i class="fas fa-trash"></i>
                                      </button>';

                              return $btn;
                            })
                            ->rawColumns(['actions'])
                            ->toJson();
        }
    }
    
    public function show(House $house)
    {
        $house->load(['details']);       
        
        return response()->json($house);
    }
    
    public function update(Request $request, House $house)
    {
        if(Auth::user()->cannot('edit_manifest_consolidations') 
            && Auth::user()->cannot('edit_manifest_shipments')){
          if($request->ajax()){
            return response()->json(['status' => 'Failed', 'message' => 'You are not authorized to edit this data.']);
          }
          return abort(403);
        }
        $data = $this->validatedHouse();

        if($data){
          DB::beginTransaction();

          try {
            $hasil = array_merge($data, ['NO_BARANG' => $data['NO_HOUSE_BLAWB']]);

            $house->update($hasil);

            createLog('App\Models\House', $house->id, 'Updated House');

            DB::commit();

            $house->refresh();

            if($request->ajax()){
              return response()->json(['status' => 'OK', 'house' => $house->NO_HOUSE_BLAWB]);
            }
            
            return redirect(url()->previous().'/edit')->with('sukses', 'Update House Success.');

          } catch (\Throwable $th) {
            DB::rollback();
            if($request->ajax()){
              return response()->json(['status' => 'Failed', 'message' => $th->getMessage()]);
            }
            throw $th;
          }
        }
    }
    
    public function destroy(House $house)
    {
        if(Auth::user()->cannot('edit_manifest_consolidations') 
            && Auth::user()->cannot('edit_manifest_shipments')){
          if($request->ajax()){
            return response()->json(['status' => 'Failed', 'message' => 'You are not authorized to edit this data.']);
          }
          return abort(403);
        }
        DB::beginTransaction();

        try {
          $master = $house->MasterID;
          $hid = $house->id;

          $house->delete();

          createLog('App\Models\House', $hid, 'Delete House');

          DB::commit();

          if($request->ajax()){
            return response()->json(['status' => "OK"]);
          }          
          
        } catch (\Throwable $th) {
          DB::rollback();

          if($request->ajax()){
            return response()->json(['status' => 'FAILED', 'message' => $th->getMessage()]);
          }
          
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
