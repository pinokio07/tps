<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\House;
use Carbon\Carbon;
use Crypt, Str, DB;

class TpsOnlineScanOutController extends Controller
{    
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

            $gowia = $this->createXML($house, $now->setTimeZone('UTC'));

            $house->update([
              'CW_Ref_GateOut' => $gowia
            ]);

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
    
    public function show(House $scan_out)
    {
        $item = $scan_out;
        $type = 'out';

        return view('pages.tpsonline.scan', compact(['item', 'type']));
    }

    public function createXML(House $house, Carbon $time)
    {      
      $gowiaTxt = '<UniversalEvent xmlns="http://www.cargowise.com/Schemas/Universal/2011/11">		<!--xmlns is mandatory-->
                  <Event>
                      <DataContext>
                          <Company>
                              <Code>ID1</Code>						<!--Company Code-->
                          </Company>
                    <EnterpriseID>B52</EnterpriseID>			<!--EnterpriseID=B52 all the time and in all environments-->
                    <ServerID>TS2</ServerID>					<!--Server=TS2 in UAT and Server=PRO in production-->
                          <DataTargetCollection>
                              <DataTarget>
                                  <Type>ForwardingShipment</Type>		<!--Key Type=ForwardingShipment when the key start by "S", it is required-->
                                  <Key>'.$house->ShipmentNumber.'</Key>				<!--Key is mandatory, otherwise the XML will fail-->
                              </DataTarget>
                          </DataTargetCollection>
                      </DataContext>
                      <EventTime>'.$time->toDateTimeLocalString().'</EventTime>	<!--EventTime -->
                      <EventType>FLO</EventType>					<!--EventCode is required, FUL event for GIWIA and FLO for GOWIA -->
                      <EventReference>|EXT_SOFTWARE=TPS|FAC=CFS|LNK=GOWIA|LOC=yyyyyy|REF=xxxxxxx|</EventReference>	<!--EventReference: |EXT_SOFTWARE=TPS|FAC=CFS|LNK=GOWIA| is a mandatory part, you can other info like LOC, REF etc-->
                      <IsEstimate>false</IsEstimate>				<!--Set IsEstimate=false all the time-->
                  </Event>
              </UniversalEvent>
              ';
      
      $micro = $time->format('u');

      $gowiName = 'XUE_TPSID_'.$house->ShipmentNumber.'_GOWIA_'.$time->format('YmdHms').substr($micro, 0,3).'_'.Str::uuid().'.xml';

      try {
        $gowia = Storage::disk('sftp')->put($gowiName, $gowiaTxt);

        createLog('App\Models\House', $house->id, 'Create file '.$gowiName.' at '.$time->translatedFormat('l d F Y H:i'));        

        return $gowiName;

      } catch (\Throwable $th) {
        throw $th;
      } 

    }

    public function download(Request $request)
    {
      return Storage::disk('sftp')->download($request->file);
    }
}
