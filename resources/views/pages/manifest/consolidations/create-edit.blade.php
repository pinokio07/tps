@extends('layouts.master')
@section('title') Consolidations @endsection
@section('page_name') Consolidations @endsection
@section('header')
  <style>
    label{
      margin-bottom: 0px !important;
    }
  </style>
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
                    <div class="col-md-8">
                      <div class="card card-primary card-outline">
                        <div class="card-header">
                          <h3 class="card-title">Details</h3>
                        </div>
                        <form id="formDetails" class="form-horizontal">
                          <div class="card-body">
                            <div class="form-group row">
                              <label for="AirlineCode" 
                                     class="col-sm-2 col-form-label">Airline</label>
                              <div class="col-sm-6">
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
                                <input type="hidden" 
                                       name="NM_SARANA_ANGKUT"
                                       id="NM_SARANA_ANGKUT"
                                       value="{{ old('NM_SARANA_ANGKUT') 
                                                 ?? $item->NM_SARANA_ANGKUT
                                                 ?? '' }}">
                              </div>
                              <div class="col-sm-2 text-right">
                                <label for="FlightNo">Flight Number</label>
                              </div>
                              <div class="col-sm-2">
                                <input type="text" 
                                       name="FlightNo" 
                                       id="FlightNo"
                                       class="form-control form-control-sm"
                                       placeholder="Flight No"
                                       value="{{ old('FlightNo')
                                                  ?? $item->FlightNo
                                                  ?? '' }}">
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
                   HOUSES               
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
    function showTab(){
      if(window.location.hash){
        $('a[href="' + window.location.hash + '"]').trigger('click');
      }      
    }
    jQuery(document).ready(function(){ 
      $('.unloco').select2({
        placeholder: 'Select...',
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
                        text: item.RM_TwoCharacterCode + " - "+ item.RM_AirlineName1,
                        id: item.id,
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
      $(document).on('change', '.select2airline', function(){
        var name = $(this).find(':selected').attr('data-name');

        $('#NM_SARANA_ANGKUT').val(name);
      });
      showTab();
      console.log(window.location.hash);
    });
  </script>
@endsection
