<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\House;
use Carbon\Carbon;
use Crypt, DB;

class TpsOnlineScanInController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $item = new House;

        return view('pages.tpsonline.scan_in', compact(['item']));
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
              'SCAN_IN_DATE' => $now->format('Y-m-d'),
              'SCAN_IN' => 'Y'
            ]);

            DB::commit();

            $gowia = $this->createXML($house, $now);

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(House $scan_in)
    {
        $item = $scan_in;

        return view('pages.tpsonline.scan_in', compact(['item']));
    }

    public function createXML(House $house, Carbon $time)
    {
      $giwiaTxt = '<UniversalEvent xmlns="http://www.cargowise.com/Schemas/Universal/2011/11">		<!--xmlns is mandatory-->
                  <Event>
                      <DataContext>
                          <Company>
                              <Code>ID1</Code>						<!--Company Code-->
                          </Company>
                    <EnterpriseID>B52</EnterpriseID>			<!--EnterpriseID=B52 all the time and all environment-->
                    <ServerID>TS2</ServerID>					<!--Server=TS2 in UAT and Server=PRO in production-->
                          <DataTargetCollection>
                              <DataTarget>
                                  <Type>ForwardingShipment</Type>		<!--Key Type=ForwardingShipment when the key start by "S", it is required-->
                                  <Key>'.$house->ShipmentNumber.'</Key>				<!--Key is mandatory, otherwise the XML will fail-->
                              </DataTarget>
                          </DataTargetCollection>
                      </DataContext>
                      <EventTime>'.$time->toDateTimeLocalString().'</EventTime>	<!--EventTime -->
                      <EventType>FUL</EventType>					<!--EventCode is required, FUL event for GIWIA and FLO for GOWIA -->
                      <EventReference>|EXT_SOFTWARE=TPS|FAC=CFS|LNK=GIWIA|LOC=...</EventReference>	<!--EventReference: |EXT_SOFTWARE=TPS|FAC=CFS|LNK=GOWIA| is a mandatory part, you can other info like LOC, REF etc. Each part separated by | -->
                      <IsEstimate>false</IsEstimate>				<!--Set IsEstimate=false all the time-->
                  </Event>
              </UniversalEvent>
              ';
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
      $gowiName = $house->ShipmentNumber.'_XUE_TPS_EVENT_FLO_'.round(microtime(true), 0).'.xml';
      $giwiName = $house->ShipmentNumber.'_XUE_TPS_EVENT_FUL_'.round(microtime(true), 0).'.xml';

      try {
        $gowia = Storage::disk('sftp')->put($gowiName, $gowiaTxt);

        createLog('App\Models\House', $house->id, 'Create file '.$gowiName.' at '.$time->translatedFormat('l d F Y H:i'));

        $giwia = Storage::disk('sftp')->put($giwiName, $giwiaTxt);

        createLog('App\Models\House', $house->id, 'Create file '.$giwiName.' at '.$time->translatedFormat('l d F Y H:i'));

      } catch (\Throwable $th) {
        throw $th;
      } 

    }
}
