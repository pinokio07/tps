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

          if(!$house->SCAN_IN_DATE){
            return redirect()->route('tps-online.scan-out')
                            ->with('gagal', 'This house is not yet Scan In!.');
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

            createLog('App\Models\House', $house->id, 'SCAN OUT');

            $gowia = $this->createXML($house, $now->setTimeZone('UTC'));

            createLog('App\Models\House', $house->id, 'Create file '.$gowia.' at '.$now->translatedFormat('l d F Y H:i'));

            $house->update([
              'SCAN_OUT_DATE' => $now,
              'SCAN_OUT' => 'Y',
              'ExitDate' => $now->format('Y-m-d'),
              'ExitTime' => $now->format('H:i:s'),
              'CW_Ref_GateOut' => $gowia
            ]);

            DB::commit();

            return redirect()->route('tps-online.scan-out.show', [
                            'scan_out' => Crypt::encrypt($house->id)
                          ])
                          ->with('sukses', 'Scan Out Success.');

          } catch (\Throwable $th) {
            DB::rollback();
            
            return redirect()->route('tps-online.scan-out.show', [
                              'scan_out' => Crypt::encrypt($house->id)
                            ])
                            ->withErrors($th->getMessage());
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
      $gowiaTxt = '<UniversalEvent xmlns="http://www.cargowise.com/Schemas/Universal/2011/11">
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
                      <EventType>FLO</EventType>
                      <EventReference>|EXT_SOFTWARE=TPS|FAC=CFS|LNK=GOWIA|LOC=IDJKT|</EventReference>
                      <IsEstimate>false</IsEstimate>
                  </Event>
              </UniversalEvent>
              ';
      
      $micro = $time->format('u');

      $gowiName = 'XUE_TPSID_'.$house->ShipmentNumber.'_GOWIA_'.$time->format('YmdHms').substr($micro, 0,3).'_'.Str::uuid().'.xml';

      try {

        // $gowia = Storage::disk('sftp')->put($gowiName, $gowiaTxt);
        $gowia = Storage::disk('ftp')->put($gowiName, $gowiaTxt);

        return $gowiName;

      } catch (FilesystemException | UnableToWriteFile $th) {

        return redirect()->route('tps-online.scan-out.show', [
                          'scan_out' => Crypt::encrypt($house->id)
                        ])
                        ->withErrors($th->getMessage());
      } 

    }

    public function download(Request $request)
    {
      return Storage::disk('sftp')->download($request->file);
    }
}
