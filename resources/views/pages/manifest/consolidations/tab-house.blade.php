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

              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Airline</h3>
              </div>
              <div class="card-body">

              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card">
              <div class="card-header bg-secondary">
                <h3 class="card-title">Master & House</h3>
              </div>
              <div class="card-body">

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