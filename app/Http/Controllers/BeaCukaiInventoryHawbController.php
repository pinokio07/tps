<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master;
use App\Models\House;
use App\Exports\InventoryDetailExport;
use Carbon\Carbon;
use DataTables, Crypt, Excel, PDF;

class BeaCukaiInventoryHawbController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
          $query = House::query();

          if($request->from
              && $request->to){
            $start = Carbon::createFromFormat('d-m-Y', $request->from);
            $end = Carbon::createFromFormat('d-m-Y', $request->to);

            $query->whereBetween('SCAN_IN_DATE', [
                        $start->startOfDay(),
                        $end->endOfDay()
                      ]);
          }

          return DataTables::eloquent($query)
                            ->addIndexColumn()
                            ->addColumn('NO_PLP', function($row){
                              return "NO PLP";
                            })
                            ->addColumn('TGL_PLP', function($row){
                              return "TGL PLP";
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
                            ->toJson();
        }
        $items = collect([
          'id' => 'id',
          'NO_BC11' => 'No BC 11',
          'TGL_BC11' => 'Tgl BC 11',
          'NO_POS_BC11' => 'Pos BC',
          'NO_PLP' => 'Nomor PLP',
          'TGL_PLP' => 'Tanggal PLP',
          'JML_BRG' => 'Jumlah Koli',
          'BRUTO' => 'Bruto',
          'NO_MASTER_BLAWB' => 'MAWB',
          'NO_HOUSE_BLAWB' => 'HAWB',
          'LM_TRACKING' => 'LM Tracking',
          'UR_BRG' => 'Uraian Barang',
          'NM_PENERIMA' => 'Consignee',
          'AL_PENERIMA' => 'Alamat',
          'SCAN_IN_DATE' => 'Masuk',
          'SCAN_OUT_DATE' => 'Keluar',
        ]);

        return view('pages.beacukai.viewinventory', compact(['items']));
    }

    public function download(Request $request)
    {
      $query = House::query();
      $jenis = $request->jenis ?? 'pdf';
      $mawb = $request->mawb ?? '';

      if($mawb != ''){

        $query->where('MasterID', $request->mawb);

        $start = today();
        $end = $start;

      } else {

        if($request->from
            && $request->to){

          $start = Carbon::createFromFormat('d-m-Y', $request->from);
          $end = Carbon::createFromFormat('d-m-Y', $request->to);

          $query->whereBetween('SCAN_IN_DATE', [
                      $start->startOfDay(),
                      $end->endOfDay()
                    ]);

        }

      }

      $items = $query->get();

      if($jenis == 'xls'){

        return Excel::download(new InventoryDetailExport($items, $mawb, $start, $end), 'inventory-'.today()->format('d-m-Y').'.xlsx');

      } else{

        $company = activeCompany();

        $pdf = PDF::setOptions([
          'enable_php' => true,
        ]);

        $pdf->loadView('exports.inventorydetail', compact(['items', 'company', 'jenis', 'mawb', 'start', 'end']));

        return $pdf->setPaper('LEGAL', 'landscape')->stream();
        
      }
    }
}
