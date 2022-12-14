@extends('layouts.master')
@section('title') Shipments @endsection
@section('page_name') Shipments @endsection

@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Shipments</h3>
            <div class="card-tools">              
              <a href="{{ route('manifest.shipments.create') }}" 
                 class="btn btn-sm btn-primary elevation-2">
                 <i class="fas fa-plus"></i>
              </a>
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              @include('table.ajax')
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
    function getDataAjax() {
      $('#dataAjax').DataTable().destroy();

      $.ajax({
        url: "{{ url()->current() }}",
        type: "GET",
        success:function(msg){
          console.log(msg.data);
          $('#dataAjax').DataTable({
            data: msg.data,
            columns:[
              @forelse ($items as $keys => $item)
                @if($keys == 'id')
                  {data:"DT_RowIndex", name: "DT_RowIndex", searchable: false},
                @elseif(in_array($keys, ['ArrivalDate', 'ExitDate']))
                {
                  data: {
                    _: "{{ $keys }}.display",
                    sort: "{{ $keys }}.timestamp",
                  }
                },
                @elseif($keys == 'NO_MASTER_BLAWB')
                {
                  data: {
                    _: "{{ $keys }}.display",
                    filter: "{{ $keys }}.filter", 
                  }
                },
                @elseif($keys == 'AL_PENERIMA')
                {
                  data: "{{ $keys }}",
                  defaultContent: '-',
                  render:function(data, type, row){
                    if( type === 'display'){
                      return (data != null && data.length > 30) ?
                              data.substr( 0, 30 ) +'…' :
                              data;
                    } else if ( type === 'export') {
                      return data;
                    }
                  }
                },
                @else
                {data: "{{$keys}}", name: "{{$keys}}"},
                @endif
              @empty
              @endforelse          
            ],
            // columnDefs: [ {
            //     targets: 5,
            //     render: function ( data, type, row ) {
            //       return (data && data.length > 30) ?
            //                   data.substr( 0, 30 ) +'…' :
            //                   data;
            //     }
            // } ],
            buttons: [                
                {
                  extend: 'excelHtml5',
                  exportOptions: { orthogonal: 'export' }
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    exportOptions: { orthogonal: 'export' }
                },
                {
                  extend: 'print',
                  exportOptions: { orthogonal: 'export' }
                },
            ],
            initComplete: function () {
              this.api().columns([2,3,4,5,6,7,8]).every( function () {
                var column = this;
                var select = $('<select class="select2bs4" style="width: 100%;"><option value="">Select...</option></select>')
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
            }, 
          }).buttons().container().appendTo('#dataAjax_wrapper .col-md-6:eq(0)');
          
          select2bs4Clear();
        }
      })
    }
    jQuery(document).ready(function(){
      getDataAjax();
      
    });
  </script>
@endsection
