<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Download Document Tegah</title>
  <style>
    @page{
      font-family: Verdana, Geneva, Tahoma, sans-serif;
      font-size: 6pt;
      margin: 20mm 7mm 10mm 7mm;
    }
    header{
      position: fixed;      
      top: -10mm;
      left: 0;
      right: 0;
      z-index: 1000;
    }
    footer{
      position: fixed;
      bottom: -5mm; 
      left: 0; 
      right: 0;      
    }
    table {
      border-collapse: collapse;
    }
    .table {
      width: 100%;
      margin-bottom: 1rem;
      color: #212529;
      background-color: transparent;
    }

    .table th,
    .table td {
      padding: 0.75rem;
      vertical-align: top;
      border-top: 1px solid #000000;
    }

    .table thead th {
      vertical-align: bottom;
      border-bottom: 1px solid #000000;
    }

    .table tbody + tbody {
      border-top: 2px solid #000000;
    }

    .table-sm th,
    .table-sm td {
      padding: 0.3rem;
    }

    .table-bordered {
      border: 1px solid #000000;
    }

    .table-bordered th,
    .table-bordered td {
      border: 1px solid #000000;
    }

    .table-bordered thead th,
    .table-bordered thead td {
      border-bottom-width: 1px;
    }
    .table-striped tbody tr:nth-of-type(odd) {
      background-color: rgba(0, 0, 0, 0.05);
    }
    .text-left {
      text-align: left !important;
    }

    .text-right {
      text-align: right !important;
    }

    .text-center {
      text-align: center !important;
    }
    .text-valign{
      vertical-align: middle !important;
    }
  </style>
</head>
<body>
  <script type="text/php">
    if (isset($pdf)) {
        $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
        $size = 8;
        $font = $fontMetrics->getFont("Verdana");
        $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
        {{-- $x = ($pdf->get_width() - $width) / 2;
        $y = $pdf->get_height() - 35; --}}
        $x = 475;
        $y = 590;
        $pdf->page_text($x, $y, $text, $font, $size);
    }
  </script>
  <header>
    <table style="width: 100%;">
      <tr>
        <th style="text-align: center;height: 25px; background-color: #3A75C4;color:white;font-size:14pt;vertical-align:middle;">
          DAFTAR BARANG DI TEGAH {{ Str::upper($company->GC_Name) }}
        </th>
      </tr>
    </table>
  </header>
  <footer>
    Print At {{ now()->format('d/m/Y') }}
  </footer>
  <main>
    @include('exports.tegahexcel')
  </main>  
</body>
</html>