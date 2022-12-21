<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\House;
use Carbon\Carbon;
use Crypt, Str, DB;

class TpsOnlineScanInController extends Controller
{    
    public function index()
    {
        $item = new House;
        $type = 'in';

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
            return redirect()->route('tps-online.scan-in')
                             ->with('gagal', 'House Number not Found!.');
          }

          if($house->SCAN_IN_DATE){
            return redirect()->route('tps-online.scan-in.show', [
                                      'scan_in' => Crypt::encrypt($house->id)
                                    ])
                            ->with('gagal', 'House was already Scanned.');
          }

          if(!$house->ShipmentNumber){
            return redirect()->route('tps-online.scan-in.show', [
                                      'scan_in' => Crypt::encrypt($house->id)
                                    ])
                            ->with('gagal', 'Shipment number is Empty.');
          }

          DB::beginTransaction();

          try {
            $now = now();
            $house->update([
              'SCAN_IN_DATE' => $now,
              'SCAN_IN' => 'Y'
            ]);

            DB::commit();

            createLog('App\Models\House', $house->id, 'SCAN IN');

            $giwi = $this->createXML($house, $now->setTimeZone('UTC'));

            $house->update([
              'CW_Ref_GateIn' => $giwi
            ]);

            DB::commit();

            return redirect()->route('tps-online.scan-in.show', [
                            'scan_in' => Crypt::encrypt($house->id)
                          ])
                          ->with('sukses', 'Scan In Success.');

          } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->route('tps-online.scan-in')
                             ->with('gagal', $th->getMessage());
          }
        }
    }

    public function show(House $scan_in)
    {
        $item = $scan_in;
        $type = 'in';

        return view('pages.tpsonline.scan', compact(['item', 'type']));
    }

    public function createXML(House $house, Carbon $time)
    {
      $giwiaTxt = '<UniversalEvent xmlns="http://www.cargowise.com/Schemas/Universal/2011/11">
                  <Event>
                      <DataContext>
                          <Company>
                              <Code>ID1</Code>
                          </Company>
                    <EnterpriseID>B52</EnterpriseID>
                    <ServerID>TS2</ServerID>
                          <DataTargetCollection>
                              <DataTarget>
                                  <Type>ForwardingShipment</Type>
                                  <Key>'.$house->ShipmentNumber.'</Key>
                              </DataTarget>
                          </DataTargetCollection>
                      </DataContext>
                      <EventTime>'.$time->toDateTimeLocalString().'</EventTime>
                      <EventType>FUL</EventType>
                      <EventReference>|EXT_SOFTWARE=TPS|FAC=CFS|LNK=GIWIA|LOC=IDJKT|</EventReference>
                      <IsEstimate>false</IsEstimate>
                  </Event>
              </UniversalEvent>
              ';
              
      $micro = $time->format('u');

      $giwiName = 'XUE_TPSID_'.$house->ShipmentNumber.'_GIWIA_'.$time->format('YmdHms').substr($micro, 0,3).'_'.Str::uuid().'.xml';

      try {        

        $giwia = Storage::disk('sftp')->put($giwiName, $giwiaTxt);

        createLog('App\Models\House', $house->id, 'Create file '.$giwiName.' at '.$time->translatedFormat('l d F Y H:i'));

        return $giwiName;

      } catch (\Throwable $th) {
        throw $th;
      } 

    }

    public function download(Request $request)
    {
      return Storage::disk('sftp')->download($request->file);
    }
}
