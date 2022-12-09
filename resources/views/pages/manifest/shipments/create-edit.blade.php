@extends('layouts.master')
@section('title') Shipments @endsection
@section('page_name') Shipments @endsection

@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    @if (count($errors) > 0)
      <div class="row">
        <div class="col-12">
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
        </div>
      </div>
    @endif
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Shipment</h3>
          </div>

            <div class="card-body">
              <!-- Tab Lists -->
              <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link" id="main-data" data-toggle="pill" href="#main-data-content" role="tab" aria-controls="main-data-content" aria-selected="true">Main Data</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="tab-houses" data-toggle="pill" href="#tab-houses-content" role="tab" aria-controls="tab-houses-content" aria-selected="false">HS Codes</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="tab-response" data-toggle="pill" href="#tab-response-content" role="tab" aria-controls="tab-response-content" aria-selected="false">Response</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="tab-partial" data-toggle="pill" href="#tab-partial-content" role="tab" aria-controls="tab-partial-content" aria-selected="false">Partial</a>
                </li>                
                <li class="nav-item">
                  <a class="nav-link" id="tab-estimasi" data-toggle="pill" href="#tab-estimasi-content" role="tab" aria-controls="tab-estimasi-content" aria-selected="false">Estimasi Billing</a>
                </li>
              </ul>
              <!-- Tab Contents -->
              <div class="tab-content" id="custom-content-above-tabContent">
                <div class="tab-pane fade show active" id="main-data-content" role="tabpanel" aria-labelledby="main-data">
                  
                  <div class="row mt-2">
                    <!-- Organization Details Form -->
                    <div class="col-12">
                      <div class="card card-primary card-outline">
                        {{-- <div class="card-header">
                          <h3 class="card-title">Details</h3>
                        </div> --}}
                        <form id="formDetails"
                              @if($item->id)
                              action="{{ route('houses.update', ['house' => \Crypt::encrypt($item->id)]) }}"
                              @else
                              action="{{ route('houses.store') }}" 
                              @endif
                              method="POST"
                              class="form-horizontal needs-validation"
                              autocomplete="off"
                              novalidate>

                          @csrf

                          @if($item->id)
                            @method('PUT')
                          @endif

                          <div class="card-body">

                            @include('pages.manifest.reference.house')

                          </div>
                          <!-- /.card-body -->
                        </form>
                        <div class="card-footer">
                          <button type="submit" 
                                  class="btn btn-sm btn-success elevation-2"
                                  form="formDetails">
                            <i class="fas fa-save"></i>
                            Save
                          </button>
                          <a href="{{ route('manifest.shipments') }}" 
                             class="btn btn-sm btn-default elevation-2 ml-2">Cancel</a>
                        </div>
                        <!-- /.card-footer -->
                      </div>
                    </div>                   
                  </div>

                </div>
                <div class="tab-pane fade" id="tab-houses-content" role="tabpanel" aria-labelledby="tab-houses">
                  <div class="row mt-2">
                    <div class="col-12">
                      <div class="card card-primary card-outline">
                        <div class="card-header">
                          <h3 class="card-title">HS Codes <span id="detailCodes"></span></h3>
                          <div class="card-tools">
                            <button id="hideHSCodes" type="button" class="btn btn-tool">
                              <i class="fas fa-times"></i>
                            </button>
                          </div>
                        </div>      
                        <div class="card-body">
                          @include('pages.manifest.reference.items')
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="tab-pane fade" id="tab-response-content" role="tabpanel" aria-labelledby="tab-response">
                  <div class="row mt-2">
                    RESPONSE
                  </div>
                </div>

                <div class="tab-pane fade" id="tab-partial-content" role="tabpanel" aria-labelledby="tab-partial">
                  <div class="row mt-2">
                   PARTIAL               
                  </div>
                </div>                

                <div class="tab-pane fade" id="tab-estimasi-content" role="tabpanel" aria-labelledby="tab-estimasi">
                  <div class="row mt-2">
                   ESTIMASI               
                  </div>
                </div>

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

