@extends('layouts.master')
@section('title') Consolidations @endsection
@section('page_name') Consolidations @endsection

@section('header')
  <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

  <script src="{{ asset('adminlte') }}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="{{ asset('adminlte') }}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="{{ asset('adminlte') }}/plugins/jszip/jszip.min.js"></script>
  <script src="{{ asset('adminlte') }}/plugins/pdfmake/pdfmake.min.js"></script>
  <script src="{{ asset('adminlte') }}/plugins/pdfmake/vfs_fonts.js"></script>
  <script src="{{ asset('adminlte') }}/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="{{ asset('adminlte') }}/plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="{{ asset('adminlte') }}/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
@endsection

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
            <h3 class="card-title">Consolidations</h3>
          </div>

            <div class="card-body">
              <!-- Tab Lists -->
              <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="main-data" data-toggle="pill" href="#main-data-content" role="tab" aria-controls="main-data-content" aria-selected="true">Main Data</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="tab-houses" data-toggle="pill" href="#tab-houses-content" role="tab" aria-controls="tab-houses-content" aria-selected="false">Houses</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="tab-summary" data-toggle="pill" href="#tab-summary-content" role="tab" aria-controls="tab-summary-content" aria-selected="false">Summary</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="tab-document" data-toggle="pill" href="#tab-document-content" role="tab" aria-controls="tab-document-content" aria-selected="false">Documents</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="tab-kirim-data" data-toggle="pill" href="#tab-kirim-data-content" role="tab" aria-controls="tab-kirim-data-content" aria-selected="false">Kirim Data</a>
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
                              action="{{ route('manifest.consolidations.update', ['consolidation' => $item->id]) }}"
                              @else
                              action="{{ route('manifest.consolidations.store') }}" 
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
                            <div class="form-group row">
                              <!-- KPBC -->
                              <label for="KPBC" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     KPBC <span class="text-danger">*</span></label>
                              <div class="col-9 col-lg-3">
                                <select name="KPBC" 
                                        id="KPBC" 
                                        style="width: 100%;"
                                        class="select2kpbc"
                                        required>
                                  @if($item->KPBC)
                                  <option value="{{ $item->KPBC }}"
                                          selected>
                                    {{ $item->KPBC }} - {{ $item->customs->UrKdkpbc }}
                                  </option>
                                  @endif
                                </select>                                
                              </div>
                              <!-- KPBC -->
                              <label for="mBRANCH" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     Company <span class="text-danger">*</span></label>
                              <div class="col-9 col-lg-3">
                                <select name="mBRANCH" 
                                        id="mBRANCH" 
                                        style="width: 100%;"
                                        class="select2bs4"
                                        required>
                                 @forelse (auth()->user()->branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        @selected($item->mBRANCH == $branch->id)
                                        data-npwp="{{ $branch->company->GC_TaxID }}">
                                      {{ $branch->company->GC_Name }} | {{ $branch->CB_Code }}
                                    </option>
                                 @empty
                                   
                                 @endforelse
                                </select>
                              </div>
                              <!-- NPWP -->
                              <label for="NPWP" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     NPWP <span class="text-danger">*</span></label>
                              <div class="col-9 col-lg-3">
                                <input type="text" 
                                       name="NPWP" 
                                       id="NPWP" 
                                       class="form-control form-control-sm"
                                       placeholder="NPWP"
                                       readonly>
                              </div>
                            </div>
                            <div class="form-group row">
                              <!-- AirlineCode -->
                              <label for="AirlineCode" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     Airline <span class="text-danger">*</span></label>
                              <div class="col-9 col-lg-4">
                                <select name="AirlineCode" 
                                        id="AirlineCode" 
                                        style="width: 100%;"
                                        class="select2airline"
                                        required>
                                  @if($item->AirlineCode)
                                  <option value="{{ $item->AirlineCode }}" 
                                          data-name="{{ $item->NM_SARANA_ANGKUT }}"
                                          selected>
                                    {{ $item->AirlineCode }} - {{ $item->NM_SARANA_ANGKUT }}
                                  </option>
                                  @endif
                                </select>
                                <!-- NM_SARANA_ANGKUT -->
                                <input type="hidden" 
                                       name="NM_SARANA_ANGKUT"
                                       id="NM_SARANA_ANGKUT"
                                       value="{{ old('NM_SARANA_ANGKUT') 
                                                 ?? $item->NM_SARANA_ANGKUT
                                                 ?? '' }}">
                              </div>
                              <!-- FlightNo -->
                              <label class="col-sm-3 col-lg-1" for="FlightNo">
                                Flight No <span class="text-danger">*</span></label>
                              <div class="col-9 col-lg-2">
                                <input type="text" 
                                       name="FlightNo" 
                                       id="FlightNo"
                                       class="form-control form-control-sm"
                                       placeholder="Flight No"
                                       value="{{ old('FlightNo')
                                                  ?? $item->FlightNo
                                                  ?? '' }}"
                                       required>
                              </div>
                              <!-- Arrivals -->
                              <label class="col-sm-3 col-lg-1" for="arrivals">
                                Arrivals <span class="text-danger">*</span></label>
                              <div class="col-9 col-lg-2 mt-1 mt-md-0">
                                <div class="input-group input-group-sm date" 
                                     id="datetimepicker1" 
                                     data-target-input="nearest">
                                  <input type="text" 
                                         id="arrivals"
                                         name="arrivals"
                                         class="form-control datetimepicker-input" 
                                         placeholder="Arrival Date"
                                         data-target="#datetimepicker1"
                                         required
                                         value="{{ old('arrivals')
                                                   ?? $item->arrivals
                                                   ?? '' }}">
                                  <div class="input-group-append arrivals" 
                                       data-target="#datetimepicker1" 
                                       data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                      <i class="fa fa-calendar"></i>
                                    </div>
                                  </div>
                                </div>
                                <!-- ArrivalDate -->
                                <input type="hidden" 
                                       name="ArrivalDate" 
                                       id="ArrivalDate"
                                       value="{{ old('ArrivalDate')
                                                 ?? $item->ArrivalDate
                                                 ?? '' }}">
                                <!-- ArrivalTime -->
                                <input type="hidden" 
                                       name="ArrivalTime" 
                                       id="ArrivalTime"
                                       value="{{ old('ArrivalTime')
                                                 ?? $item->ArrivalTime
                                                 ?? '' }}">
                              </div>
                            </div>
                            <div class="form-group row">
                              <!-- Origin -->
                              <label for="Origin" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     Origin <span class="text-danger">*</span></label>
                              <div class="col-9 col-lg-3">
                                <select name="Origin" 
                                        id="Origin" 
                                        style="width: 100%;"
                                        class="select2unloco"
                                        required>
                                  @if($item->Origin)
                                  <option value="{{ $item->Origin }}"
                                          selected>
                                    {{ $item->unlocoOrigin->RL_Code
                                        . " - " 
                                        . $item->unlocoOrigin->RL_PortName
                                        . " ( "
                                        . $item->unlocoOrigin->RL_RN_NKCountryCode
                                        . " )" }}
                                  </option>
                                  @endif
                                </select>                                
                              </div>
                              <!-- Transit -->
                              <label for="Transit" 
                                     class="col-sm-3 col-lg-1 col-form-label">Transit</label>
                              <div class="col-9 col-lg-3">
                                <select name="Transit" 
                                        id="Transit" 
                                        style="width: 100%;"
                                        class="select2unloco">
                                  @if($item->Transit)
                                  <option value="{{ $item->Transit }}"
                                          selected>
                                    {{ $item->unlocoTransit->RL_Code
                                        . " - " 
                                        . $item->unlocoTransit->RL_PortName
                                        . " ( "
                                        . $item->unlocoTransit->RL_RN_NKCountryCode
                                        . " )" }}
                                  </option>
                                  @endif
                                </select>                                
                              </div>
                              <!-- Destination -->
                              <label for="Destination" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     Destination <span class="text-danger">*</span></label>
                              <div class="col-9 col-lg-3">
                                <select name="Destination" 
                                        id="Destination" 
                                        style="width: 100%;"
                                        class="select2unloco"
                                        required>
                                  @if($item->Destination)
                                  <option value="{{ $item->Destination }}"
                                          selected>
                                    {{ $item->unlocoDestination->RL_Code
                                        . " - " 
                                        . $item->unlocoDestination->RL_PortName
                                        . " ( "
                                        . $item->unlocoDestination->RL_RN_NKCountryCode
                                        . " )" }}
                                  </option>
                                  @endif
                                </select>                                
                              </div>
                            </div>
                            <div class="form-group row">
                              <!-- ShipmentNumber -->
                              <label for="ShipmentNumber" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     Shipment No</label>
                              <div class="col-9 col-lg-3">
                                <input type="text" 
                                       name="ShipmentNumber" 
                                       id="ShipmentNumber" 
                                       class="form-control form-control-sm"
                                       placeholder="Shipment Number"
                                       value="{{ old('ShipmentNumber')
                                                 ?? $item->ShipmentNumber
                                                 ?? ''}}">
                              </div>
                              <!-- MAWBNumber -->
                              <label for="MAWBNumber" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     MAWB No <span class="text-danger">*</span></label>
                              <div class="col-9 col-lg-2">
                                <input type="text" 
                                       name="MAWBNumber" 
                                       id="MAWBNumber" 
                                       class="form-control form-control-sm mawb-mask"
                                       placeholder="MAWB Number"
                                       required
                                       value="{{ old('MAWBNumber')
                                                 ?? $item->MAWBNumber
                                                 ?? ''}}">
                              </div>
                              <!-- MAWBDate -->
                              <label for="MAWBDate" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     MAWB Date <span class="text-danger">*</span></label>
                              <div class="col-9 col-lg-2">
                                <div class="input-group input-group-sm date onlydate" 
                                     id="datetimepicker2" 
                                     data-target-input="nearest">
                                  <input type="text" 
                                         id="tglmawb"
                                         name="tglmawb"
                                         class="form-control datetimepicker-input tanggal"
                                         placeholder="MAWB Date"
                                         data-target="#datetimepicker2"
                                         data-focus="tglmawb"
                                         required
                                         value="{{ old('tglmawb')
                                                   ?? $item->date_mawb
                                                   ?? '' }}">
                                  <div class="input-group-append tglmawb" 
                                       data-target="#datetimepicker2" 
                                       data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                      <i class="fa fa-calendar"></i>
                                    </div>
                                  </div>
                                </div>
                                <input type="hidden" 
                                       name="MAWBDate" 
                                       id="MAWBDate" 
                                       class="form-control form-control-sm"
                                       placeholder="MAWB Date"
                                       value="{{ old('MAWBDate')
                                                 ?? $item->MAWBDate
                                                 ?? ''}}">
                              </div>
                              <!-- HAWBCount -->
                              <label for="HAWBCount" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     HAWB Count <span class="text-danger">*</span></label>
                              <div class="col-9 col-lg-1">
                                <input type="text" 
                                       name="HAWBCount" 
                                       id="HAWBCount" 
                                       class="form-control form-control-sm"
                                       placeholder="HAWB Count"
                                       required
                                       value="{{ old('HAWBCount')
                                                 ?? $item->HAWBCount
                                                 ?? ''}}">
                              </div>
                            </div>
                            <div class="form-group row">
                              <!-- mNoOfPackages -->
                              <label for="mNoOfPackages" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     Total Collie</label>
                              <div class="col-9 col-lg-2">
                                <input type="text" 
                                       name="mNoOfPackages" 
                                       id="mNoOfPackages" 
                                       class="form-control form-control-sm"
                                       placeholder="Total Collie"
                                       value="{{ old('mNoOfPackages')
                                                 ?? $item->mNoOfPackages
                                                 ?? 0}}">
                              </div>
                              <!-- mGrossWeight -->
                              <label for="mGrossWeight" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     GW</label>
                              <div class="col-9 col-lg-2">
                                <input type="text" 
                                       name="mGrossWeight" 
                                       id="mGrossWeight" 
                                       class="form-control form-control-sm berat"
                                       placeholder="Gross Weight"
                                       value="{{ old('mGrossWeight')
                                                 ?? $item->mGrossWeight
                                                 ?? 0}}">
                              </div>
                              <!-- mChargeableWeight -->
                              <label for="mChargeableWeight" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     CW</label>
                              <div class="col-9 col-lg-2">
                                <input type="text" 
                                       name="mChargeableWeight" 
                                       id="mChargeableWeight" 
                                       class="form-control form-control-sm berat"
                                       placeholder="Chargable Weight"
                                       value="{{ old('mChargeableWeight')
                                                 ?? $item->mChargeableWeight
                                                 ?? 0}}">
                              </div>
                              <!-- Partial -->
                              <label for="Partial" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     Partial</label>
                              <div class="col-9 col-lg-2">
                                <select name="Partial" 
                                        id="Partial" 
                                        class="custom-select custom-select-sm">
                                  <option value="0" 
                                    @selected($item->Partial == false)>No</option>
                                  <option value="1" 
                                    @selected($item->Partial == true)>Yes</option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group row">
                              <!-- BC 1.1 -->
                              <label for="PUNumber" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     BC 1.1</label>
                              <div class="col-9 col-lg-3">
                                <input type="text" 
                                       name="PUNumber" 
                                       id="PUNumber" 
                                       class="form-control form-control-sm"
                                       placeholder="BC 1.1 Number"
                                       value="{{ old('PUNumber')
                                                 ?? $item->PUNumber
                                                 ?? ''}}">
                              </div>
                              <!-- POS BC 1.1 -->
                              <label for="POSNumber" 
                                      class="col-sm-3 col-lg-1 col-form-label">
                                      POS BC 1.1</label>
                              <div class="col-9 col-lg-3">
                                <input type="text" 
                                      name="POSNumber" 
                                      id="POSNumber" 
                                      class="form-control form-control-sm"
                                      placeholder="POS BC 1.1"
                                      value="{{ old('POSNumber')
                                                ?? $item->POSNumber
                                                ?? ''}}">
                              </div>
                              <!-- BC 1.1 Date -->
                              <label for="tglbc" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     MAWB Date <span class="text-danger">*</span></label>
                              <div class="col-9 col-lg-2">
                                <div class="input-group input-group-sm date onlydate" 
                                     id="datetimepicker3" 
                                     data-target-input="nearest">
                                  <input type="text" 
                                         id="tglbc"
                                         name="tglbc"
                                         class="form-control datetimepicker-input tanggal"
                                         placeholder="BC 1.1 Date"
                                         data-target="#datetimepicker3"
                                         data-focus="tglbc"
                                         required
                                         value="{{ old('tglmawb')
                                                   ?? $item->date_mawb
                                                   ?? '' }}">
                                  <div class="input-group-append tglbc" 
                                       data-target="#datetimepicker3" 
                                       data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                      <i class="fa fa-calendar"></i>
                                    </div>
                                  </div>
                                </div>
                                <input type="hidden" 
                                       name="PUDate" 
                                       id="PUDate" 
                                       class="form-control form-control-sm"
                                       value="{{ old('PUDate')
                                                 ?? $item->PUDate
                                                 ?? ''}}">
                              </div>
                            </div>
                            <div class="form-group row">
                              <!-- OriginWarehouse -->
                              <label for="OriginWarehouse" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     Line 1 Warehouse </label>
                              <div class="col-9 col-lg-3">
                                <select name="OriginWarehouse" 
                                        id="OriginWarehouse" 
                                        style="width: 100%;">
                                  @if($item->OriginWarehouse)
                                    <option value="{{ $item->OriginWarehouse }}" selected>
                                    {{ $item->OriginWarehouse }} - {{ $item->warehouseLine1->company_name }}
                                    </option>
                                  @endif
                                </select>
                              </div>
                              <!-- Tanggal Masuk Gudang -->
                              <label for="OriginWarehouse" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     Tgl Masuk Gudang </label>
                              <div class="col-9 col-lg-3">
                                <input type="text" 
                                       name="MasukGudang" 
                                       id="MasukGudang" 
                                       class="form-control form-control-sm"
                                       readonly>
                              </div>
                              <!-- No Segel PLP BC -->
                              <label for="NO_SEGEL" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     No Segel PLP BC <span class="text-danger">*</span></label>
                              <div class="col-9 col-lg-3">
                                <input type="text" 
                                       name="NO_SEGEL" 
                                       id="NO_SEGEL" 
                                       class="form-control form-control-sm"
                                       placeholder="No Segel PLP Bea Cukai"
                                       value="{{ old('NO_SEGEL')
                                                 ?? $item->NO_SEGEL
                                                 ?? '' }}"
                                       required>
                              </div>
                            </div>
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
                          <a href="{{ route('manifest.consolidations') }}" 
                             class="btn btn-sm btn-default elevation-2 ml-2">Cancel</a>
                          @if($item->id != '')
                          <a href="{{ route('manifest.consolidations.create') }}" class="btn btn-sm btn-info elevation-2 ml-2">
                            <i class="fas fa-plus"></i> New
                          </a>
                          @endif
                        </div>
                        <!-- /.card-footer -->
                      </div>
                    </div>                   
                  </div>

                </div>
                <div class="tab-pane fade" id="tab-houses-content" role="tabpanel" aria-labelledby="tab-houses">
                  <div class="row mt-2">
                   @include('pages.manifest.consolidations.tab-house')               
                  </div>
                </div>
                <div class="tab-pane fade" id="tab-summary-content" role="tabpanel" aria-labelledby="tab-summary">
                  <div class="row mt-2">
                   SUMMARY               
                  </div>
                </div>
                <div class="tab-pane fade" id="tab-document-content" role="tabpanel" aria-labelledby="tab-document">
                  <div class="row mt-2">
                   DOCUMENTS               
                  </div>
                </div>
                <div class="tab-pane fade" id="tab-kirim-data-content" role="tabpanel" aria-labelledby="tab-kirim-data">
                  <div class="row mt-2">
                   KIRIM DATA               
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
@endsection

