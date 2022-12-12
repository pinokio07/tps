<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\House;
use App\Models\Master;
use App\Models\Tariff;
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
                              $btn .= '<button class="btn btn-xs bg-fuchsia elevation-2 mr-1 calculate"
                                              data-toggle="tooltip"
                                              data-target="collapseCalculate"
                                              title="Calculate"
                                              data-id="'.Crypt::encrypt($row->id).'"
                                              data-code="'.$row->NO_HOUSE_BLAWB.'">
                                        <i class="fas fa-calculator"></i>
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

            DB::commit();

            if(!empty($house->getChanges())){
              $info = 'Update House '.$house->mawb_parse.' <br> <ul>';

              foreach ($house->getChanges() as $key => $value) {
                if($key != 'updated_at'){
                  $info .= '<li> Update ' . $key . ' to ' . $value .'</li>';
                }                
              }

              $info .= '</ul>';

              createLog('App\Models\House', $house->id, $info);

              DB::commit();
            }

            $house->refresh();

            if($request->ajax()){
              return response()->json(['status' => 'OK', 'house' => $house->NO_HOUSE_BLAWB]);
            }
            
            return redirect('/manifest/shipments/'.Crypt::encrypt($house->id).'/edit')->with('sukses', 'Update House Success.');

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
          $mawb = $house->mawb_parse;

          $house->delete();

          createLog('App\Models\House', $hid, 'Delete House '.$mawb);

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

    public function calculate(Request $request, House $house)
    {
      $data = $request->validate([
        'cal_tariff' => 'required|numeric',
        'cal_days' => 'required|numeric'
      ]);

      if($data){
        $tariff = Tariff::with(['schema'])->findOrFail($data['cal_tariff']);
        $totalCharge = 0;
        $subTotal = 0;
        $days = $data['cal_days'];

        $output = '';

        $charges = $tariff->schema->where('is_fixed', false)
                                  ->where('column', 'ChargeableWeight')
                                  ->sortBy('urut');
        $others = $tariff->schema->whereNotIn('id', $charges->pluck('id')->toArray())
                                ->sortBy('urut');

        foreach ($charges as $charge) {
          $column = $charge->column;

          if($charge->as_one == true){
            $days -= $charge->days;
            $countDays = 1;            
          } else if($charge->days > 0){
            $countDays = ( ($days - $charge->days) > 0 ) ? $charge->days : $days;
            $days -= $countDays;
          } else {
            $countDays = $days;
          }
          ${'charge_'.$charge->id} = $charge->rate * ($house->$column ?? 0) * $countDays;

          $output .= '<tr>'
                      .'<td>'.$charge->name.'</td>'
                      .'<td>'.$countDays.'</td>'
                      .'<td>'.number_format(($house->$column ?? 0), 2, ',','.').'</td>'
                      .'<td class="text-right">'.number_format($charge->rate, 2, ',','.').'</td>'                      
                      .'<td class="text-right">'.number_format((${'charge_'.$charge->id} ?? 0), 2, ',','.').'</td>';

          $totalCharge += ${'charge_'.$charge->id};
        }        
        $output .= '<tr>'
                    .'<td>Minimum Charge</td>'
                    .'<td></td>'
                    .'<td></td>'
                    .'<td></td>'
                    .'<td class="text-right">'.number_format($tariff->minimum, 2, ',', '.').'</td>'
                    .'</tr>';

        if($totalCharge < $tariff->minimum){
          $totalCharge = $tariff->minimum;
        }
        
        $subTotal += $totalCharge;

        foreach ($others as $other ) {
          if($other->is_fixed == true){
            ${'other_'.$other->id} = $other->rate;
          } else if($other->column == 'CDC'){
            $chPU = $others->where('name', 'Charge PU')->first()->rate ?? 0;
            $dcFee = $others->filter(function($df){
                            return str_contains($df->name, 'Admin');
                          })->first()->rate ?? 0;
            ${'other_'.$other->id} = $other->rate * ($totalCharge + $chPU + $dcFee);
          } else if($other->column == 'CHARGE'){
            ${'other_'.$other->id} = $other->rate * $totalCharge;
          } else {
            $column = $other->column;
            ${'other_'.$other->id} = $other->rate * $house->$column;
          }
          
          if($other->rate < 1){
            $rateShow = ($other->rate * 100) . ' %';
          } else {
            $rateShow = number_format($other->rate, 2, ',', '.');
          }

          $output .= '<tr>'
                      .'<td>'.$other->name.'</td>'
                      .'<td></td>'                      
                      .'<td></td>'
                      .'<td class="text-right">'.$rateShow.'</td>'
                      .'<td class="text-right">'.number_format(${'other_'.$other->id}, 2, ',','.').'</td>'
                      .'</tr>';

          $subTotal += ${'other_'.$other->id};
        }

        $output .= '<tr>'
                    .'<td colspan="4" class="text-right"><b>Sub Total</b></td>'
                    .'<td class="text-right"><b>'.number_format($subTotal, 2, ',', '.').'</b></td>'
                    .'</tr>';

        if($tariff->vat){
          $vat = $subTotal * ($tariff->vat / 100);
          $output .= '<tr>'
                      .'<td colspan="4" class="text-right">VAT '.$tariff->vat.' %</td>'
                      .'<td class="text-right"><b>'.number_format(round($vat), 2, ',', '.').'</b></td>'
                      .'</tr>';
        }

        $output .= '<tr>'
                    .'<td colspan="4" class="text-right"><b>TOTAL</b></td>'
                    .'<td class="text-right"><b>'.number_format(($subTotal + ($vat ?? 0)), 2, ',', '.').'</b></td>'
                    .'</tr>';
       

        echo $output;
        
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
        'ChargeableWeight' => 'required|numeric',
        'FOB' => 'nullable|numeric',
        'FREIGHT' => 'nullable|numeric',
        'VOLUME' => 'nullable|numeric',
        'ASURANSI' => 'nullable|numeric',
        'JML_BRG' => 'nullable|numeric',
        'JNS_KMS' => 'nullable',
        'MARKING' => 'nullable',
        'tariff_id' => 'nullable|numeric',
        'NPWP_BILLING' => 'nullable',
        'NAMA_BILLING' => 'nullable',
        'NO_INVOICE' => 'nullable',
        'TGL_INVOICE' => 'nullable|date',
        'TOT_DIBAYAR' => 'nullable|numeric',
      ]);
    }
}
