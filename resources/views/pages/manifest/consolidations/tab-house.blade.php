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
            </tr>
          @empty        
          @endforelse
        </table>
      </div>
    </div>
  </div>
</div>