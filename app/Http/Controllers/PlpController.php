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

        // $plp->logs()->create([
        //   'user_id' => Auth::id(),
        //   'REF_NUMBER' => $running,
        //   'Service' => 'SendAjuPlp',
        //   ''
        // ])

        return response()->json(['status' => 'OK']);

      } catch (\Throwable $th) {
        DB::rollback();

        return response()->json(['status' => 'ERROR', 'message' => $th->getMessage()]);
      }

    }

    public function getResponsePlp(Master $master)
    {
      # code...
    }
}