@section('footer')
  <script>   
    $(function () {
        $('#datetimepicker1').datetimepicker({
          icons: { time: 'far fa-clock' },
          format: 'DD-MM-YYYY HH:mm:ss'
        });

        $('.onlydate').datetimepicker({
          icons: { time: 'far fa-clock' },
          format: 'DD-MM-YYYY'
        });

        $("#arrivals").focus(function () {
            $('.arrivals').trigger("click");
        });

        $(".tanggal").focus(function () {
          var focus = $(this).attr('data-focus');
          $('.'+focus).trigger("click");
          console.log(focus);
        });

        $('#MAWBNumber').inputmask({
          mask: "999 9999 9999",
          removeMaskOnSubmit: true
        });

        $('.berat').inputmask({
          alias: "decimal",
          rightAlign: false,
          integerDigits: 5,
          digits: 6,
          digitsOptional: false,
          placeholder: "0",
          allowMinus: false
        });
    });
    function showTab(){
      if(window.location.hash){
        $('a[href="' + window.location.hash + '"]').trigger('click');
      }      
    }
    function findNpwp() {
      var npwp = $('#mBRANCH').find(':selected').attr('data-npwp');

      $('#NPWP').val(npwp);
    }
    jQuery(document).ready(function(){ 
      findNpwp();
      showTab();

      $('#tblHouses').DataTable({
        buttons: [                
            'excelHtml5',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            },
            'print',
        ],
      }).buttons().container().appendTo('#tblHouses_wrapper .col-md-6:eq(0)');

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
      $('.select2unloco').select2({
        placeholder: 'Select...',
        allowClear: true,
        ajax: {
          url: "{{ route('select2.setup.unloco') }}",
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.RL_Code + " - "+ item.RL_PortName + " (" + item.RL_RN_NKCountryCode + ")",
                        id: item.RL_Code,
                        code: item.RL_RN_NKCountryCode,
                    }
                })
            };
          },
          cache: true
        }
      });
      $('.select2airline').select2({
        placeholder: 'Select...',
        allowClear: true,
        ajax: {
          url: "{{ route('select2.setup.airlines') }}",
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.RM_TwoCharacterCode + " - "+ item.RM_AirlineName1.toUpperCase(),
                        id: item.RM_TwoCharacterCode,
                        name: item.RM_AirlineName1
                    }
                })
            };
          },
          cache: true
        },
        templateSelection: function(container) {
            $(container.element).attr("data-name", container.name);
            return container.text;
        }
      });
      $('#OriginWarehouse').select2({
        placeholder: 'Select...',
        allowClear: true,
        ajax: {
          url: "{{ route('select2.setup.bonded-warehouses') }}",
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.warehouse_code + " - "+ item.company_name,
                        id: item.warehouse_code
                    }
                })
            };
          },
          cache: true
        },
        templateSelection: function(container) {
            $(container.element).attr("data-name", container.name);
            return container.text;
        }
      });
      $(document).on('change', '.select2airline', function(){
        var name = $(this).find(':selected').attr('data-name');

        $('#NM_SARANA_ANGKUT').val(name.toUpperCase());
      });
      $(document).on('input paste', '#arrivals', function(){
        var tgl = $(this).val().split(' ');

        $('#ArrivalDate').val(moment(tgl[0], 'DD-MM-YYYY').format('YYYY-MM-DD'));
        $('#ArrivalTime').val(tgl[1]);
      });
      $(document).on('input paste', '#tglmawb', function(){
        var tgl = $(this).val();

        $('#MAWBDate').val(moment(tgl, 'DD-MM-YYYY').format('YYYY-MM-DD'));
      });
      $(document).on('input paste', '#tglbc', function(){
        var tgl = $(this).val();

        $('#PUDate').val(moment(tgl, 'DD-MM-YYYY').format('YYYY-MM-DD'));
      });
            
    });
  </script>
@endsection