<div class="modal fade" id="modal-item">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Items</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formHSCodes"
              class="form-horizontal" 
              method="post">
          @csrf
          @method('PUT')
          <input type="hidden" name="house_id" id="house_id">
          <!-- HS Code -->
          <div class="form-group row">
            <label for="HS_CODE" 
                   class="col-sm-3 col-form-label">
              HS Code</label>
            <div class="col-sm-9">
              <input type="text" 
                    class="form-control form-control-sm clearable"
                    id="HS_CODE"
                    name="HS_CODE"
                    placeholder="HS Code"
                    required>
            </div>
          </div>
          <!-- Descriptons -->
          <div class="form-group row">
            <label for="UR_BRG" 
                   class="col-sm-3 col-form-label">
              Description</label>
            <div class="col-sm-9">
              <textarea name="UR_BRG" 
                        id="UR_BRG"
                        class="form-control form-control-sm clearable"
                        placeholder="Item Description"
                        rows="3"></textarea>
            </div>
          </div>
          <!-- CIF -->
          <div class="form-group row">
            <label for="CIF" 
                   class="col-sm-3 col-form-label">
              CIF</label>
            <div class="col-sm-4">
              <input type="text" 
                    class="form-control form-control-sm desimal clearable"
                    id="CIF"
                    name="CIF"
                    placeholder="CIF">
            </div>
            <!-- FOB -->
            <label for="FOB" 
                   class="col-sm-1 col-form-label">
              FOB</label>
            <div class="col-sm-4">
              <input type="text" 
                    class="form-control form-control-sm desimal clearable"
                    id="FOB"
                    name="FOB"
                    placeholder="FOB">
            </div>
          </div>
          <!-- CIF -->
          <div class="form-group row">
            <label for="CIF" 
                   class="col-sm-3 col-form-label">
              Tarrif</label>
            <div class="col-sm-3 text-right">
              <input type="text" 
                    class="form-control form-control-sm desimal clearable"
                    id="BM_TRF"
                    name="BM_TRF"
                    placeholder="BM">
              <span>% BM</span>
            </div>
            <div class="col-sm-3 text-right">
              <input type="text" 
                    class="form-control form-control-sm desimal clearable"
                    id="PPN_TRF"
                    name="PPN_TRF"
                    placeholder="PPN">
              <span>% PPN</span>
            </div>
            <div class="col-sm-3 text-right">
              <input type="text" 
                    class="form-control form-control-sm desimal clearable"
                    id="PPH_TRF"
                    name="PPH_TRF"
                    placeholder="PPH">
              <span>% PPH</span>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">Close</button>
        <button type="submit" form="formHSCodes" 
                class="btn btn-lg btn-primary">
          <i class="fas fa-save"></i> Save
        </button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@endsection

