@extends('layouts.master')

@section('title') Company @endsection
@section('page_name') 
@if($company && $company->GC_Logo)
  <img src="{{ $company->getLogo() }}" 
       alt="Logo Company"
       style="height: 25px; width: auto;">
@else
<i class="fas fa-building"></i> 
@endif
{{ $company->GC_Name ?? 'Company Data' }}
@endsection
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
        <form action="/administrator" 
              method="post" 
              enctype="multipart/form-data">   
        @method('PUT')
        @csrf
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Company Data</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <!-- Left Card -->
                  <div class="col-12 col-md-8">
                    <div class="card card-primary card-outline">
                      <div class="card-header">
                        <h3 class="card-title">Details</h3>
                        <div class="card-tools">
                          <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                          </button>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <!-- Company Name -->
                          <div class="col-12 col-md-9">
                            <div class="form-group form-group-sm">
                              <label for="GC_Name">Company Name</label>
                              <input type="text" name="GC_Name" id="GC_Name"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_Name') ?? $company->GC_Name ?? '' }}"
                                     placeholder="Company Name"
                                     required>
                            </div>
                          </div> 
                          <!-- Company Code -->
                          <div class="col-12 col-md-3">
                            <div class="form-group form-group-sm">
                              <label for="GC_Code">Company Code</label>
                              <input type="text" name="GC_Code" id="GC_Code"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_Code') ?? $company->GC_Code ?? '' }}"
                                     placeholder="Company Code"
                                     required>
                            </div>
                          </div>
                          <!-- Address 1 -->
                          <div class="col-12">                            
                            <div class="form-group form-group-sm">
                              <label for="GC_Address1">Address 1</label>
                              <input type="text" name="GC_Address1" id="GC_Address1"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_Address1') ?? $company->GC_Address1 ?? '' }}"
                                     placeholder="Address 1"
                                     required>
                            </div>
                          </div> 
                          <!-- Company Name -->
                          <div class="col-12">                            
                            <div class="form-group form-group-sm">
                              <label for="GC_Address2">Address 2</label>
                              <input type="text" name="GC_Address2" id="GC_Address2"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_Address2') ?? $company->GC_Address2 ?? '' }}"
                                     placeholder="Address 2">
                            </div>
                          </div>
                          <!-- Country -->
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_RN_NKCountryCode">Country</label>
                              <select name="GC_RN_NKCountryCode" id="GC_RN_NKCountryCode"
                                      style="width: 100%;"
                                      class="select2bs4"
                                      required>
                                <option selected 
                                        value="{{ old('GC_RN_NKCountryCode') ?? $company->GC_RN_NKCountryCode ?? 'ID' }}">
                                  {{ old('GC_RN_NKCountryCode') ?? $company->GC_RN_NKCountryCode ?? 'ID' }}</option>
                              </select>                              
                            </div>
                          </div>
                          <!-- City -->
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_City">City</label>
                              <input type="text" name="GC_City" id="GC_City"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_City') ?? $company->GC_City ?? '' }}"
                                     placeholder="City Name"
                                     required>
                            </div>
                          </div>
                          <!-- State -->
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_State">State</label>
                              <input type="text" name="GC_State" id="GC_State"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_State') ?? $company->GC_State ?? '' }}"
                                     placeholder="State">
                            </div>
                          </div>
                          <!-- Post Code -->
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_PostCode">Post Code</label>
                              <input type="number" name="GC_PostCode" id="GC_PostCode"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_PostCode') ?? $company->GC_PostCode ?? '' }}"
                                     placeholder="Post Code"
                                     required>
                            </div>
                          </div>
                          <!-- Phone -->
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_Phone">Phone</label>
                              <input type="text" name="GC_Phone" id="GC_Phone"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_Phone') ?? $company->GC_Phone ?? '' }}"
                                     placeholder="Phone"
                                     required>
                            </div>
                          </div>
                          <!-- Fax -->
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_Fax">Fax</label>
                              <input type="text" name="GC_Fax" id="GC_Fax"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_Fax') ?? $company->GC_Fax ?? '' }}"
                                     placeholder="Fax No">
                            </div>
                          </div>
                          <!-- Email -->
                          <div class="col-12 col-md-6">
                            <div class="form-group form-group-sm">
                              <label for="GC_Email">Email</label>
                              <input type="email" name="GC_Email" id="GC_Email"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_Email') ?? $company->GC_Email ?? '' }}"
                                     placeholder="Email Address">
                            </div>
                          </div>
                          <!-- Web Address -->
                          <div class="col-12 col-md-6">
                            <div class="form-group form-group-sm">
                              <label for="GC_WebAddress">Web Address</label>
                              <input type="text" name="GC_WebAddress" id="GC_WebAddress"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_WebAddress') ?? $company->GC_WebAddress ?? '' }}"
                                     placeholder="Company Web Address">
                            </div>
                          </div>
                          <!-- Bussiness Reg 1 -->
                          <div class="col-12 col-md-6">
                            <div class="form-group form-group-sm">
                              <label for="GC_BusinessRegNo">Bussiness Reg No</label>
                              <input type="text" name="GC_BusinessRegNo" id="GC_BusinessRegNo"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_BusinessRegNo') ?? $company->GC_BusinessRegNo ?? '' }}"
                                     placeholder="Bussiness Registration Number">
                            </div>
                          </div>
                          <!-- Bussiness Reg 2 -->
                          <div class="col-12 col-md-6">
                            <div class="form-group form-group-sm">
                              <label for="GC_BusinessRegNo2">Bussiness Reg No 2</label>
                              <input type="text" name="GC_BusinessRegNo2" id="GC_BusinessRegNo2"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_BusinessRegNo2') 
                                                ?? $company->GC_BusinessRegNo2 ?? '' }}"
                                     placeholder="Additional Bussiness Registration Number">
                            </div>
                          </div>
                          <!-- Customs Reg -->
                          <div class="col-12 col-md-6">
                            <div class="form-group form-group-sm">
                              <label for="GC_CustomsRegistrationNo">Custom Reg No</label>
                              <input type="text" name="GC_CustomsRegistrationNo" id="GC_CustomsRegistrationNo"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_CustomsRegistrationNo') 
                                              ?? $company->GC_CustomsRegistrationNo ?? '' }}"
                                     placeholder="Customs Registration Number">
                            </div>
                          </div>
                          <!-- Tax ID -->
                          <div class="col-12 col-md-6">
                            <div class="form-group form-group-sm">
                              <label for="GC_TaxID">Tax ID</label>
                              <input type="text" name="GC_TaxID" id="GC_TaxID"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_TaxID') 
                                              ?? $company->GC_TaxID ?? '' }}"
                                     placeholder="Tax ID">
                            </div>
                          </div>
                          <!-- Currency -->
                          <div class="col-12 col-md-6">
                            <div class="form-group form-group-sm">
                              <label for="GC_RX_NKLocalCurrency">Local Currency</label>
                              <select name="GC_RX_NKLocalCurrency" id="GC_RX_NKLocalCurrency"
                                      style="width: 100%;"
                                      class="select2bs4"
                                      required>
                                <option selected 
                                      value="{{ old('GC_RX_NKLocalCurrency') ?? $company->GC_RX_NKLocalCurrency ?? 'IDR' }}">
                                {{ old('GC_RX_NKLocalCurrency') ?? $company->GC_RX_NKLocalCurrency ?? 'IDR' }}</option>
                              </select>
                            </div>
                          </div>
                          <!-- Currency -->
                          <div class="col-12 col-md-6">
                            <div class="form-group form-group-sm">
                              <label for="GC_Logo">Company Logo</label>
                              <input type="file" 
                                     name="GC_Logo" 
                                     id="GC_Logo" 
                                     class="form-control form-control-sm">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>                    
                  </div>                                   
                </div>
              </div>
              <div class="card-footer">
                <div class="row">                  
                  <div class="col-3">
                    <button type="submit" class="btn btn-primary btn-block elevation-2">
                      <i class="fas fa-save"></i> Save
                    </button>
                  </div>              
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection

@section('footer')
  <script>
    jQuery(document).ready(function(){
      //Country Code Select2
      // $('#GC_RN_NKCountryCode').select2({
      //   placeholder: 'Select...',
      //   ajax: {
      //     url: "",
      //     dataType: 'json',
      //     delay: 250,
      //     processResults: function (data) {
      //       return {
      //         results:  $.map(data, function (item) {
      //               return {
      //                   text: item.RN_Code,
      //                   id: item.RN_Code,
      //               }
      //           })
      //       };
      //     },          
      //     cache: true
      //   }        
      // });
       //Currency Select2
      // $('#GC_RX_NKLocalCurrency').select2({
      //   placeholder: 'Select...',
      //   ajax: {
      //     url: "",
      //     dataType: 'json',
      //     delay: 250,
      //     processResults: function (data) {
      //       return {
      //         results:  $.map(data, function (item) {
      //               return {
      //                   text: item.RX_Code,
      //                   id: item.RX_Code,
      //               }
      //           })
      //       };
      //     },          
      //     cache: true
      //   }
      // });
      
    });
  </script>
@endsection