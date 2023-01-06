<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Running;
use App\Models\Master;
use App\Models\PlpOnline;
use App\Models\PlpOnlineLog;
use Carbon\Carbon;
use DB, Auth;

class PlpController extends Controller
{
    public function index(Request $request, Master $master)
    {
      $data = $request->validate([
        'jenis' => 'required'
      ]);

      if($data){
        $jenis = $request->jenis;
        $today = today();        

        switch ($jenis) {
          case 'plp-request':

            if(!$master->pendingPlp->isEmpty()){
              return response()->json([
                'status' => 'GAGAL',
                'message' => 'Anda sedang menunggu respon PLP dari BC, tidak diperkenankan mengirim ulang permohonan PLP. Proses dibatalkan.'
              ]);
            }
    
            $running = getRunning('PLP', 'REF_NUMBER', $today->format('Y-m-d'));
            $nosurat = getRunning('PLP', 'NO_SURAT', $today->format('Y-m-d'));

            return $this->sendAjuPlp($master, $running, $nosurat, $today);

            break;
          case 'plp-response':
            if(!$master->approvedPlp->isEmpty()){
              return response()->json([
                'status' => 'GAGAL',
                'message' => 'Anda sudah memperoleh persetujuan PLP atas dokumen ini. Silakan cetak permohonan tersebut.'
              ]);
            }
            return $this->getResponsePlp($master);
            break;
          case 'plp-batal':
            return $this->sendBatalPlp($master);
            break;
          case 'plp-resbatal':
            return $this->getResponseBatalPlp($master);
            break;
          default:
            return false;
            break;
        }
      }
      
    }

    public function sendAjuPlp(Master $master, $running, $nosurat, Carbon $today)
    {
      $warehouse = $master->warehouseLine1;

      DB::beginTransaction();

      try {
        $plp = PlpOnline::updateOrCreate([
                          'master_id' => $master->id
                        ],[
                          'KD_KANTOR' => $master->KPBC,
                          'TIPE_DATA' => 1,
                          'KD_TPS_ASAL' => $warehouse->tps_code ?? NULL,
                          'REF_NUMBER' => $running,
                          'NO_SURAT' => ( $nosurat . '/PLP/' . $warehouse->warehouse_code . '/' . $warehouse->warehouse_code . '-TE11' . '/' . $today->format('Y') ),
                          'TGL_SURAT' => $today->format('Y-m-d'),
                          'GUDANG_ASAL' => $warehouse->warehouse_code,
                          'KD_TPS_TUJUAN' => 'SDVL',
                          'GUDANG_TUJUAN' => 'TE11',
                          'KD_ALASAN_PLP' => 5,
                          'NM_ANGKUT' => $master->NM_SARANA_ANGKUT,
                          'NO_VOY_FLIGHT' => $master->FlightNo,
                          'TGL_TIBA' => $master->ArrivalDate,
                          'NO_BC11' => $master->PUNumber, 
                          'TGL_BC11' => $master->PUDate, 
                          'NO_BL_AWB' => $master->MAWBNumber,
                          'TGL_BL_AWB' => $master->MAWBDate,
                          'JNS_KMS' => $master->houses->first()->JNS_KMS, 
                          'JML_KMS' => $master->mNoOfPackages,
                          'NM_PEMOHON' => Auth::user()->name,
                          'CABANG' => activeCompany()->branches->first()->id,
                          'LAST_SENT' => now(),
                          'STATUS'    => 'Pending',
                        ]);
        DB::commit();

        $mohonPLP = [
          'LOADPLP' => [
              'HEADER' => [
                'KD_KANTOR' => $plp->KD_KANTOR,
                'TIPE_DATA' => $plp->TIPE_DATA,
                'KD_TPS_ASAL' => $plp->KD_TPS_ASAL,
                'REF_NUMBER' => $plp->REF_NUMBER,
                'NO_SURAT'  => $plp->NO_SURAT,
                'TGL_SURAT' => date('Ymd',strtotime($plp->TGL_SURAT)),
                'GUDANG_ASAL'   => $plp->GUDANG_ASAL,
                'KD_TPS_TUJUAN' => $plp->KD_TPS_TUJUAN,
                'GUDANG_TUJUAN' => $plp->GUDANG_TUJUAN,
                'KD_ALASAN_PLP' => $plp->KD_ALASAN_PLP,
                'YOR_ASAL' => $plp->YOR_ASAL,
                'YOR_TUJUAN' => $plp->YOR_TUJUAN,
                'CALL_SIGN' => '',
                'NM_ANGKUT' => $plp->NM_ANGKUT,
                'NO_VOY_FLIGHT' => $plp->NO_VOY_FLIGHT,
                'TGL_TIBA' => date('Ymd',strtotime($plp->TGL_TIBA)),
                'NO_BC11' => $plp->NO_BC11,
                'TGL_BC11' => date('Ymd',strtotime($plp->TGL_BC11)),
                'NM_PEMOHON' => $plp->NM_PEMOHON,
              ],
              'DETIL' => [
                'KMS' => [
                  'JNS_KMS'   => $plp->JNS_KMS,
                  'JML_KMS'   => $plp->JML_KMS,
                  'NO_BL_AWB' => $master->mawb_parse,
                  'TGL_BL_AWB'    => date('Ymd',strtotime($plp->TGL_BL_AWB))
                ]
              ]
            ]
        ];

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><DOCUMENT xmlns="loadplp.xsd"></DOCUMENT>');

        $this->array_to_xml($MohonPLP, $xml);
        
        $opts = [
          'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
          ]
        ];

        $response = Soap::baseWsdl('https://tpsonline.beacukai.go.id/tps/service.asmx?wsdl')
                      ->withOptions([
                        'trace' => true,
                        'encoding' => 'UTF-8',
                        'verifypeer' => false,
                        'verifyhost' => false,
                        'soap_version' => SOAP_1_2,
                        'keep_alive' => false,
                        'connection_timeout' => 180,
                        'stream_context' => stream_context_create($opts)
                      ])
                      ->uploadMohonPLP([
                        'fStream' => $xml->asXML(),
                        'Username' => config('tps.user'),
                        'Password' => config('tps.password')
                      ])
                      ->call();
        if($response->successful()){

        } elseif($response->clientError()){

        } elseif($response->serverError()){

        }

        return response()->json($data);

      } catch (\Throwable $th) {
        DB::rollback();

        return response()->json(['status' => 'ERROR', 'message' => $th->getMessage()]);
      }

    }

    public function getResponsePlp(Master $master)
    {
      # code...
    }

    public function array_to_xml($array, &$xml)
    {
      foreach ($array as $key => $value) {
        if (is_array($value)) {
            if (!is_numeric($key)) {
                $subnode = $xml->addChild("$key");
                $this->array_to_xml($value, $subnode);
            } else {
                $this->array_to_xml($value, $xml);
            }
        } else {
            $xml->addChild("$key", "$value");
        }
      }
    }
}
