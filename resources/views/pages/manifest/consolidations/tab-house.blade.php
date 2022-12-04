<div class="col-12">
  <div class="card card-success card-outline">
    <div class="card-body">
      <div class="table-responsive">
        <table id="tblHouses" class="table table-sm table-striped" style="width: 100%;">
          <thead>
            <tr>
              <th>No</th>
              <th>HAWB Number</th>
              <th>X-Ray Date</th>
              <th>Flight No</th>
              <th>BC 1.1</th>
              <th>POS BC 1.1</th>
              <th>Sub POS BC 1.1</th>
              <th>Consignee</th>
              <th>Total Items</th>
              <th>Gross Weight</th>
              <th>TPSO Gate In</th>
              <th>TPSO Gate Out</th>
              <th>KD Response</th>
              <th>Ket Response</th>
              <th>Actions</th>
            </tr>
          </thead>
          @forelse ($item->houses as $house)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $house->NO_HOUSE_BLAWB ?? "-" }}</td>
              <td>X-RAY Date</td>
              <td>{{ $house->NO_FLIGHT ?? "-" }}</td>
              <td>{{ $house->NO_BC11 ?? "-" }}</td>
              <td>{{ $house->NO_POS_BC11 ?? "-" }}</td>
              <td>{{ $house->NO_SUBPOS_BC11 ?? "-" }}</td>
              <td>{{ $house->NM_PENERIMA ?? "-" }}</td>
              <td>{{ $house->JML_BRG ?? "-" }}</td>
              <td>{{ $item->mGrossWeight ?? "-" }}</td>
              <td>{{ $house->TPS_GateInDateTime ?? "-" }}</td>
              <td>{{ $house->TPS_GateOutDateTime ?? "-" }}</td>
              <td>#{{ $house->BC_CODE ?? "-" }}</td>
              <td>{{ $house->BC_STATUS ?? "-" }}</td>
              <td class="text-nowrap">                
                <button class="btn btn-xs btn-warning elevation-2 edit"
                        data-toggle="tooltip"
                        data-target="collapseHouse"
                        title="Edit"
                        data-id="{{ $house->id }}">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-xs btn-info elevation-2 codes"
                        data-toggle="tooltip"
                        data-target="collapseHSCodes"
                        title="HS Codes"
                        data-id="{{ $house->id }}">
                  <i class="fas fa-clipboard-list"></i>
                </button>
                <button class="btn btn-xs btn-success elevation-2 response"
                        data-toggle="tooltip"
                        data-target="collapseResponse"
                        title="Response"
                        data-id="{{ $house->id }}">
                  <i class="fas fa-sync"></i>
                </button>
                <button class="btn btn-xs btn-danger elevation-2 delete"
                        data-href="{{ route('manifest.houses.destroy', ['house' => $house->id]) }}">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          @empty        
          @endforelse
        </table>
      </div>
    </div>
  </div>
