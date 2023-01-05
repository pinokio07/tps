<div class="row">
  <div class="col-lg-4">
    <div class="card card-danger">
      <div class="card-header">
        <h3 class="card-title">Customs</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <!-- Jenis AJU -->
          <div class="col-12">
            <div class="form-group form-group-sm">
              <label for="JNS_AJU">Jenis AJU</label>
              <select name="JNS_AJU" 
                      id="JNS_AJU" 
                      class="form-control form-control-sm"
                      {{ $disabled }}>
                <option value="1" @selected(old('JNS_AJU') == 1 || $item->JNS_AJU == 1)>
                  CN (Consignment Note)</option>
                <option value="2" @selected(old('JNS_AJU') == 2 || $item->JNS_AJU == 2)>
                  PIBK (Pemberitahuan Impor Barang Khusus)</option>
                <option value="3" @selected(old('JNS_AJU') == 3 || $item->JNS_AJU == 3)>
                  BC 1.4 (Pemberitahuan Pemindahan Penimbunan Barang Kiriman)</option>
                <option value="4" @selected(old('JNS_AJU') == 4 || $item->JNS_AJU == 4)>
                  PIB (Pemberitahuan Impor Barang)</option>
              </select>
            </div>
          </div>
          <!-- Kode PIBK -->
          <div class="col-lg-6">
            <div class="form-group form-group-sm">
              <label for="KD_JNS_PIBK">Kode Jenis PIBK</label>
              <select name="KD_JNS_PIBK" 
                      id="KD_JNS_PIBK" 
                      class="form-control form-control-sm"
                      {{ $disabled }}>
                <option value="1" 
                  @selected(old('KD_JNS_PIBK') == 1 || $item->KD_JNS_PIBK == 1)>
                  Barang Pindahan</option>
                <option value="2" 
                  @selected(old('KD_JNS_PIBK') == 2 || $item->KD_JNS_PIBK == 2)>
                  Barang Kiriman Melalui PJT</option>
                <option value="3" 
                  @selected(old('KD_JNS_PIBK') == 3 || $item->KD_JNS_PIBK == 3)>
                  Barang Impor Sementara dibawa Penumpang</option>
                <option value="4" 
                  @selected(old('KD_JNS_PIBK') == 4 || $item->KD_JNS_PIBK == 4)>
                  Barang Impor Tertentu</option>
                <option value="5" 
                  @selected(old('KD_JNS_PIBK') == 5 || $item->KD_JNS_PIBK == 5)>
                  Barang Pribadi Penumpang</option>
                <option value="6" 
                  @selected(old('KD_JNS_PIBK') == 6 || $item->KD_JNS_PIBK == 6)>
                  Lainnya</option>
              </select>
            </div>
          </div>
          <!-- Kode Kantor -->
          <div class="col-lg-6">
            <div class="form-group form-group-sm">
              <label for="KD_KANTOR">Kode Kantor</label>
              <select name="KD_KANTOR" 
                      id="KD_KANTOR" 
                      class="select2kpbc"
                      style="width: 100%;"
                      disabled>
                @if($item->KPBC)
                <option value="{{ $item->KPBC }}"
                        selected>
                  {{ $item->KPBC }} - {{ $item->customs->UrKdkpbc }}
                </option>
                @elseif($item->KD_KANTOR)
                <option value="{{ $item->KD_KANTOR }}"
                        selected>
                  {{ $item->KD_KANTOR }} - {{ $item->customs->UrKdkpbc }}
                </option>
                @endif
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- SPPB No -->
          <div class="col-lg-6">
            <div class="form-group form-group-sm">
              <label for="SPPBNumber">SPPB No</label>
              <input type="text" 
                    class="form-control form-control-sm" 
                    id="SPPBNumber"
                    name="SPPBNumber"
                    placeholder="Belum SPPB"
                    value="{{ old('SPPBNumber') ?? $item->SPPBNumber ?? "" }}"
                    {{ $disabled }}>
            </div>
          </div>
          <div class="col-lg-6">
            <!-- SPPB Date -->
            <label for="tglsppb">SPPB Date</label>                    
            <div class="input-group input-group-sm date onlydate" 
                  id="datetimepicker4" 
                  data-target-input="nearest">
                @php
                    $sppbDate = old('SPPBDate') 
                                ?? $item->SPPBDate 
                                ?? '';
                    if($sppbDate != ''){
                      $sppbParse = \Carbon\Carbon::parse($sppbDate)->format('d-m-Y');
                    } else {
                      $sppbParse = '';
                    }
                    
                @endphp
              <input type="text" 
                      id="tglsppb"
                      class="form-control datetimepicker-input tanggal"
                      placeholder="SPPB Date"
                      data-target="#datetimepicker4"
                      data-ganti="SPPBDate"
                      value="{{ $sppbParse }}"
                      {{ $disabled }}>
              <div class="input-group-append" 
                    data-target="#datetimepicker4" 
                    data-toggle="datetimepicker">
                <div class="input-group-text">
                  <i class="fa fa-calendar"></i>
                </div>
              </div>
            </div>
            <input type="hidden" 
                    name="SPPBDate" 
                    id="SPPBDate" 
                    class="form-control form-control-sm"
                    value="{{ old('SPPBDate') ?? $item->SPPBDate ?? "" }}"
                    {{ $disabled }}>
          </div>
        </div>
        <div class="row">
          <!-- BC 1.1 No -->
          <div class="col-lg-6">
            <div class="form-group form-group-sm">
              <label for="NO_BC11">BC 1.1 No</label>
              <input type="text" 
                    class="form-control form-control-sm" 
                    id="NO_BC11"
                    value="{{ $item->PUNumber ?? $item->NO_BC11 ?? '' }}"
                    disabled>
            </div>
          </div>
          <!-- Tgl BC 1.1 -->
          <div class="col-lg-6">
            <div class="form-group form-group-sm">
              <label for="TGL_BC11">BC 1.1 Date</label>
              @php
                $bcDate = $item->PUDate 
                          ?? $item->TGL_BC11 
                          ?? '';
                if($bcDate != ''){
                  $bcParse = \Carbon\Carbon::parse($bcDate)->format('d-m-Y');
                } else {
                  $bcParse = '';
                }
                
              @endphp
              <input type="text" 
                    class="form-control form-control-sm" 
                    id="TGL_BC11"
                    value="{{ $bcParse }}"
                    disabled>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- NO POS BC11 -->
          <div class="col-lg-4">
            <div class="form-group form-group-sm">
              <label for="NO_POS_BC11">No POS BC 1.1</label>
              <input type="text" 
                    class="form-control form-control-sm" 
                    id="NO_POS_BC11"
                    value="{{ $item->POSNumber ?? $item->NO_POS_BC11 ?? '' }}"
                    disabled>
            </div>
          </div>
          <!-- NO SUBPOS BC11 -->
          <div class="col-lg-4">
            <div class="form-group form-group-sm">
              <label for="NO_SUBPOS_BC11">No SubPOS BC 1.1</label>
              <input type="text" 
                    class="form-control form-control-sm" 
                    id="NO_SUBPOS_BC11"
                    value="{{ $item->NO_SUBPOS_BC11 ?? '' }}"
                    disabled>
            </div>
          </div>
          <!-- NO SUBSUBPOS BC11 -->
          <div class="col-lg-4">
            <div class="form-group form-group-sm">
              <label for="NO_SUBSUBPOS_BC11"><small><b>No SubSubPOS BC 1.1</b></small></label>
              <input type="text" 
                    class="form-control form-control-sm" 
                    id="NO_SUBSUBPOS_BC11"
                    value="{{ $item->NO_SUBSUBPOS_BC11 ?? '' }}"
                    disabled>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- BCF 1.5 -->
          <div class="col-lg-4">
            <div class="form-group form-group-sm">
              <label for="BCF15_Status">BCF 1.5</label>
              <select name="BCF15_Status" 
                      id="BCF15_Status" 
                      class="form-control form-control-sm"
                      {{ $disabled }}>
                <option value="N"
                  @selected(old('BCF15_Status') == "N" || $item->BCF15_Status == "N")>
                  No</option>
                <option value="Y"
                  @selected(old('BCF15_Status') == "Y" || $item->BCF15_Status == "Y")>
                  Yes</option>                        
              </select>
            </div>
          </div>
          <!-- BCF 1.5 Number -->
          <div class="col-lg-4">
            <div class="form-group form-group-sm">
              <label for="BCF15_Number">BCF 1.5 Number</label>
              <input type="text" 
                    class="form-control form-control-sm" 
                    id="BCF15_Number"
                    name="BCF15_Number"
                    value="{{ old('BCF15_Number') ?? $item->BCF15_Number ?? '' }}"
                    placeholder="BCF 1.5 Number"
                    {{ $disabled }}>
            </div>
          </div>
          <div class="col-lg-4">
            <!-- BCF 1.5 Date -->
            <label for="tglbcf">BCF 1.5 Date</label>
            @php
              $bcfDate = old('BCF15_Date')
                        ?? $item->BCF15_Date 
                        ?? '';
              if($bcfDate != ''){
                $bcfParse = \Carbon\Carbon::parse($bcfDate)->format('d-m-Y');
              } else {
                $bcfParse = '';
              }
              
            @endphp
            <div class="input-group input-group-sm date onlydate" 
                  id="datetimepicker5" 
                  data-target-input="nearest">
              <input type="text" 
                      id="tglbcf"
                      class="form-control datetimepicker-input tanggal"
                      placeholder="BCF 1.5 Date"
                      data-target="#datetimepicker5"                      
                      data-ganti="BCF15_Date"
                      value="{{ $bcfParse }}"
                      {{ $disabled }}>
              <div class="input-group-append" 
                    data-target="#datetimepicker5" 
                    data-toggle="datetimepicker">
                <div class="input-group-text">
                  <i class="fa fa-calendar"></i>
                </div>
              </div>
            </div>
            <input type="hidden" 
                    name="BCF15_Date" 
                    id="BCF15_Date" 
                    class="form-control form-control-sm"
                    value="{{ old('BCF15_Date') ?? $item->BCF15_Date ?? '' }}"
                    {{ $disabled }}>
          </div>
        </div>
        <div class="row">
          <!-- PABEAN No -->
          <div class="col-lg-6">
            <div class="form-group form-group-sm">
              <label for="NO_DAFTAR_PABEAN">Pabean No</label>
              <input type="text" 
                    class="form-control form-control-sm" 
                    id="NO_DAFTAR_PABEAN"
                    name="NO_DAFTAR_PABEAN"
                    placeholder="No Daftar Pabean"
                    value="{{ old('NO_DAFTAR_PABEAN') ?? $item->NO_DAFTAR_PABEAN ?? "" }}"
                    {{ $disabled }}>
            </div>
          </div>
          <div class="col-lg-6">
            <!-- Pabean Date -->
            <label for="tglpabean">Pabean Date</label>                    
            <div class="input-group input-group-sm date onlydate" 
                  id="datetimepicker9" 
                  data-target-input="nearest">
                @php
                    $pabeanDate = old('TGL_DAFTAR_PABEAN') 
                                ?? $item->TGL_DAFTAR_PABEAN 
                                ?? '';
                    if($pabeanDate != ''){
                      $pabeanParse = \Carbon\Carbon::parse($pabeanDate)->format('d-m-Y');
                    } else {
                      $pabeanParse = '';
                    }
                    
                @endphp
              <input type="text" 
                      id="tglsppb"
                      class="form-control datetimepicker-input tanggal"
                      placeholder="SPPB Date"
                      data-target="#datetimepicker9"
                      data-ganti="TGL_DAFTAR_PABEAN"
                      value="{{ $pabeanParse }}"
                      {{ $disabled }}>
              <div class="input-group-append" 
                    data-target="#datetimepicker9" 
                    data-toggle="datetimepicker">
                <div class="input-group-text">
                  <i class="fa fa-calendar"></i>
                </div>
              </div>
            </div>
            <input type="hidden" 
                    name="TGL_DAFTAR_PABEAN" 
                    id="TGL_DAFTAR_PABEAN" 
                    class="form-control form-control-sm"
                    value="{{ old('TGL_DAFTAR_PABEAN') ?? $item->TGL_DAFTAR_PABEAN ?? "" }}"
                    {{ $disabled }}>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Airline</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <!-- Partial -->
          <div class="col-lg-6">
            <div class="form-group form-group-sm">
              <label for="PART_SHIPMENT">Partial</label>
              <select name="PART_SHIPMENT" 
                      id="PART_SHIPMENT" 
                      class="form-control form-control-sm"
                      readonly
                      {{ $disabled }}>
                <option value="0" 
                  @selected($item->Partial == false || optional($item->master)->Partial == false)>
                  No</option>
                <option value="1" 
                  @selected($item->Partial == true || optional($item->master)->Partial == true)>
                  Yes</option>                        
              </select>
            </div>
          </div>
          <!-- Total Partial -->
          <div class="col-lg-6">
            <div class="form-group form-group-sm">
              <label for="TOTAL_PARTIAL">Total Partial</label>
              <input type="text" 
                    class="form-control form-control-sm numeric" 
                    id="TOTAL_PARTIAL"
                    name="TOTAL_PARTIAL"
                    placeholder="Total Partial"
                    {{ $disabled }}
                    @disabled($item->Partial == false && optional($item->master)->Partial == false)>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- NM_PENGANGKUT -->
          <div class="col-12">
            <div class="form-group form-group-sm">
              <label for="NM_PENGANGKUT">Air Line</label>
              <input type="text" 
                    class="form-control form-control-sm" 
                    id="NM_PENGANGKUT"
                    value="{{ old('NM_PENGANGKUT')
                              ?? $item->NM_SARANA_ANGKUT
                              ?? $item->NM_PENGANGKUT
                              ?? '' }}"
                    readonly
                    {{ $disabled }}>
            </div>
          </div>                  
        </div>
        <div class="row">
          <!-- AirlineCode -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="AirlineCode">Air Line Code</label>
              <input type="text" 
                    class="form-control form-control-sm" 
                    id="AirlineCode"
                    value="{{ $item->AirlineCode
                              ?? optional($item->master)->AirlineCode
                              ?? '' }}"
                    readonly
                    {{ $disabled }}>
            </div>
          </div>
          <!-- NO_FLIGHT -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="NO_FLIGHT">Flight No</label>
              <input type="text" 
                    class="form-control form-control-sm" 
                    id="NO_FLIGHT"
                    value="{{ $item->FlightNo
                              ?? $item->NO_FLIGHT
                              ?? '' }}"
                    readonly
                    {{ $disabled }}>
            </div>
          </div> 
        </div>
        <div class="row">
          <!-- TGL_TIBA -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="TGL_TIBA">Arrival Date</label>
              <input type="text" 
                    class="form-control form-control-sm" 
                    id="TGL_TIBA"
                    value="{{ ($item->ArrivalDate) 
                              ? \Carbon\Carbon::parse($item->ArrivalDate)->format('d/m/Y') 
                              : ($item->TGL_TIBA
                                ? \Carbon\Carbon::parse($item->TGL_TIBA)->format('d/m/Y')
                                : '' ) }}"
                    readonly
                    {{ $disabled }}>
            </div>
          </div>
          <!-- JAM_TIBA -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="JAM_TIBA">Arrival Time</label>
              <input type="text" 
                    class="form-control form-control-sm" 
                    id="JAM_TIBA"
                    value="{{ $item->ArrivalTime
                              ?? $item->JAM_TIBA
                              ?? '' }}"
                    readonly
                    {{ $disabled }}>
            </div>
          </div> 
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header bg-secondary">
        <h3 class="card-title">Master & House</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <!-- Master No -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="NO_MASTER_BLAWB">MAWB No</label>
              <input type="text" 
                    class="form-control form-control-sm mawb-mask" 
                    id="NO_MASTER_BLAWB"
                    value="{{ $item->MAWBNumber
                              ?? $item->NO_MASTER_BLAWB
                              ?? '' }}"
                    readonly
                    {{ $disabled }}>
            </div>
          </div>
          <!-- Master Date -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="TGL_MASTER_BLAWB">MAWB Date</label>
              <input type="text" 
                    class="form-control form-control-sm" 
                    id="TGL_MASTER_BLAWB"
                    value="{{ ($item->MAWBDate) 
                              ? \Carbon\Carbon::parse($item->MAWBDate)->format('d/m/Y') 
                              : ($item->TGL_MASTER_BLAWB
                                 ? \Carbon\Carbon::parse($item->TGL_MASTER_BLAWB)->format('d/m/Y')
                                 : '' ) }}"
                    readonly
                    {{ $disabled }}>
            </div>
          </div> 
        </div>
        <div class="row">
          <!-- House No -->
          <div class="col-12">
            <div class="form-group form-group-sm">
              <label for="ShipmentNumber">Shipment Number</label>
              <input type="text" 
                    class="form-control form-control-sm" 
                    id="ShipmentNumber"
                    name="ShipmentNumber"
                    placeholder="Shipment Number"
                    value="{{ old('ShipmentNumber')
                              ?? $item->ShipmentNumber
                              ?? '' }}"
                    required
                    {{ $disabled }}>
            </div>
          </div>
          <!-- House No -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="NO_HOUSE_BLAWB">House No</label>
              <input type="text" 
                    class="form-control form-control-sm" 
                    id="NO_HOUSE_BLAWB"
                    name="NO_HOUSE_BLAWB"
                    placeholder="House Number"
                    value="{{ old('NO_HOUSE_BLAWB')
                              ?? $item->NO_HOUSE_BLAWB
                              ?? '' }}"
                    required
                    {{ $disabled }}>
            </div>
          </div>
          <!-- House Date -->
          <div class="col-6">
            <label for="tglhouse">House Date</label>
            @php
              $houseDate = old('TGL_HOUSE_BLAWB')
                            ?? $item->TGL_HOUSE_BLAWB 
                            ?? '';
              if($houseDate != ''){
                $houseParse = \Carbon\Carbon::parse($houseDate)->format('d-m-Y');
              } else {
                $houseParse = '';
              }
              
            @endphp
            <div class="input-group input-group-sm date onlydate" 
                  id="datetimepicker6" 
                  data-target-input="nearest">
              <input type="text" 
                      id="tglhouse"
                      class="form-control datetimepicker-input tanggal"
                      placeholder="House Date"
                      data-target="#datetimepicker6"
                      data-ganti="TGL_HOUSE_BLAWB"
                      value="{{ $houseParse }}"
                      required
                      {{ $disabled }}>
              <div class="input-group-append" 
                    data-target="#datetimepicker6" 
                    data-toggle="datetimepicker">
                <div class="input-group-text">
                  <i class="fa fa-calendar"></i>
                </div>
              </div>
            </div>
            <input type="hidden" 
                    name="TGL_HOUSE_BLAWB" 
                    id="TGL_HOUSE_BLAWB" 
                    class="form-control form-control-sm"
                    value="{{ old('TGL_HOUSE_BLAWB')
                              ?? $item->TGL_HOUSE_BLAWB
                              ?? '' }}"
                    {{ $disabled }}>
          </div> 
        </div>
        <div class="row">
          <!-- Muat -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="KD_PEL_MUAT">Muat</label>
              <input type="text" 
                    class="form-control form-control-sm" 
                    id="KD_PEL_MUAT"
                    value="{{ $item->Origin
                              ?? $item->KD_PEL_MUAT
                              ?? '' }}"
                    readonly
                    {{ $disabled }}>
            </div>
          </div>
          <!-- Transit -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="KD_PEL_TRANSIT">Transit</label>
              <input type="text" 
                    class="form-control form-control-sm" 
                    id="KD_PEL_TRANSIT"
                    value="{{ $item->Transit
                              ?? $item->KD_PEL_TRANSIT
                              ?? '' }}"
                    readonly
                    {{ $disabled }}>
            </div>
          </div>   
        </div>
        <div class="row">
          <!-- Akhir -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="KD_PEL_AKHIR">Akhir</label>
              <input type="text" 
                    class="form-control form-control-sm" 
                    id="KD_PEL_AKHIR"
                    value="{{ $item->Destination
                              ?? $item->KD_PEL_AKHIR
                              ?? '' }}"
                    readonly
                    {{ $disabled }}>
            </div>
          </div>
          <!-- Bongkar -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="KD_PEL_BONGKAR">Bongkar</label>
              <input type="text" 
                    class="form-control form-control-sm" 
                    id="KD_PEL_BONGKAR"
                    value="{{ $item->Destination
                              ?? $item->KD_PEL_BONGKAR
                              ?? '' }}"
                    readonly
                    {{ $disabled }}>
            </div>
          </div>   
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Pengirim</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <!-- Pengirim -->
          <div class="col-12">
            <div class="form-group form-group-sm">
              <label for="NM_PENGIRIM">Name</label>
              <select name="NM_PENGIRIM" 
                      id="NM_PENGIRIM" 
                      class="select2organization"
                      data-type="OH_IsConsignor"                      
                      data-target="AL_PENGIRIM"
                      data-npwp=""
                      data-phone=""
                      style="width: 100%;"
                      required
                      {{ $disabled }}>
                @if(old('NM_PENGIRIM') || $item->NM_PENGIRIM)
                <option value="{{ old('NM_PENGIRIM') ?? $item->NM_PENGIRIM }}" selected>
                  {{ old('NM_PENGIRIM') ?? $item->NM_PENGIRIM }} || {{ old('AL_PENGIRIM') ?? $item->AL_PENGIRIM }}
                </option>
                @endif
              </select>
            </div>
          </div>
          <!-- Alamat Pengirim -->
          <div class="col-12">
            <div class="form-group form-group-sm">
              <label for="AL_PENGIRIM">Address</label>
              <textarea name="AL_PENGIRIM" 
                        id="AL_PENGIRIM"
                        class="form-control form-control-sm" 
                        placeholder="Alamat Pengirim"
                        {{ $disabled }}
                        rows="3">{{ old('AL_PENGIRIM')
                                    ?? $item->AL_PENGIRIM
                                    ?? '' }}</textarea>
            </div>
          </div>
          <!-- Pengirim -->
          <div class="col-12">
            <div class="form-group form-group-sm">
              <label for="KD_NEG_PENGIRIM">Country Code</label>
              <select name="KD_NEG_PENGIRIM" 
                      id="KD_NEG_PENGIRIM" 
                      class="select2bs4"                              
                      style="width: 100%;"
                      disabled>
                <option value="{{ optional($item->unlocoOrigin)->RL_RN_NKCountryCode ?? $item->KD_NEGARA_ASAL ?? "" }}">
                  {{ optional($item->unlocoOrigin)->RL_RN_NKCountryCode ?? $item->KD_NEGARA_ASAL ?? "" }}
                </option>
              </select>
            </div>
          </div>
        </div>                
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Penerima</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <!-- Penerima -->
          <div class="col-12">
            <div class="form-group form-group-sm">
              <label for="NM_PENERIMA">Name</label>
              <select name="NM_PENERIMA" 
                      id="NM_PENERIMA" 
                      class="select2organization"
                      data-type="OH_IsConsignee"
                      data-target="AL_PENERIMA"
                      data-npwp="NO_ID_PENERIMA"
                      data-phone="TELP_PENERIMA"
                      style="width: 100%;"
                      required
                      {{ $disabled }}>
                @if(old('NM_PENERIMA') || $item->NM_PENERIMA)
                <option value="{{ old('NM_PENERIMA') ?? $item->NM_PENERIMA }}" selected>
                  {{ old('NM_PENERIMA') ?? $item->NM_PENERIMA }} || {{ old('AL_PENERIMA') ?? $item->AL_PENERIMA }}
                </option>
                @endif
              </select>
            </div>
          </div>
          <!-- Alamat Penerima -->
          <div class="col-12">
            <div class="form-group form-group-sm">
              <label for="AL_PENERIMA">Address</label>
              <textarea name="AL_PENERIMA" 
                        id="AL_PENERIMA"
                        class="form-control form-control-sm"
                        placeholder="Alamat Penerima"
                        {{ $disabled }} 
                        rows="3">{{ old('AL_PENERIMA')
                                    ?? $item->AL_PENERIMA
                                    ?? '' }}</textarea>
            </div>
          </div>
          <!-- ID Penerima -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="NO_ID_PENERIMA">ID Penerima</label>
              <input type="text"
                    name="NO_ID_PENERIMA" 
                    id="NO_ID_PENERIMA"
                    class="form-control form-control-sm"
                    placeholder="ID Penerima"
                    value="{{ old('NO_ID_PENERIMA')
                              ?? $item->NO_ID_PENERIMA
                              ?? '' }}"
                    {{ $disabled }}>
            </div>
          </div>
          <!-- Jenis ID -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="JNS_ID_PENERIMA">Jenis ID</label>
              <select name="JNS_ID_PENERIMA" 
                      id="JNS_ID_PENERIMA" 
                      class="custom-select custom-select-sm"
                      {{ $disabled }}>
                <option value="0"
                  @selected( old('JNS_ID_PENERIMA') == 0 
                              || $item->JNS_ID_PENERIMA == 0)>
                  0-NPWP 12 DIGIT</option>
                <option value="1"
                  @selected( old('JNS_ID_PENERIMA') == 1 
                              || $item->JNS_ID_PENERIMA == 1)>
                  1-NPWP 10 DIGIT</option>
                <option value="2"
                  @selected( old('JNS_ID_PENERIMA') == 2 
                              || $item->JNS_ID_PENERIMA == 2)>
                  2-PASPOR</option>
                <option value="3"
                  @selected( old('JNS_ID_PENERIMA') == 3 
                              || $item->JNS_ID_PENERIMA == 3)>
                  3-NIK/KTP</option>
                <option value="4"
                  @selected( old('JNS_ID_PENERIMA') == 4 
                              || $item->JNS_ID_PENERIMA == 4)>
                  4-LAINNYA</option>
                <option value="5"
                  @selected( old('JNS_ID_PENERIMA') == 5 
                              || $item->JNS_ID_PENERIMA == 5)>
                  5-NPWP 15 DIGIT</option>
              </select>
            </div>
          </div>
          <!-- ID Penerima -->
          <div class="col-12">
            <div class="form-group form-group-sm">
              <label for="TELP_PENERIMA">Telpon Penerima</label>
              <input type="text"
                    name="TELP_PENERIMA" 
                    id="TELP_PENERIMA"
                    class="form-control form-control-sm"
                    placeholder="Phone Number"
                    value="{{ old('TELP_PENERIMA')
                              ?? $item->TELP_PENERIMA
                              ?? '' }}"
                    {{ $disabled }}>
            </div>
          </div>
        </div>                
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Pemberitahu</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <!-- Pengirim -->
          <div class="col-12">
            <div class="form-group form-group-sm">
              <label for="NM_PEMBERITAHU">Name</label>
              <input type="text"
                    id="NM_PEMBERITAHU"
                    class="form-control form-control-sm"
                    value="{{ $item->NM_PEMBERITAHU }}"
                    disabled>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group form-group-sm">
              <label for="NO_ID_PEMBERITAHU">NPWP</label>
              <input type="text"
                    id="NO_ID_PEMBERITAHU"
                    class="form-control form-control-sm"
                    value="{{ $item->NPWP
                              ?? $item->NO_ID_PEMBERITAHU
                              ?? '' }}"
                    disabled>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group form-group-sm">
              <label for="AL_PEMBERITAHU">Address</label>
              <textarea id="AL_PEMBERITAHU"
                        class="form-control form-control-sm"
                        rows="3"
                        disabled>{{ $item->AL_PEMBERITAHU }}</textarea>
            </div>
          </div>
        </div>                
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header bg-fuchsia">
        <h3 class="card-title">Berat</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <!-- Netto -->
          <div class="col-lg-4">
            <div class="form-group form-group-sm">
              <label for="NETTO">Netto</label>
              <input type="text"
                    id="NETTO"
                    name="NETTO"
                    class="form-control form-control-sm desimal"
                    placeholder="Netto"
                    value="{{ old('NETTO')
                              ?? $item->NETTO
                              ?? 0 }}"
                    {{ $disabled }}>
            </div>
          </div>
          <!-- Bruto -->
          <div class="col-lg-4">
            <div class="form-group form-group-sm">
              <label for="BRUTO">Bruto</label>
              <input type="text"
                    id="BRUTO"
                    name="BRUTO"
                    class="form-control form-control-sm desimal"
                    placeholder="Bruto"
                    value="{{ old('BRUTO')
                              ?? $item->BRUTO
                              ?? 0 }}"
                    {{ $disabled }}>
            </div>
          </div>
          <!-- Chargable -->
          <div class="col-lg-4">
            <div class="form-group form-group-sm">
              <label for="ChargeableWeight">Chargable</label>
              <input type="text"
                    id="ChargeableWeight"
                    name="ChargeableWeight"
                    class="form-control form-control-sm desimal"
                    placeholder="Chargable Weight"
                    value="{{ old('ChargeableWeight')
                              ?? $item->ChargeableWeight
                              ?? 0 }}"
                    required
                    {{ $disabled }}>
            </div>
          </div>
          <!-- CIF -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="CIF">CIF</label>
              <input type="text"
                    id="CIF"
                    class="form-control form-control-sm desimal"
                    placeholder="0"
                    disabled>
            </div>
          </div>
          <!-- FOB -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="FOB">FOB</label>
              <input type="text"
                    id="FOB"
                    name="FOB"
                    class="form-control form-control-sm desimal"
                    placeholder="FOB"
                    value="{{ old('FOB')
                              ?? $item->FOB
                              ?? 0 }}"
                    {{ $disabled }}>
            </div>
          </div>
          <!-- FREIGHT -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="FREIGHT">FREIGHT</label>
              <input type="text"
                    id="FREIGHT"
                    name="FREIGHT"
                    class="form-control form-control-sm desimal"
                    placeholder="Freight"
                    value="{{ old('FREIGHT')
                              ?? $item->FREIGHT
                              ?? 0 }}"
                    {{ $disabled }}>
            </div>
          </div>
          <!-- VOLUME -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="VOLUME">Volume</label>
              <input type="text"
                    id="VOLUME"
                    name="VOLUME"
                    class="form-control form-control-sm desimal"
                    placeholder="Volume"
                    value="{{ old('VOLUME')
                              ?? $item->VOLUME
                              ?? 0 }}"
                    {{ $disabled }}>
            </div>
          </div>
        </div>                
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header bg-lime">
        <h3 class="card-title">Barang</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <!-- Description -->
          <div class="col-12">
            <div class="form-group form-group-sm">
              <label for="UR_BRG">Description</label>
              <input type="text"
                    id="UR_BRG"
                    name="UR_BRG"
                    class="form-control form-control-sm"
                    placeholder="Uraian Barang"
                    value="{{ old('UR_BRG')
                              ?? optional($item->details)->first()->UR_BRG
                              ?? '' }}"
                    {{ $disabled }}>
            </div>
          </div>
          <!-- ASURANSI -->
          <div class="col-12">
            <div class="form-group form-group-sm">
              <label for="ASURANSI">Asuransi</label>
              <input type="text"
                    id="ASURANSI"
                    name="ASURANSI"
                    class="form-control form-control-sm desimal"
                    placeholder="Asuransi"
                    value="{{ old('ASURANSI')
                              ?? $item->ASURANSI
                              ?? 0 }}"
                    {{ $disabled }}>
            </div>
          </div>
          <!-- JML_BRG -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="JML_BRG">Packages</label>
              <input type="text"
                    id="JML_BRG"
                    name="JML_BRG"
                    class="form-control form-control-sm numeric"
                    placeholder="Packages"
                    value="{{ old('JML_BRG')
                              ?? $item->JML_BRG
                              ?? 0 }}"
                    {{ $disabled }}>
            </div>
          </div>
          <!-- JNS_KMS -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="JNS_KMS">Jenis KMS</label>
              <input type="text"
                    id="JNS_KMS"
                    name="JNS_KMS"
                    class="form-control form-control-sm"
                    placeholder="Jenis KMS"
                    value="{{ old('JNS_KMS')
                              ?? $item->JNS_KMS
                              ?? 0 }}"
                    {{ $disabled }}>
            </div>
          </div>
          <!-- MARKING -->
          <div class="col-12">
            <div class="form-group form-group-sm">
              <label for="MARKING">Marking</label>
              <textarea name="MARKING" 
                        id="MARKING" 
                        rows="3"
                        class="form-control form-control-sm"
                        placeholder="Marking"
                        {{ $disabled }}>{{ old('MARKING')
                                            ?? $item->MARKING 
                                            ?? '' }}</textarea>
            </div>
          </div>                  
        </div>                
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header bg-indigo">
        <h3 class="card-title">Billing & Invoice</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <!-- SCHEMA -->
          <div class="col-12">
            <div class="form-group form-group-sm">
              <label for="tarrif_id">Tariff Schema</label>
              <select name="tariff_id"
                      id="tariff_id"
                      class="select2bs4clear"
                      style="width: 100%"
                      {{ $disabled }}>
                <option value=""></option>
                @forelse ($tariff as $t)
                  <option value="{{ $t->id }}"
                    @selected($t->id == $item->tariff_id)>{{ $t->name }}</option>
                @empty                  
                @endforelse
              </select>
            </div>
          </div>
          <!-- NPWP_BILLING -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="NPWP_BILLING">NPWP</label>
              <input type="text"
                    id="NPWP_BILLING"
                    name="NPWP_BILLING"
                    class="form-control form-control-sm"
                    placeholder="NPWP"
                    value="{{ old('NPWP_BILLING')
                              ?? $item->NPWP_BILLING
                              ?? '' }}"
                    {{ $disabled }}>
            </div>
          </div>
          <!-- NAMA_BILLING -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="NAMA_BILLING">Nama Billing</label>
              <input type="text"
                    id="NAMA_BILLING"
                    name="NAMA_BILLING"
                    class="form-control form-control-sm"
                    placeholder="Nama Billing"
                    value="{{ old('NAMA_BILLING')
                              ?? $item->NAMA_BILLING
                              ?? '' }}"
                    {{ $disabled }}>
            </div>
          </div>
          <!-- NO_INVOICE -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="NO_INVOICE">Invoice No</label>
              <input type="text"
                    id="NO_INVOICE"
                    name="NO_INVOICE"
                    class="form-control form-control-sm"
                    placeholder="Invoice No"
                    value="{{ old('NO_INVOICE')
                              ?? $item->NO_INVOICE
                              ?? '' }}"
                    {{ $disabled }}>
            </div>
          </div>
          <!-- TGL_INVOICE -->
          <div class="col-6">
            <div class="form-group form-group-sm">
              <label for="tglinv">Invoice Date</label>
              @php
                $invDate = old('TGL_INVOICE')
                              ?? $item->TGL_INVOICE 
                              ?? '';
                if($invDate != ''){
                  $invParse = \Carbon\Carbon::parse($invDate)->format('d-m-Y');
                } else {
                  $invParse = '';
                }
                
              @endphp
              <div class="input-group input-group-sm date onlydate" 
                  id="datetimepicker7" 
                  data-target-input="nearest">
              <input type="text" 
                      id="tglinv"
                      class="form-control datetimepicker-input tanggal"
                      placeholder="Invoice Date"
                      data-target="#datetimepicker7"
                      data-ganti="TGL_INVOICE"
                      value="{{ $invParse }}">
              <div class="input-group-append" 
                    data-target="#datetimepicker7" 
                    data-toggle="datetimepicker">
                <div class="input-group-text">
                  <i class="fa fa-calendar"></i>
                </div>
              </div>
            </div>
            <input type="hidden" 
                    name="TGL_INVOICE" 
                    id="TGL_INVOICE" 
                    class="form-control form-control-sm"
                    value="{{ old('TGL_INVOICE')
                              ?? $item->TGL_INVOICE
                              ?? '' }}">
            </div>
          </div>
          <!-- TOT_DIBAYAR -->
          <div class="col-12">
            <div class="form-group form-group-sm">
              <label for="TOT_DIBAYAR">Total Dibayar</label>              
              <input type="text"
                    id="TOT_DIBAYAR"
                    name="TOT_DIBAYAR"
                    class="form-control form-control-sm money"
                    placeholder="Invoice No"
                    value="{{ old('TOT_DIBAYAR')
                              ?? $item->TOT_DIBAYAR
                              ?? 0 }}">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
