<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Print Sewa Gudang - {{ $shipment->NO_HOUSE_BLAWB }}</title>
  <style>
    @page{ 
      margin: 20mm 15mm 15mm 15mm;   
      font-family: Verdana, Geneva, Tahoma, sans-serif;
      font-size: 8.5pt;
    }
    table{
      width: 100%;
      border-collapse: collapse;
    }
    .table th,
    .table td {
      padding: 0.15rem;
      vertical-align: top;
      border-top: 1px solid #565656;
    }

    .table thead th {
      vertical-align: bottom;
      border-bottom: 2px solid #565656;
    }

    .table tbody + tbody {
      border-top: 2px solid #565656;
    }
    .table-bordered th,
    .table-bordered td {
      border: 1px solid #000000;
    }

    .table-bordered thead th,
    .table-bordered thead td {
      border-bottom-width: 1px;
    }
    .border{
      border:1px solid black;
    }
    .line-100{      
      line-height: 100% !important;
    }
    .text-center{
      text-align: center !important;
    }
    .text-middle{
      vertical-align: middle !important;
      text-align: center !important;
    }
    .text-right{
      text-align: right !important;
    }
  </style>
</head>
<body>
  <table class="table table-bordered line-100" style="width: 100%;">
    <tr>
      <td rowspan="3" style="width: 13%;">
        {{-- @php
            $imgPath = public_path('/img/companies/'.$company->GC_Logo);
            if(is_dir($imgPath) || !file_exists($imgPath)){
              $imgPath = public_path('/img/default-logo-light.png');
            }
          @endphp
          <img src="{{ $imgPath }}" alt="Company Logo"
                height="50"> --}}
      </td>
      <td rowspan="2" style="width: 55%;">
        <b>PT Bollore Logistics Indonesia</b><br>
        Suite A, 1st Floor, Wisma Soewarna Soewarna Business Park<br>
        Soekarno-Hatta International Airport, Jakarta 19110 Indonesia<br>
        Tel. : (62-21) 5591 1717<br>
        Tel. : (62-21) 5591 1717
      </td>
      <td class="text-center" 
          style="vertical-align: middle;">
        Country
      </td>
      <td class="text-center" 
          style="vertical-align: middle;">
        INDONESIA
      </td>
    </tr>
    <tr>
      <td class="text-center" 
          style="vertical-align: middle;"
        >Division
      </td>
      <td class="text-center" 
          style="vertical-align: middle;">
        AIR IMPORT
      </td>
    </tr>
    <tr>
      <td class="text-center">Title : Storage Calculation</td>
      <td class="text-center">Document Ref.</td>
      <td class="text-center">OPS-FOR-AI-008-R0</td>
    </tr>
  </table>
  <div style="text-align: center;margin-top:20px;font-size:10pt;">
    <span>
      <h4 style="text-decoration: underline; margin:0 auto;">
        KALKULASI JASA GUDANG
      </h4>
    </span>
    <span>
      <h4 style="text-decoration: underline; margin:0 auto;">
        STORAGE CALCULATION
      </h4>
    </span>
  </div>
  <table style="width: 100%; margin-top:40px;">
    <tr style="vertical-align: top;">
      <td style="width: 12%;">Scheme</td>
      <td style="width: 1%;">:</td>
      <td colspan="2" style="width: 53%;">{{ $shipment->schemaTariff->name ?? "-" }}</td>
      <td style="width: 10%;">ISSUED</td>
      <td style="width: 1%;">:</td>
      <td style="width: 20%;">Jakarta, {{ today()->translatedFormat('d F Y') }}</td>
    </tr>
    <tr style="vertical-align: top;">
      <td>Document Number</td>
      <td>:</td>
      <td colspan="2">{{ rand(000001, 999999) . '/SDV/'.today()->format('Ymd') }}</td>
      <td>ATA</td>
      <td>:</td>
      <td>
        @php
          if($shipment->TGL_TIBA){
            $tiba = \Carbon\Carbon::parse($shipment->TGL_TIBA);
            $showTiba = $tiba->translatedFormat('d F Y');
          } else {
            $tiba = null;
            $showTiba = '-';
          }
        @endphp
        {{ $showTiba }}
      </td>
    </tr>
    <tr style="vertical-align: top;">
      <td>Shipper</td>
      <td>:</td>
      <td colspan="2">{{ $shipment->NM_PENGIRIM ?? "-" }}</td>
      <td>Cargo Out</td>
      <td>:</td>
      <td>
        @php
          if($shipment->estimatedExitDate){
            $keluar = \Carbon\Carbon::parse($shipment->estimatedExitDate);
            $showKeluar = $keluar->translatedFormat('d F Y');
          } elseif($shipment->SCAN_OUT_DATE) {
            $keluar = \Carbon\Carbon::parse($shipment->SCAN_OUT_DATE);
            $showKeluar = $keluar->translatedFormat('d F Y');
          } else {
            $keluar = null;
            $showKeluar = '-';
          }
        @endphp
        {{ $showKeluar }}
      </td>
    </tr>
    <tr style="vertical-align: top;">
      <td>Airport Origin</td>
      <td>:</td>
      <td colspan="2">{{ $shipment->unlocoOrigin->RL_PortName ?? "-" }}</td>
      <td>Total Days</td>
      <td>:</td>
      <td>
        @php
            if($tiba && $keluar){
              $beda = $tiba->diffInDays($keluar);
            } else {
              $beda = 0;
            }
        @endphp
        {{ $beda + 1 }}
      </td>
    </tr>
    <tr style="vertical-align: top;">
      <td>Commodity</td>
      <td>:</td>
      <td>
        @forelse ($shipment->details as $detail)
          {{ $detail->UR_BRG }} @if(!$loop->last) / @endif
        @empty          
        @endforelse
      </td>
      <td style="text-align: right; padding-right:50px;">
        @forelse ($shipment->details as $detail)
          {{ $detail->HS_CODE ?? 00000000 }} @if(!$loop->last) / @endif
        @empty          
        @endforelse
      </td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr style="vertical-align: top;">
      <td>No HAWB</td>
      <td>:</td>
      <td>{{ $shipment->NO_HOUSE_BLAWB ?? "-" }}</td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </table>
  <table class="table table-bordered" style="width: 100%;margin-top:5px;">
    <tr>
      <td colspan="6">
        <b>CONSIGNEE : {{ $shipment->NM_PENGIRIM ?? "-" }}</b>
      </td>
    </tr>
    <tr class="text-center">
      <td><b>No. AWB</b></td>
      <td><b>No. P.U/Pos</b></td>
      <td><b>FLIGHT</b></td>
      <td><b>CARGO IN</b></td>
      <td><b>HARI</b></td>
      <td><b>Coli - Kg/Ch. Weight</b></td>
    </tr>
    <tr>
      <td class="text-middle">
        {{ $shipment->mawb_parse ?? "-" }}
      </td>
      <td class="text-middle">
        {{ $shipment->NO_BC11 ?? "-" }}
      </td>
      <td class="text-middle">
        {{ $shipment->NO_FLIGHT ?? "-" }}
      </td>
      <td class="text-middle">
        @php
          $inDate = $shipment->SCAN_IN_DATE ?? $shipment->TGL_TIBA;
          $tglTiba = \Carbon\Carbon::parse($inDate)->translatedFormat('d F Y');
        @endphp
        {{ $tglTiba ?? "-" }}
      </td>
      <td class="text-middle">
        {{ $beda + 1 }}
      </td>
      <td>
        Coli : {{ $shipment->JML_BRG ?? 0 }}<br>
        KG : {{ $shipment->BRUTO ?? 0 }}<br>
        CH. Weight : {{ $shipment->ChargeableWeight ?? 0 }}
      </td>
    </tr>
  </table>
  <table class="table table-bordered" style="width: 100%;margin-top:20px;">
    <tr class="text-center">
      <td colspan="4"><b>KETERANGAN</b></td>
      <td><b>JUMLAH</b></td>
    </tr>
    <tr>
      <td colspan="4">
        <table>
          <tr style="border: none;">
            <td style="border: none;">Name (Special Rules)</td>
            <td class="text-right" style="border: none;">Rate/Days</td>
            <td class="text-center" style="border: none;">Days</td>
          </tr>          
          @forelse ($shipment->estimatedTariff->where('is_vat', false) as $tariff)
            @php
              $rateShow = '';
              
              if($tariff->rate){
                
                if($tariff->rate < 1){
                  $rateShow = ($tariff->rate * 100);
                } else {
                  $rateShow = number_format($tariff->rate, 0, ',', '.');
                }
              }
            @endphp
            <tr style="border: none;">
              <td style="border: none;">{{ $tariff->item }}</td>
              <td class="text-right" style="border: none;">
                {{ ($rateShow == 0) ? '' : $rateShow }}
              </td>
              <td class="text-center" style="border: none;width:80px;">
                {{ ($tariff->days < 1) ? '' : $tariff->days }}
              </td>
            </tr>
          @empty            
          @endforelse
        </table>
      </td>
      <td>
        <table>
          <tr style="border: none;">
            <td style="border: none;">&nbsp;</td>
          </tr>
          @php
            $subTotal = 0;
          @endphp
          @forelse ($shipment->estimatedTariff->where('is_vat', false) as $tariff)
            @php
              $subTotal += $tariff->total;
            @endphp
            <tr style="border: none;">
              <td class="text-right" style="border: none;">
                {{ number_format($tariff->total, 0, ',', '.') }}
              </td>
            </tr>
          @empty            
          @endforelse
        </table>
      </td>     
    </tr>
    <tr>
      <td colspan="4" class="text-center"><b>SUB TOTAL</b></td>
      <td class="text-right"><b>Rp. {{ number_format($subTotal, 0, ',','.') }}</b></td>
    </tr>
    @php
      $vatTariff = $shipment->estimatedTariff->where('is_vat', true)->first();
    @endphp
    <tr>
      <td colspan="4">
        <table>
          <tr style="border: none;">
            <td style="border: none;">VAT</td>
            <td class="text-right" style="border: none;">
              {{ ($shipment->schemaTariff->vat + 0) }} %
            </td>
            <td style="border: none;width:80px;"></td>
          </tr>
        </table>
      </td>
      <td class="text-right">{{ number_format(round($vatTariff->total), 0, ',', '.') }}</td>
    </tr>
    <tr>
      @php
        $grandTotal = $subTotal + round($vatTariff->total);
      @endphp
      <td colspan="4" class="text-center"><b>TOTAL</b></td>
      <td class="text-right"><b>Rp. {{ number_format($grandTotal, 0, ',','.') }}</b></td>
    </tr>
    <tr>
      <td colspan="5" >
        @php
          $number = explode('.', $grandTotal);
          $nf2 = new \NumberFormatter('id_ID', \NumberFormatter::SPELLOUT);
        @endphp
        <table>
          <tr>
            <td style="width:60px;border:none;height:80px;">Terbilang</td>
            <td style="width: 4px;border:none;">:</td>
            <td style="border:none;">
              {{ Str::title($nf2->format($number[0])) }} rupiah
            </td>
          </tr>
          <tr>
            <td style="width:60px;border:none;">Invoice To</td>
            <td style="width: 4px;border:none;">:</td>
            <td style="text-decoration: underline;border:none;">
              {{ $shipment->NM_PENERIMA }}
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>