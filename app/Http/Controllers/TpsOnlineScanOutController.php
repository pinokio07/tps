<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\House;
use Crypt, Str, DB;

class TpsOnlineScanOutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $item = new House;
        $type = 'out';

        return view('pages.tpsonline.scan', compact(['item', 'type']));
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
          'NO_HOUSE_BLAWB' => 'required'
        ]);

        if($data){
          $house = House::where('NO_HOUSE_BLAWB', $data['NO_HOUSE_BLAWB'])
                        ->first();

          if(!$house){
            return redirect()->route('tps-online.scan-out')
                            ->with('gagal', 'House Number not Found!.');
          }

          if($house->SCAN_OUT_DATE){
            return redirect()->route('tps-online.scan-out.show', [
                                      'scan_out' => Crypt::encrypt($house->id)
                                    ])
                            ->with('gagal', 'House was already Scanned.');
          }

          DB::beginTransaction();

          try {

            $now = now();

            $house->update([
              'SCAN_OUT_DATE' => $now,
              'SCAN_OUT' => 'Y',
              'ExitDate' => $now->format('Y-m-d'),
              'ExitTime' => $now->format('H:i:s'),
            ]);

            DB::commit();

            createLog('App\Models\House', $house->id, 'SCAN OUT');

            DB::commit();

            return redirect()->route('tps-online.scan-out.show', [
                            'scan_out' => Crypt::encrypt($house->id)
                          ])
                          ->with('sukses', 'Scan Out Success.');

          } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->route('tps-online.scan-out')
                            ->with('gagal', $th->getMessage());
          }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(House $scan_out)
    {
        $item = $scan_out;
        $type = 'out';

        return view('pages.tpsonline.scan', compact(['item', 'type']));
    }
}
