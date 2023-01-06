@extends('layouts.master')
@section('title') Consolidations @endsection
@section('page_name') Consolidations @endsection

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
                  <a class="nav-link" id="main-data" data-toggle="pill" href="#main-data-content" role="tab" aria-controls="main-data-content" aria-selected="true">Main Data</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="tab-houses" data-toggle="pill" href="#tab-houses-content" role="tab" aria-controls="tab-houses-content" aria-selected="false">Houses</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="tab-plp" data-toggle="pill" href="#tab-plp-content" role="tab" aria-controls="tab-plp-content" aria-selected="false">PLP</a>
                </li>                
                <li class="nav-item">
                  <a class="nav-link" id="tab-log" data-toggle="pill" href="#tab-log-content" role="tab" aria-controls="tab-log-content" aria-selected="false">Logs</a>
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
                              action="{{ route('manifest.consolidations.update', ['consolidation' => \Crypt::encrypt($item->id)]) }}"
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
                                        required
                                        >
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
                                       readonly
                                       >
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
                                        required
                                        >
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
                                                 ?? '' }}"
                                                 >
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
                                       required
                                       >
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
                                                   ?? '' }}"
                                         >
                                  <div class="input-group-append" 
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
                                                 ?? '' }}"
                                       >
                                <!-- ArrivalTime -->
                                <input type="hidden" 
                                       name="ArrivalTime" 
                                       id="ArrivalTime"
                                       value="{{ old('ArrivalTime')
                                                 ?? $item->ArrivalTime
                                                 ?? '' }}"
                                       >
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
                                        required
                                        >
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
                                        class="select2unloco"
                                        >
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
                                        required
                                        >
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
                              <!-- ConsolNumber -->
                              <label for="ConsolNumber" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     Consolidation Number</label>
                              <div class="col-9 col-lg-3">
                                <input type="text" 
                                       name="ConsolNumber" 
                                       id="ConsolNumber" 
                                       class="form-control form-control-sm"
                                       placeholder="Shipment Number"
                                       value="{{ old('ConsolNumber')
                                                 ?? $item->ConsolNumber
                                                 ?? ''}}"
                                       >
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
                                                 ?? ''}}"
                                       >
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
                                         data-ganti="MAWBDate"
                                         required
                                         value="{{ old('tglmawb')
                                                   ?? $item->date_mawb
                                                   ?? '' }}"
                                         >
                                  <div class="input-group-append" 
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
                                                 ?? ''}}"
                                       >
                              </div>
                              <!-- HAWBCount -->
                              <label for="HAWBCount" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     HAWB Count <span class="text-danger">*</span></label>
                              <div class="col-9 col-lg-1">
                                <input type="text" 
                                       name="HAWBCount" 
                                       id="HAWBCount" 
                                       class="form-control form-control-sm numeric"
                                       placeholder="HAWB Count"
                                       required
                                       value="{{ old('HAWBCount')
                                                 ?? $item->HAWBCount
                                                 ?? ''}}"
                                       >
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
                                       class="form-control form-control-sm numeric"
                                       placeholder="Total Collie"
                                       value="{{ old('mNoOfPackages')
                                                 ?? $item->mNoOfPackages
                                                 ?? 0}}"
                                       >
                              </div>
                              <!-- mGrossWeight -->
                              <label for="mGrossWeight" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     GW</label>
                              <div class="col-9 col-lg-2">
                                <input type="text" 
                                       name="mGrossWeight" 
                                       id="mGrossWeight" 
                                       class="form-control form-control-sm desimal"
                                       placeholder="Gross Weight"
                                       value="{{ old('mGrossWeight')
                                                 ?? $item->mGrossWeight
                                                 ?? 0}}"
                                       >
                              </div>
                              <!-- mChargeableWeight -->
                              <label for="mChargeableWeight" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     CW</label>
                              <div class="col-9 col-lg-2">
                                <input type="text" 
                                       name="mChargeableWeight" 
                                       id="mChargeableWeight" 
                                       class="form-control form-control-sm desimal"
                                       placeholder="Chargable Weight"
                                       value="{{ old('mChargeableWeight')
                                                 ?? $item->mChargeableWeight
                                                 ?? 0}}"
                                       >
                              </div>
                              <!-- Partial -->
                              <label for="Partial" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     Partial</label>
                              <div class="col-9 col-lg-2">
                                <select name="Partial" 
                                        id="Partial" 
                                        class="custom-select custom-select-sm"
                                        >
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
                                                 ?? ''}}"
                                       >
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
                                                ?? ''}}"
                                      >
                              </div>
                              <!-- BC 1.1 Date -->
                              <label for="tglbc" 
                                     class="col-sm-3 col-lg-1 col-form-label">
                                     BC 1.1 Date</label>
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
                                         data-ganti="PUDate"
                                         value="{{ old('tglmawb')
                                                   ?? $item->date_mawb
                                                   ?? '' }}"
                                         >
                                  <div class="input-group-append" 
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
                                                 ?? ''}}"
                                       >
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
                                        style="width: 100%;"
                                        >
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
                                       readonly
                                       value="{{ $item->MasukGudang }}">
                              </div>
                              <!-- No Segel PLP BC -->
                              <label for="NO_SEGEL" 
                                     class="col-sm-3 col-lg-2 col-form-label">
                                     No Segel PLP BC <span class="text-danger">*</span></label>
                              <div class="col-9 col-lg-2">
                                <input type="text" 
                                       name="NO_SEGEL" 
                                       id="NO_SEGEL" 
                                       class="form-control form-control-sm"
                                       placeholder="No Segel PLP Bea Cukai"
                                       value="{{ old('NO_SEGEL')
                                                 ?? $item->NO_SEGEL
                                                 ?? '' }}">
                              </div>
                            </div>
                          </div>
                          <!-- /.card-body -->
                        </form>
                        <div class="card-footer">
                          @if($disabled != 'disabled')
                          <button type="submit" 
                                  class="btn btn-sm btn-success elevation-2"
                                  form="formDetails">
                            <i class="fas fa-save"></i>
                            Save
                          </button>
                          @endif
                          <a href="{{ route('manifest.consolidations') }}" 
                             class="btn btn-sm btn-default elevation-2 ml-2">Cancel</a>
                          @if($item->id
                              && $disabled != 'disabled')
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

                <div class="tab-pane fade" id="tab-plp-content" role="tabpanel" aria-labelledby="tab-plp">
                  <div class="row mt-2">
                   @include('pages.manifest.consolidations.tab-plp')
                  </div>
                </div>

                <div class="tab-pane fade" id="tab-summary-content" role="tabpanel" aria-labelledby="tab-summary">
                  <div class="row mt-2">
                    @include('pages.manifest.consolidations.tab-summary') 
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

                <div class="tab-pane fade" id="tab-log-content" role="tabpanel" aria-labelledby="tab-log">
                  <div class="row mt-2">
                   @include('pages.manifest.reference.logs')               
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
        $('#datetimepicker1').datetimepicker({
          icons: { time: 'far fa-clock' },
          format: 'DD-MM-YYYY HH:mm:ss',
          sideBySide: true,
          allowInputToggle: true,
        });

        $('.withtime').datetimepicker({
          icons: { time: 'far fa-clock' },
          format: 'DD/MM/YYYY HH:mm',
          sideBySide: true,
          allowInputToggle: true,
        });

        $('.onlydate').datetimepicker({
          icons: { time: 'far fa-clock' },
          format: 'DD-MM-YYYY',
          allowInputToggle: true,
        });       

        $('.mawb-mask').inputmask({
          mask: "999-99999999",
          removeMaskOnSubmit: true
        });

        @if($disabled == 'disabled')
          $('input, select, textarea, button[type=submit]').prop('disabled', true);
        @endif
        
    });
    
    function findNpwp() {
      var npwp = $('#mBRANCH').find(':selected').attr('data-npwp');

      $('#NPWP').val(npwp);
      @if(!$item->id)
      $('#KPBC').append('<option value="050100" selected>050100 - KPPBC Soekarno-Hatta</option>').trigger('change');
      @endif
    }
    function getTblHouse() {
      $('#tblHouses').DataTable().destroy();

      $.ajax({
        url: "{{ route('houses.index') }}",
        type: "GET",
        data:{
          id: "{{ $item->id }}",
        },
        success: function(msg){
          $('#tblHouses').DataTable({
            data:msg.data,
            columns:[
              @forelse ($headerHouse as $keys => $value )
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
          }).buttons().container().appendTo('#tblHouses_wrapper .col-md-6:eq(0)');
        }

      })
    }
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
    function getTblLogs(){
      $('#tblLogs').DataTable().destroy();

      $.ajax({
        url: "{{ route('logs.show') }}",
        type: "GET",
        data:{
          type: 'master',
          id: "{{ $item->id }}",
        },
        success: function(msg){
          $('#tblLogs').DataTable({
            data:msg.data,
            columns:[
              {data:"DT_RowIndex", name: "DT_RowIndex", searchable: false, className:"h-10"},
              {data:"created_at", name: "created_at"},
              {data:"user", name: "user"},
              {data:"keterangan", name: "keterangan", searchable: false},
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
          }).buttons().container().appendTo('#tblLogs_wrapper .col-md-6:eq(0)');
        }

      })
    }
    function getTblPlp(){
      $('#tblPlp').DataTable().destroy();

      $.ajax({
        url: "{{ route('logs.plp') }}",
        type: "GET",
        data:{
          id: "{{ $item->id }}",
        },
        success: function(msg){
          $('#tblPlp').DataTable({
            data:msg.data,
            columns:[
              @forelse ($headerPlp as $key => $value)
                @if($key == 'id')
                {data:"DT_RowIndex", name: "DT_RowIndex", searchable: false, className:"h-10"},
                @else
                {data:"{{ $key }}", name: "{{ $key }}"},
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
          }).buttons().container().appendTo('#tblPlp_wrapper .col-md-6:eq(0)');
        }

      })
    }
    
    function calDays() {
      var one = $('#cal_arrival').val();
      var two = $('#cal_out').val();
      
      if(one && two){
        var dayOne = moment(one, "DD/MM/YYYY HH:mm", true);
        var dayTwo = moment(two, "DD/MM/YYYY HH:mm", true);
        var diff = dayTwo.diff(dayOne, 'days');
        if(diff != NaN){
          $('#cal_days').val(diff + 1);
        }
      }
    }
    jQuery(document).ready(function(){ 
      findNpwp();
      showTab();           

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
      $('.select2country').select2({
        placeholder: 'Select...',
        ajax: {
          url: "{{ route('select2.setup.countries') }}",
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.RN_Code + " (" + item.RN_Desc + ")",
                        id: item.RN_Code,
                    }
                })
            };
          },
          cache: true
        }
      });
      $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
        // console.log(e.target);
        switch (e.target.id){
            case "tab-houses":{
                getTblHouse(); 
                break;
            }
            case "tab-plp":{
                getTblPlp();
                break;
            }
            case "tab-log":{
                getTblLogs();
                break;
            }
        }
      });
      $(document).on('change', '.select2airline', function(){
        var name = $(this).find(':selected').attr('data-name');

        $('#NM_SARANA_ANGKUT').val(name.toUpperCase());
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
      $(document).on('input paste', '#arrivals', function(){
        var tgl = $(this).val().split(' ');

        $('#ArrivalDate').val(moment(tgl[0], 'DD-MM-YYYY').format('YYYY-MM-DD'));
        $('#ArrivalTime').val(tgl[1]);
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
      $(document).on('input paste', '.tgltime', function(){
        var tgl = $(this).val();
        var ganti = $(this).attr('data-ganti');
        if(tgl != ''){
          var tanggal = moment(tgl, 'DD-MM-YYYY H:M', true).format('DD/MM/YYYY H:M');          
        } else {
          var tanggal = '';
        }

        $('#'+ganti).val(tanggal).trigger('change');
        
      });
      $(document).on('change', '#MAWBNumber', function(){
        var val = $(this).val().replace(/[^0-9]/gi, '');
        
        if(val.length == 11){
          var end = val.substr(10,1);
          var code = val.substr(3,7);
          var divseven = code / 7;
          var substr = divseven.toString().split('.');
          console.log('substr: '+substr[1]);
          var nbr = (0+'.'+substr[1]) * 7;
          console.log('nbr:' + nbr);
          var checkNum = Math.round(nbr);
          console.log('check:' + checkNum);
          
          if(end != checkNum){
            alert('Please provide a valid MAWB Number!');
          }
        }
      });
      $(document).on('click', '.edit', function(){
        var target = $(this).attr('data-target');
        var id = $(this).attr('data-id');

        $('#collapseHSCodes').removeClass('show');
        $('#collapseResponse').removeClass('show');
        $('#collapseCalculate').removeClass('show');
        $('#'+target).removeClass('show');

        $.ajax({
          url:"/manifest/houses/"+id,
          type: "GET",
          success:function(msg){

            $('#detailHouse').html(msg.NO_HOUSE_BLAWB);

            $('#JNS_AJU').val((msg.JNS_AJU ?? 4)).trigger('change');
            $('#KD_JNS_PIBK').val((msg.KD_JNS_PIBK ?? 6)).trigger('change');
            $('#SPPBNumber').val(msg.SPPBNumber).trigger('change');

            if(msg.SPPBDate){
              var sppbDate = moment(msg.SPPBDate);

              $('#tglsppb').val(sppbDate.format('DD/MM/YYYY')).trigger('change');
              $('#SPPBDate').val(sppbDate.format('YYYY-MM-DD')).trigger('change');
            } else {
              $('#tglsppb').val('').trigger('change');
              $('#SPPBDate').val('').trigger('change');
            }

            if(msg.TGL_BC11){
              var bcDate = moment(msg.TGL_BC11);              
              $('#TGL_BC11').val(bcDate.format('DD-MM-YYYY'));
            } else {              
              $('#TGL_BC11').val('');
            }

            $('#NO_BC11').val(msg.NO_BC11);
            $('#NO_POS_BC11').val(msg.NO_POS_BC11);
            $('#NO_SUBPOS_BC11').val(msg.NO_SUBPOS_BC11);
            $('#NO_SUBSUBPOS_BC11').val(msg.NO_SUBSUBPOS_BC11);

            $('#BCF15_Status').val((msg.BCF15_Status ?? 'N')).trigger('change');
            $('#BCF15_Number').val(msg.BCF15_Number).trigger('change');

            if(msg.BCF15_Date){
              var bcfDate = moment(msg.BCF15_Date);

              $('#tglbcf').val(bcfDate.format('DD/MM/YYYY')).trigger('change');
              $('#BCF15_Date').val(bcfDate.format('YYYY-MM-DD')).trigger('change');
            } else {
              $('#tglbcf').val('').trigger('change');
              $('#BCF15_Date').val('').trigger('change');
            }

            $('#TOTAL_PARTIAL').val(msg.TOTAL_PARTIAL).trigger('change');

            $('#ShipmentNumber').val(msg.ShipmentNumber).trigger('change');
            $('#NO_HOUSE_BLAWB').val(msg.NO_HOUSE_BLAWB).trigger('change');

            if(msg.TGL_HOUSE_BLAWB){
              var houseDate = moment(msg.TGL_HOUSE_BLAWB);

              $('#tglhouse').val(houseDate.format('DD/MM/YYYY')).trigger('change');
              $('#TGL_HOUSE_BLAWB').val(houseDate.format('YYYY-MM-DD')).trigger('change');
            } else {
              $('#tglhouse').val('').trigger('change');
              $('#TGL_HOUSE_BLAWB').val('').trigger('change');
            }

            if(msg.NM_PENGIRIM){
              var optPengirim = '<option value="'+ msg.NM_PENGIRIM +'"'
                                +'data-address="'+ msg.AL_PENGIRIM +'"'
                                +'data-tax="" data-phone="">'
                                + msg.NM_PENGIRIM + ' || ' + msg.AL_PENGIRIM +'</option>';
              $('#NM_PENGIRIM').empty().append(optPengirim);
            } else {
              $('#NM_PENGIRIM').empty()
            }
            
            $('#AL_PENGIRIM').val(msg.AL_PENGIRIM).trigger('change');
            $('#KD_NEG_PENGIRIM').val(msg.KD_NEG_PENGIRIM).trigger('change');

            if(msg.NM_PENERIMA){
              var optPengirim = '<option value="'+ msg.NM_PENERIMA +'"'
                                +'data-address="'+ msg.AL_PENERIMA +'"'
                                +'data-tax="'+ msg.NO_ID_PENERIMA +'"'
                                +'data-phone="'+ msg.TELP_PENERIMA +'">'
                                + msg.NM_PENERIMA + ' || ' + msg.AL_PENERIMA +'</option>';
              $('#NM_PENERIMA').empty().append(optPengirim);
            } else {
              $('#NM_PENERIMA').empty()
            }
            
            $('#AL_PENERIMA').val(msg.AL_PENERIMA).trigger('change');
            $('#NO_ID_PENERIMA').val(msg.NO_ID_PENERIMA).trigger('change');
            $('#JNS_ID_PENERIMA').val((msg.JNS_ID_PENERIMA ?? 0)).trigger('change');
            $('#TELP_PENERIMA').val(msg.TELP_PENERIMA).trigger('change');

            $('#NM_PEMBERITAHU').val(msg.NM_PEMBERITAHU);
            $('#NO_ID_PEMBERITAHU').val(msg.NO_ID_PEMBERITAHU);
            $('#AL_PEMBERITAHU').val(msg.AL_PEMBERITAHU);

            $('#NETTO').val(msg.NETTO).trigger('change');
            $('#BRUTO').val(msg.BRUTO).trigger('change');
            $('#ChargeableWeight').val(msg.ChargeableWeight).trigger('change');
            $('#CIF').val(msg.CIF);
            $('#FOB').val(msg.FOB).trigger('change');
            $('#FREIGHT').val(msg.FREIGHT).trigger('change');
            $('#VOLUME').val(msg.VOLUME).trigger('change');

            if(msg.details.length > 0){
              $('#UR_BRG').val(msg.details[0].UR_BRG).trigger('change');
            } else {
              $('#UR_BRG').val('').trigger('change');
            }
            
            $('#ASURANSI').val(msg.ASURANSI).trigger('change');
            $('#JML_BRG').val(msg.JML_BRG).trigger('change');
            $('#JNS_KMS').val(msg.JNS_KMS).trigger('change');
            $('#MARKING').val(msg.MARKING).trigger('change');

            $('#tariff_id').val(msg.tariff_id).trigger('change');
            $('#NPWP_BILLING').val(msg.NPWP_BILLING).trigger('change');
            $('#NAMA_BILLING').val(msg.NAMA_BILLING).trigger('change');
            $('#NO_INVOICE').val(msg.NO_INVOICE).trigger('change');
            
            if(msg.TGL_INVOICE){
              var invDate = moment(msg.TGL_INVOICE);

              $('#tglinv').val(invDate.format('DD/MM/YYYY')).trigger('change');
              $('#TGL_INVOICE').val(invDate.format('YYYY-MM-DD')).trigger('change');
            } else {
              $('#tglinv').val('').trigger('change');
              $('#TGL_INVOICE').val('').trigger('change');
            }

            $('#TOT_DIBAYAR').val(msg.TOT_DIBAYAR).trigger('change');

            $('#printWithHeader').attr('href', "{{ route('download.manifest.shipments') }}?shipment="+msg.id+"&header=1");
            $('#printNoHeader').attr('href', "{{ route('download.manifest.shipments') }}?shipment="+msg.id+"&header=0");

            $('#'+target).addClass('show');            
            console.log(msg);
          }
        });

        $('#formHouse').attr('action', '/manifest/houses/'+id);

      });
      $(document).on('click', '.codes', function(){
        var target = $(this).attr('data-target');
        var id = $(this).attr('data-id');
        var house = $(this).attr('data-house');
        var code = $(this).attr('data-code');

        $('#collapseHouse').removeClass('show');
        $('#collapseResponse').removeClass('show');
        $('#collapseCalculate').removeClass('show');
        $('#'+target).removeClass('show');

        getTblHSCodes(id);
        $('#detailCodes').html(code);
        $('#formHSCodes #house_id').val(house);
        $('#'+target).addClass('show');
      });
      $(document).on('click', '.response', function(){
        var target = $(this).attr('data-target');
        var id = $(this).attr('data-id');
        var code = $(this).attr('data-code');

        $('#collapseHouse').removeClass('show');
        $('#collapseHSCodes').removeClass('show');
        $('#collapseCalculate').removeClass('show');
        $('#'+target).removeClass('show');

        $('#detailResponse').html(code);

        $('#'+target).addClass('show');

      });
      $(document).on('click', '.calculate', function(){
        var target = $(this).attr('data-target');
        var id = $(this).attr('data-id');
        var code = $(this).attr('data-code');

        $('#collapseHouse').removeClass('show');
        $('#collapseHSCodes').removeClass('show');
        $('#collapseResponse').removeClass('show');
        $('#'+target).removeClass('show');

        $('#detailCalculate').html(code);
        $('#tblIsiCalculate').html('');

        $.ajax({
          url:"/manifest/houses/"+id,
          type: "GET",
          success:function(msg){

            var arrival = "{{ $item->ArrivalDate }} {{ $item->ArrivalTime }}";

            if(arrival != ''){
              var parseArrival = moment(arrival).format('DD/MM/YYYY hh:mm');
              $('#cal_arrival').val(parseArrival).trigger('change');
            }           
            
            if(msg.ExitDate && msg.ExitTime){
              var parseOut = moment(msg.ExitDate + ' ' + msg.ExitTime).format('DD/MM/YYYY HH:mm');

              $('#cal_out').val(parseOut).attr('readonly', true);

              calDays();
            } else {
              $('#cal_out').val('').removeAttr('readonly');
            }

            if(msg.tariff_id){
              $('#cal_tariff').val(msg.tariff_id).trigger('change');
            }

            $('#cal_chargable').val(msg.ChargeableWeight).trigger('change');
            $('#cal_gross').val(msg.BRUTO).trigger('change');

            if(msg.estimated_tariff.length > 0){
              $('#btnShowEstimated').removeClass('d-none');
              $('#btnEstimateH').attr('href', "/manifest/download-calculated/"+id+"?header=1");
              $('#btnEstimateWH').attr('href', "/manifest/download-calculated/"+id+"?header=0");
            } else {
              $('#btnShowEstimated').addClass('d-none');
              $('#btnEstimateH').addClass('d-none');
              $('#btnEstimateWH').addClass('d-none');
            }

            $('#formCalculate').attr('action', "/manifest/calculate/"+id);
            $('#formStoreCalculate').attr('action', "/manifest/save-calculate/"+id);
            
            $('#'+target).addClass('show');

          }
        });

      });
      $(document).on('click', '#hideHouse', function(){
        $('#collapseHouse').removeClass('show');
      });
      $(document).on('click', '#hideHSCodes', function(){
        $('#collapseHSCodes').removeClass('show');
      });
      $(document).on('click', '#hideResponse', function(){
        $('#collapseResponse').removeClass('show');
      });
      $(document).on('click', '#hideCalculate', function(){
        $('#collapseCalculate').removeClass('show');
      });
      $(document).on('submit', '#formHouse', function(e){
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
              toastr.success("Update House Success", "Success!", {timeOut: 3000, closeButton: true,progressBar: true});

              getTblHouse();
              $('#detailHouse').html(msg.house);
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
      $(document).on("change.datetimepicker", '.withtime', function (e) {          
          calDays();
      });
      $(document).on('click', '#btnCalculate', function(){
        $('#show_estimate').val(0);
        $('#show_actual').val(0);

        $('#formCalculate').submit();

        $('.saveCalculation').removeClass('d-none');
      });
      $(document).on('submit', '#formCalculate', function(e){
        e.preventDefault();
        var action = $(this).attr('action');        
        var data = $(this).serialize();

        $('.btn').prop('disabled', 'disabled');
        
        $.ajax({
          url: action,
          type: "GET",
          data: data,
          success:function(msg){
            $('#tblIsiCalculate').html(msg);
            $('.btn').prop('disabled', false);
          },
          error:function(jqXHR){
            jsonValue = jQuery.parseJSON( jqXHR.responseText );
            toastr.error(jqXHR.status + ' || ' + jsonValue.message, "Failed!", {timeOut: 3000, closeButton: true,progressBar: true});

            $('.btn').prop('disabled', false);
          }
        })
      });
      $(document).on('click', '.saveCalculation', function(){
        var estimate = $(this).attr('data-estimate');
        var info = 'Estimated';
        var action = $('#formStoreCalculate').attr('action');
        var data = $('#formStoreCalculate').serialize();

        if(estimate < 1){
          info = 'Actual';
        }

        Swal.fire({			
          title: 'Save '+info+'?',			
          html: "This will replace current data if exists!",
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'Cancel',
          confirmButtonText: 'Yes, calculate!'
        }).then((result) => {
          if (result.value) {
            $('#formStoreCalculate #is_estimate').val(estimate);

            $.ajax({
              url: action,
              type: "POST",
              data:data,
              success:function(msg){
                if(msg.status == 'OK'){
                  toastr.success("Store "+info+" Success", "Success!", {timeOut: 3000, closeButton: true,progressBar: true});
                  if(msg.estimate > 0){
                    $('#btnShowActual').removeClass('d-none');
                  } else {
                    $('#btnShowEstimated').removeClass('d-none');
                  }
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
      $(document).on('click', '#btnShowEstimated', function(){
        $('#show_estimate').val(1);

        $('#formCalculate').submit();

        $('.saveCalculation').addClass('d-none');
      });
      $(document).on('click', '.plp', function(){
        var jenis = $(this).attr('data-jenis');

        $('.btn').prop('disabled', 'disabled');

        $.ajax({
          url: "{{ route('manifest.plp', ['master' => \Crypt::encrypt($item->id)]) }}",
          type: "POST",
          data:{
            _token: "{{ csrf_token() }}",
            jenis: jenis
          },
          success: function(msg){
            if(msg.status == 'OK'){
              toastr.success("Send "+jenis+" Success", "Success!", {timeOut: 3000, closeButton: true,progressBar: true});              
            } else {
              toastr.error(msg.message, "Failed!", {timeOut: 3000, closeButton: true,progressBar: true});
            }
            $('.btn').prop('disabled', false);
            getTblPlp();
          },
          error:function(jqXHR){
            jsonValue = jQuery.parseJSON( jqXHR.responseText );
            toastr.error(jqXHR.status + ' || ' + jsonValue.message, "Failed!", {timeOut: 3000, closeButton: true,progressBar: true});

            $('.btn').prop('disabled', false);
          }
        })
      });
      $('#formDetails').dirty({
        preventLeaving: true,
      });
    });
  </script>
@endsection
