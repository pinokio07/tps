<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\LaporanExport;
use App\Models\Master;
use App\Models\House;
use Excel;

class InventoryReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->jenis
            && $request->period){
          
          $jenis = $request->jenis;
          $period = explode(' - ', $request->period);

          $start = $period[0];
          $end = $period[1];

          $query = House::with(['master']);

          switch ($jenis) {
            case 'barang-keluar':
              $query->whereBetween('SCAN_OUT_DATE', [$start, $end])
                    ->orderBy('SCAN_OUT_DATE');
              break;
            case 'barang-masuk':
              $query->whereBetween('SCAN_IN_DATE', [$start, $end])
                    ->orderBy('SCAN_IN_DATE');
              break;
            case 'tidak-dikuasai':
              $abdate = Carbon::parse($start)->subDays(30)
                                             ->endOfDay()
                                             ->format('Y-m-d H:i:s');
              $query->where('SCAN_IN_DATE', '<', $abdate)
                    ->whereNull('SCAN_OUT_DATE')
                    ->with(['details'])
                    ->orderBy('SCAN_IN_DATE');
              break;
            case 'monev':
              $query->whereBetween('SCAN_IN_DATE', [$start, $end])
                    ->with(['master', 'details'])
                    ->orderBy('SCAN_IN_DATE');
              break;
            case 'rekap-plp':
              $query = Master::whereBetween('ApprovedPLP', [$start, $end])
                             ->orderBy('ApprovedPLP');
              break;
            case 'status-plp':
            
              break;
            case 'timbun':
          
              break;
            default:
              # code...
              break;
          }

          $items = $query->get();

          return Excel::download(new LaporanExport($items, $jenis, $start, $end), 'Laporan '.$jenis.' '.$request->period.'.xlsx');
        }
        return view('pages.inventory.report');
    }
        
}
