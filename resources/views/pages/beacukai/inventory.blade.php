@extends('layouts.master')
@section('title') {{Str::title(Request::segment(1))}} @endsection
@section('page_name') Inventory @endsection

@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Inventory</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <form id="formBeaCukai" action="{{ url()->current() }}" method="get">
              <div class="row">                
                <div class="col-lg-3">
                  <div class="form-group">
                    <div class="input-group input-group-sm date onlydate" 
                          id="datetimepicker1" 
                          data-target-input="nearest">
                      <input type="text" 
                              id="from"
                              name="from"
                              class="form-control datetimepicker-input tanggal"
                              placeholder="From Date"
                              data-target="#datetimepicker1"
                              value="{{ Request::get('from') ?? '' }}"
                              required>
                      <div class="input-group-append" 
                            data-target="#datetimepicker1" 
                            data-toggle="datetimepicker">
                        <div class="input-group-text">
                          <i class="fa fa-calendar"></i>
                        </div>
                      </div>
                    </div>
                  </div>                  
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <div class="input-group input-group-sm date onlydate" 
                          id="datetimepicker2" 
                          data-target-input="nearest">
                      <input type="text" 
                              id="to"
                              name="to"
                              class="form-control datetimepicker-input tanggal"
                              placeholder="To Date"
                              data-target="#datetimepicker2"
                              value="{{ Request::get('to') ?? '' }}"
                              required>
                      <div class="input-group-append" 
                            data-target="#datetimepicker2" 
                            data-toggle="datetimepicker">
                        <div class="input-group-text">
                          <i class="fa fa-calendar"></i>
                        </div>
                      </div>
                    </div>
                  </div>                  
                </div>                
                <div class="col-2 col-lg-1">
                  <button type="submit" 
                          class="btn btn-sm btn-primary btn-block elevation-2"
                          id="btnFilter">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </form>
            <div class="table-responsive">
              <table id="dataAjax" 
                     class="table table-sm table-bordered" 
                     style="width: 100%;">
                <thead>
                  <tr>
                    <th rowspan="2">No</th>
                    <th colspan="3" class="text-center">BC</th>
                    <th rowspan="2">Nama Pengangkut</th>
                    <th colspan="3" class="text-center">PLP</th>
                    <th colspan="2" class="text-center">Jumlah</th>
                    <th rowspan="2">MAWB Number</th>
                    <th rowspan="2">Nama Pemberitahu</th>
                    <th colspan="6" class="text-center">Jumlah Gate</th>
                    <th rowspan="2">Waktu Masuk TPS</th>
                    <th rowspan="2">Keterangan</th>
                  </tr>
                  <tr>
                    <th>Nomor</th>
                    <th>Tanggal</th>
                    <th>Pos</th>
                    <th>Nomor</th>
                    <th>Tanggal</th>
                    <th>Segel</th>
                    <th>Koli</th>
                    <th>Berat</th>
                    <th>CN Total</th>
                    <th>Gate In</th>
                    <th>SPPB</th>
                    <th>Gate Out</th>
                    <th>Pending</th>
                    <th>Current Now</th>
                  </tr>
                </thead>
              </table>
            </div>            
          </div>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection

@section('footer')
  <script>   
    $(function(){
      $('.onlydate').datetimepicker({
        icons: { time: 'far fa-clock' },
        format: 'DD-MM-YYYY',
        sideBySide: true,
        allowInputToggle: true
      });
    })
    function getDataAjax() {
      var data = $('#formBeaCukai').serialize();

      $('#dataAjax').DataTable().destroy();

      $.ajax({
        url: "{{ url()->current() }}",
        type: "GET",
        data: data,
        success:function(msg){
          $('#dataAjax').DataTable({
            data: msg.data,
            columns:[
              @forelse ($items as $keys => $item)
                @if($keys == 'id')
                  {data:"DT_RowIndex", name: "DT_RowIndex", searchable: false},
                @elseif(in_array($keys, ['PUDate', 'mawb_parse', 'MasukGudang']))
                {data: "{{$keys}}", name: "{{$keys}}", className: "text-nowrap"},
                @else
                {data: "{{$keys}}", name: "{{$keys}}"},
                @endif
              @empty
              @endforelse          
            ],
            buttons: [                
                {
                  extend: 'excelHtml5',
                  action: function ( e, dt, node, config ) {
                    var from = $('#from').val();
                    var to = $('#to').val();
                    if(from == ''
                        || to == ''){
                      alert('Please Select Dates');

                      return false;
                    }
                    window.open("{{ route('download.bea-cukai.inventory') }}?jenis=xls&from="+from+"&to="+to);
                  }
                },
                {
                    extend: 'pdfHtml5',
                    action: function ( e, dt, node, config ) {
                      var from = $('#from').val();
                      var to = $('#to').val();
                      if(from == ''
                          || to == ''){
                        alert('Please Select Dates');

                        return false;
                      }
                      window.open("{{ route('download.bea-cukai.inventory') }}?jenis=pdf&from="+from+"&to="+to);
                    }
                }
            ],
            initComplete: function () {
              this.api().columns([1,2,3,4,5,6,7,8,11,12,13,14,16,17,18]).every( function () {
                var column = this;
                var select = $('<select class="select2bs4clear" style="width: 100%;"><option value="">Select...</option></select>')
                .appendTo( $(column.footer(3)).empty() )
                .on( 'change', function () {
                  var val = $.fn.dataTable.util.escapeRegex(
                    $(this).val()
                    );
                  column
                  .search( val ? '^'+val+'$' : '', true, false )
                  .draw();
                } );

                column.data().unique().sort().each( function ( d ) {
                  if(d !== ''){                    
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                  }              
                } );
              } );

              select2bs4Clear();
            }, 
          })
          .buttons()
          .container()
          .appendTo('#dataAjax_wrapper .col-md-6:eq(0)');
        },
        error:function(msg){
          console.log(msg);
        }
      });
    }
    
    jQuery(document).ready(function(){
      // getDataAjax();
      $(document).on('submit', '#formBeaCukai', function(e){
        e.preventDefault();
        getDataAjax();
      });
    });
  </script>
@endsection