@section('footer')
  <script>   
    $(function () {
        $('.onlydate').datetimepicker({
          icons: { time: 'far fa-clock' },
          format: 'DD-MM-YYYY'
        });
        
        $(".tanggal").focus(function () {
          $(this).next('.input-group-append').trigger('click');
        });

        $('.mawb-mask').inputmask({
          mask: "999 9999 9999",
          removeMaskOnSubmit: true
        });        
    });

    function getTblHSCodes(id) {
      $('#tblHSCodes').DataTable().destroy();

      $.ajax({
        url:"{{ route('house-details.index') }}",
        type: "GET",
        data:{
          id:id,
        },
        success:function(msg){

          $('#tblHSCodes').DataTable({
            data:msg.data,
            columns:[
              @forelse ($headerDetail as $keys => $value )
                @if($keys == 'id')
                {data:"DT_RowIndex", name: "DT_RowIndex", searchable: false},
                @elseif($keys == 'actions')
                {data:"actions", searchable: false, className:"text-nowrap"},
                @else
                {data: "{{$keys}}", name: "{{$keys}}"},
                @endif
              @empty                
              @endforelse
            ],
            buttons: [                
                'excelHtml5',
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                },
                'print',
            ],
          }).buttons().container().appendTo('#tblHSCodes_wrapper .col-md-6:eq(0)');

        }
      });
    }

    jQuery(document).ready(function(){
      showTab();
      getTblHSCodes("{{ $item->id }}");

      $('.select2kpbc').select2({
        placeholder: 'Select...',
        allowClear: true,        
        ajax: {
          url: "{{ route('select2.setup.customs-offices') }}",
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.Kdkpbc + " - "+ item.UrKdkpbc,
                        id: item.Kdkpbc,
                    }
                })
            };
          },
          cache: true
        }
      });
      $('.select2organization').select2({
        placeholder: 'Select...',
        ajax: {
          url: "{{ route('select2.setup.organization') }}",          
          dataType: 'json',
          delay: 250,
          data: function (params) {
            var query = {
              q: params.term,
              type: $(this).attr('data-type'),
              // country: $(this).attr('data-country'),
              address: 1
            }

            return query;
          },
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: (item.OH_LegacyCode ?? item.OH_Code)+" - "+item.OH_FullName + " || " + item.OA_Address1,
                        id: item.OH_FullName,
                        name: item.OH_FullName,
                        address: item.OA_Address1,
                        tax: item.OA_TaxID,
                        phone: item.OA_Phone,
                    }
                })
            };
          },
          cache: true
        },
        templateSelection: function(container) {
            $(container.element).attr("data-address", container.address)
                                .attr("data-tax", container.tax)
                                .attr("data-phone", container.phone);
            return container.text;
        }
      });
      $(document).on('change', '.select2organization', function(){
        var target = $(this).attr('data-target');
        var npwp = $(this).attr('data-npwp');
        var phone = $(this).attr('data-phone');
        var address = $(this).find(':selected').attr('data-address');
        var idpenerima = $(this).find(':selected').attr('data-tax');
        var phonepenerima = $(this).find(':selected').attr('data-phone');

        if(address != undefined){
          $('#'+target).val(address.toUpperCase());
        }        
        if(npwp != ''){
          $('#'+npwp).val(idpenerima);
        }
        if(phone != ''){
          $('#'+phone).val(phonepenerima);
        }       

        if(idpenerima != '' && idpenerima != undefined){          
          var count = idpenerima.replace(/[^0-9]/g,'');
          if(count.length > 12){
            var value = 5;
          } else if (count.lenght > 10){
            var value = 0;
          } else if(count.length == 10){
            var value = 1;
          } else {
            var value = 4;
          }
          console.log(count.lenght);
          $('#JNS_ID_PENERIMA').val(value).trigger('change');
        }
      });
      $(document).on('input paste', '.tanggal', function(){
        var tgl = $(this).val();
        var ganti = $(this).attr('data-ganti');
        if(tgl != ''){
          var tanggal = moment(tgl, 'DD-MM-YYYY').format('YYYY-MM-DD');          
        } else {
          var tanggal = '';
        }

        $('#'+ganti).val(tanggal);
        
      });
      $(document).on('click', '.editDetail', function(){
        var id = $(this).attr('data-id');

        $('#formHSCodes').attr('action', '/manifest/house-details/'+id);
        $('#formHSCodes input[name="_method"]').val('PUT');
        $('#formHSCodes #house_id').val($(this).attr('data-house'));
        $('#formHSCodes #HS_CODE').val($(this).attr('data-hs'));
        $('#formHSCodes #UR_BRG').val($(this).attr('data-desc'));
        $('#formHSCodes #CIF').val($(this).attr('data-cif'));
        $('#formHSCodes #FOB').val($(this).attr('data-fob'));
        $('#formHSCodes #BM_TRF').val($(this).attr('data-bm'));
        $('#formHSCodes #PPN_TRF').val($(this).attr('data-ppn'));
        $('#formHSCodes #PPH_TRF').val($(this).attr('data-pph'));
      });
      $(document).on('click', '#btnNewItem', function(){
        $('#formHSCodes').attr('action', '/manifest/house-details');
        $('#formHSCodes input[name="_method"]').val('POST');
        $('.clearable').val('');
      });
      $(document).on('submit', '#formHSCodes', function(e){
        e.preventDefault();

        var form = $(this).serialize();
        var action = $(this).attr('action');

        $('.btn').prop('disabled', 'disabled');

        $.ajax({
          url: action,
          type: "POST",
          data: form,
          success:function(msg){
            console.log(msg);
            if(msg.status == 'OK'){
              toastr.success(msg.message, "Success!", {timeOut: 3000, closeButton: true,progressBar: true});

              $('#modal-item').modal('toggle');
              getTblHSCodes(msg.house);
            } else {
              toastr.error(msg.message, "Failed!", {timeOut: 3000, closeButton: true,progressBar: true});
            }
            
            $('.btn').prop('disabled', false);
          },
          error: function (jqXHR, exception) {
            jsonValue = jQuery.parseJSON( jqXHR.responseText );
            toastr.error(jqXHR.status + ' || ' + jsonValue.message, "Failed!", {timeOut: 3000, closeButton: true,progressBar: true});

            $('.btn').prop('disabled', false);
          }
        })
      });
      $(document).on('click', '.hapusHouse', function(){
        var href = $(this).data('href');		

        Swal.fire({			
          title: 'Are you sure?',			
          html: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'Cancel',
          confirmButtonText: 'Yes, delete!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: href,
              type: "POST",
              data:{
                _token: "{{ csrf_token() }}",
                _method: "DELETE"
              },
              success:function(msg){
                if(msg.status == 'OK'){
                  toastr.success("Delete House Success", "Success!", {timeOut: 3000, closeButton: true,progressBar: true});

                  getTblHouse();

                  $('#collapseHouse').removeClass('show');
                  $('#collapseHSCodes').removeClass('show');
                  $('#collapseResponse').removeClass('show');
                  
                } else {
                  toastr.error(msg.message, "Failed!", {timeOut: 3000, closeButton: true,progressBar: true});
                }
              },
              error:function(jqXHR, exception){
                jsonValue = jQuery.parseJSON( jqXHR.responseText );
                toastr.error(jqXHR.status + ' || ' + jsonValue.message, "Failed!", {timeOut: 3000, closeButton: true,progressBar: true});
              }
            })
          }
        });
      });
      $(document).on('click', '.hapusDetail', function(){
        var href = $(this).data('href');		

        Swal.fire({			
          title: 'Are you sure?',			
          html: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'Cancel',
          confirmButtonText: 'Yes, delete!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: href,
              type: "POST",
              data:{
                _token: "{{ csrf_token() }}",
                _method: "DELETE"
              },
              success:function(msg){
                if(msg.status == 'OK'){
                  toastr.success("Delete House Item Success", "Success!", {timeOut: 3000, closeButton: true,progressBar: true});

                  getTblHSCodes(msg.house);
                } else {
                  toastr.error(msg.message, "Failed!", {timeOut: 3000, closeButton: true,progressBar: true});
                }
              },
              error:function(jqXHR, exception){
                jsonValue = jQuery.parseJSON( jqXHR.responseText );
                toastr.error(jqXHR.status + ' || ' + jsonValue.message, "Failed!", {timeOut: 3000, closeButton: true,progressBar: true});
              }
            })
          }
        });
      });
    });
  </script>
@endsection
