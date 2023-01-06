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
      <td style="width: 53%;">{{ $shipment->schemaTariff->name ?? "-" }}</td>
      <td style="width: 10%;">ISSUED</td>
      <td style="width: 1%;">:</td>
      <td style="width: 20%;">Jakarta, {{ today()->translatedFormat('d F Y') }}</td>
    </tr>
    <tr style="vertical-align: top;">
      <td>Document Number</td>
      <td>:</td>
      <td>{{ rand(000001, 999999) . '/SDV/'.today()->format('Ymd') }}</td>
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
      <td>{{ $shipment->NM_PENGIRIM ?? "-" }}</td>
      <td>Cargo Out</td>
      <td>:</td>
      <td>
        @php
          $count = $shipment->estimatedTariff->sum('days');
          if($tiba){
            $keluar = $tiba->copy()->addDays($count)->translatedFormat('d F Y');
          }
        @endphp
        {{ $keluar }}
      </td>
    </tr>
    <tr style="vertical-align: top;">
      <td>Airport Origin</td>
      <td>:</td>
      <td>{{ $shipment->unlocoOrigin->RL_PortName ?? "-" }}</td>
      <td>Total Days</td>
      <td>:</td>
      <td>
        {{ $count }}
      </td>
    </tr>
    <tr style="vertical-align: top;">
      <td>Commodity</td>
      <td>:</td>
      <td>{{ $shipment->unlocoOrigin->RL_PortName ?? "-" }}</td>
      <td>Total Days</td>
      <td>:</td>
      <td>
        {{ $count }}
      </td>
    </tr>
  </table>
</body>
</html>