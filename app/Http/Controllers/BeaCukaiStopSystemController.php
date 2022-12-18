<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HouseTegah;
use App\Exports\TegahExport;
use DataTables, Crypt, Auth, Excel, PDF, DB;

class BeaCukaiStopSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
          $query = HouseTegah::where('is_tegah', true);

          return DataTables::eloquent($query)
                            ->addIndexColumn()
                            ->addColumn('actions', function($row){
                              $btn = '<button id="btnTegah_'.$row->id.'"
                                            data-toggle="modal"
                                            data-target="#modal-tegah"
                                            class="btn btn-xs btn-success elevation-2 tegah"
                                            data-id="'.$row->id.'">
                                            <i class="fas fa-lock-open"></i> Lepas</button>';

                              return $btn;
                            })
                            ->rawColumns(['actions'])
                            ->toJson();  
        }
        $items = collect([
          'id' => 'id',
          'MAWBNumber' => 'MAWB Number',
          'HAWBNumber' => 'MAWB Number',
          'Koli' => 'Koli',
          'Bruto' => 'Bruto',
          'Consignee' => 'Consignee',
          'AlasanTegah' => 'Alasan Tegah',
          'TanggalTegah' => 'Tanggal Tegah',
          'NamaPetugas' => 'Nama Petugas',
          'actions' => 'Action'
        ]);

        return view('pages.beacukai.stopsystem', compact(['items']));
    }

    public function update(Request $request, HouseTegah $stop_system)
    {
        $data = $request->validate([
          'AlasanLepasTegah' => 'required'
        ]);

        if($data){
          $user = Auth::user();

          DB::beginTransaction();
          
          try {

            $stop_system->update([
              'TanggalLepasTegah' => now(),
              'AlasanLepasTegah' => $request->AlasanLepasTegah,
              'PetugasLepasTegah' => $user->name,
              'is_tegah' => false,
            ]);

            createLog('App\Models\House', $stop_system->house_id, 'Lepas Tegah by '.$user->name.', reason: "'.$request->AlasanLepasTegah.'"');

            DB::commit();

            if($request->ajax()){
              return response()->json([
                'status' => 'OK',
                'message' => 'Lepas Tegah Success.'
              ]);
            }

            return redirect('/bea-cukai/stop-system')->with('sukses', 'Lepas Tegah Success.');
          } catch (\Throwable $th) {

            DB::rollback();

            if($request->ajax()){
              return response()->json([
                'status' => 'ERROR',
                'message' => $th->getMessage()
              ]);
            }

            throw $th;
          }
        }
    }

    public function download(Request $request)
    {
      if($request->jenis == 'xls'){

        return Excel::download(new TegahExport(), 'tegah-'.today()->format('d-m-Y').'.xlsx');

      } elseif($request->jenis == 'pdf'){

        $items = HouseTegah::with(['house.master.warehouseLine1'])
                            ->where('is_tegah', true)
                            ->get();
        $company = activeCompany();
        $jenis = 'pdf';

        $pdf = PDF::setOptions([
          'enable_php' => true,
        ]);

        $pdf->loadView('exports.tegah', compact(['items', 'company', 'jenis']));

        return $pdf->setPaper('LEGAL', 'landscape')->stream();
      }
      
    }

}