</div>
<div class="col-12">
  <div id="collapseHouse" class="collapse">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">House <span id="detailHouse"></span></h3>
        <div class="card-tools">
          <button id="hideHouse" type="button" class="btn btn-tool">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <form id="formHouse" method="post">

        @csrf
        @method('PUT')

      <div class="card-body">
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
                              class="form-control form-control-sm">
                        <option value="1">
                          CN (Consignment Note)</option>
                        <option value="2">
                          PIBK (Pemberitahuan Impor Barang Khusus)</option>
                        <option value="3">
                          BC 1.4 (Pemberitahuan Pemindahan Penimbunan Barang Kiriman)</option>
                        <option value="4">
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
                              class="form-control form-control-sm">
                        <option value="1">Barang Pindahan</option>
                        <option value="2">Barang Kiriman Melalui PJT</option>
                        <option value="3">Barang Impor Sementara dibawa Penumpang</option>
                        <option value="4">Barang Impor Tertentu</option>
                        <option value="5">Barang Pribadi Penumpang</option>
                        <option value="6">Lainnya</option>
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
                              disabled>
                        @if($item->KPBC)
                        <option value="{{ $item->KPBC }}"
                                selected>
                          {{ $item->KPBC }} - {{ $item->customs->UrKdkpbc }}
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
                             placeholder="Belum SPPB">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <!-- SPPB Date -->
                    <label for="tglsppb">SPPB Date</label>                    
                    <div class="input-group input-group-sm date onlydate" 
                          id="datetimepicker4" 
                          data-target-input="nearest">
                      <input type="text" 
                              id="tglsppb"
                              class="form-control datetimepicker-input tanggal"
                              placeholder="SPPB Date"
                              data-target="#datetimepicker4"
                              data-focus="tglsppb"
                              data-ganti="SPPBDate"
                              value="">
                      <div class="input-group-append tglsppb" 
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
                            value="">
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
                             value="{{ $item->PUNumber ?? '' }}"
                             disabled>
                    </div>
                  </div>
                  <!-- Tgl BC 1.1 -->
                  <div class="col-lg-6">
                    <div class="form-group form-group-sm">
                      <label for="TGL_BC11">BC 1.1 Date</label>
                      <input type="text" 
                             class="form-control form-control-sm" 
                             id="TGL_BC11"
                             value="{{ ($item->PUDate) ? \Carbon\Carbon::parse($item->PUDate)->format('d/m/Y') : '' }}"
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
                             value="{{ $item->POSNumber ?? '' }}"
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
                             readonly>
                    </div>
                  </div>
                  <!-- NO SUBSUBPOS BC11 -->
                  <div class="col-lg-4">
                    <div class="form-group form-group-sm">
                      <label for="NO_SUBSUBPOS_BC11"><small><b>No SubSubPOS BC 1.1</b></small></label>
                      <input type="text" 
                             class="form-control form-control-sm" 
                             id="NO_SUBSUBPOS_BC11"
                             readonly>
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
                              class="form-control form-control-sm">
                        <option value="N">No</option>
                        <option value="Y">Yes</option>                        
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
                             value=""
                             placeholder="BCF 1.5 Number">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <!-- BCF 1.5 Date -->
                    <label for="tglbcf">BCF 1.5 Date</label>                    
                    <div class="input-group input-group-sm date onlydate" 
                          id="datetimepicker5" 
                          data-target-input="nearest">
                      <input type="text" 
                              id="tglbcf"
                              class="form-control datetimepicker-input tanggal"
                              placeholder="SPPB Date"
                              data-target="#datetimepicker5"
                              data-focus="tglbcf"
                              data-ganti="BCF15_Date"
                              value="">
                      <div class="input-group-append tglbcf" 
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
                            value="">
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
                              readonly>
                        <option value="0" 
                          @selected($item->Partial == false)>
                          No</option>
                        <option value="1" 
                          @selected($item->Partial == true)>
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
                             @if($item->Partial == false) disabled @endif>
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
                             value="{{ $item->NM_SARANA_ANGKUT }}"
                             readonly>
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
                             value="{{ $item->AirlineCode }}"
                             readonly>
                    </div>
                  </div>
                  <!-- NO_FLIGHT -->
                  <div class="col-6">
                    <div class="form-group form-group-sm">
                      <label for="NO_FLIGHT">Flight No</label>
                      <input type="text" 
                             class="form-control form-control-sm" 
                             id="NO_FLIGHT"
                             value="{{ $item->FlightNo }}"
                             readonly>
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
                             value="{{ ($item->ArrivalDate) ? \Carbon\Carbon::parse($item->ArrivalDate)->format('d/m/Y') : '' }}"
                             readonly>
                    </div>
                  </div>
                  <!-- JAM_TIBA -->
                  <div class="col-6">
                    <div class="form-group form-group-sm">
                      <label for="JAM_TIBA">Arrival Time</label>
                      <input type="text" 
                             class="form-control form-control-sm" 
                             id="JAM_TIBA"
                             value="{{ $item->ArrivalTime }}"
                             readonly>
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
                             value="{{ $item->MAWBNumber }}"
                             readonly>
                    </div>
                  </div>
                  <!-- Master Date -->
                  <div class="col-6">
                    <div class="form-group form-group-sm">
                      <label for="TGL_MASTER_BLAWB">MAWB Date</label>
                      <input type="text" 
                             class="form-control form-control-sm" 
                             id="TGL_MASTER_BLAWB"
                             value="{{ ($item->MAWBDate) ? \Carbon\Carbon::parse($item->MAWBDate)->format('d/m/Y') : '' }}"
                             readonly>
                    </div>
                  </div> 
                </div>
                <div class="row">
                  <!-- House No -->
                  <div class="col-6">
                    <div class="form-group form-group-sm">
                      <label for="NO_HOUSE_BLAWB">House No</label>
                      <input type="text" 
                             class="form-control form-control-sm" 
                             id="NO_HOUSE_BLAWB"
                             name="NO_HOUSE_BLAWB"
                             placeholder="House Number"
                             required>
                    </div>
                  </div>
                  <!-- House Date -->
                  <div class="col-6">
                    <label for="tglhouse">House Date</label>                    
                    <div class="input-group input-group-sm date onlydate" 
                          id="datetimepicker6" 
                          data-target-input="nearest">
                      <input type="text" 
                              id="tglhouse"
                              class="form-control datetimepicker-input tanggal"
                              placeholder="House Date"
                              data-target="#datetimepicker6"
                              data-focus="tglhouse"
                              data-ganti="TGL_HOUSE_BLAWB"
                              value="">
                      <div class="input-group-append tglhouse" 
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
                            value="">
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
                             value="{{ $item->Origin }}"
                             readonly>
                    </div>
                  </div>
                  <!-- Transit -->
                  <div class="col-6">
                    <div class="form-group form-group-sm">
                      <label for="KD_PEL_TRANSIT">Transit</label>
                      <input type="text" 
                             class="form-control form-control-sm" 
                             id="KD_PEL_TRANSIT"
                             value="{{ $item->Transit }}"
                             readonly>
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
                             value="{{ $item->Destination }}"
                             readonly>
                    </div>
                  </div>
                  <!-- Bongkar -->
                  <div class="col-6">
                    <div class="form-group form-group-sm">
                      <label for="KD_PEL_BONGKAR">Bongkar</label>
                      <input type="text" 
                             class="form-control form-control-sm" 
                             id="KD_PEL_BONGKAR"
                             value="{{ $item->Destination }}"
                             readonly>
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
                              data-country="{{ $item->unlocoOrigin->RL_RN_NKCountryCode }}"
                              data-target="AL_PENGIRIM"
                              data-npwp=""
                              data-phone=""
                              style="width: 100%;"
                              required>
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
                                rows="3"></textarea>
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
                        <option value="{{ $item->unlocoOrigin->RL_RN_NKCountryCode }}">
                          {{ $item->unlocoOrigin->RL_RN_NKCountryCode }}
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
                              data-country="ID"
                              data-target="AL_PENERIMA"
                              data-npwp="NO_ID_PENERIMA"
                              data-phone="TELP_PENERIMA"
                              style="width: 100%;"
                              required>
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
                                rows="3"></textarea>
                    </div>
                  </div>
                  <!-- ID Penerima -->
                  <div class="col-6">
                    <div class="form-group form-group-sm">
                      <label for="NO_ID_PENERIMA">ID Penerima</label>
                      <input type="text"
                             name="NO_ID_PENERIMA" 
                             id="NO_ID_PENERIMA"
                             class="form-control form-control-sm">
                    </div>
                  </div>
                  <!-- Jenis ID -->
                  <div class="col-6">
                    <div class="form-group form-group-sm">
                      <label for="JNS_ID_PENERIMA">Jenis ID</label>
                      <select name="JNS_ID_PENERIMA" 
                              id="JNS_ID_PENERIMA" 
                              class="custom-select custom-select-sm">
                        <option value="0">0-NPWP 12 DIGIT</option>
                        <option value="1">1-NPWP 10 DIGIT</option>
                        <option value="2">2-PASPOR</option>
                        <option value="3">3-NIK/KTP</option>
                        <option value="4">4-LAINNYA</option>
                        <option value="5">5-NPWP 15 DIGIT</option>
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
                             placeholder="Phone Number">
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
                      
                    </div>
                  </div>
                </div>                
              </div>
            </div>
          </div>
        </div>
      </div>

      </form>
    </div>
  </div>
</div>
<div class="col-12">
  <div id="collapseHSCodes" class="collapse">
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
        ITEMS
      </div>
    </div>
  </div>
</div>
<div class="col-12">
  <div id="collapseResponse" class="collapse">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">Responses <span id="detailResponse"></span></h3>
        <div class="card-tools">
          <button id="hideResponse" type="button" class="btn btn-tool">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>      
      <div class="card-body">
        RESPONSES
      </div>
    </div>
  </div>
</div>