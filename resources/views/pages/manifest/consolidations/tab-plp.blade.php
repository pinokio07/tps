<div class="col-12">
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title">PLP Online</h3>
    </div>
    <div class="card-body">
      <div class="row">
        @if(!$item->PUNumber)

        @endif
        @if($item->pendingPlp->isEmpty()
            && $item->approvedPlp->isEmpty()
            && $item->pendingBatalPlp->isEmpty()
            && $item->PUNumber != '')
        <div class="col-6">
          <button id="sendRequestPlp"
                  data-jenis="plp-request"
                  class="btn btn-sm btn-success btn-block elevation-2 plp">
            <i class="fas fa-paper-plane"></i> Request PLP
          </button>
        </div>
        @endif
        @if(!$item->pendingPlp->isEmpty())
        <div class="col-6">
          <button id="sendResponsePlp"
                  data-jenis="plp-response"
                  class="btn btn-sm btn-info btn-block elevation-2 plp">
            <i class="fas fa-sync-alt"></i> Get Response
          </button>
        </div>
        @endif
        @if(!$item->approvedPlp->isEmpty())
        <div class="col-6">
          <button id="sendRequestBatalPlp"
                  data-jenis="plp-batal"
                  class="btn btn-sm btn-danger btn-block elevation-2 plp">
            <i class="fas fa-paper-plane"></i> Request Batal PLP
          </button>
        </div>
        @endif
        @if(!$item->pendingBatalPlp->isEmpty())
        <div class="col-6">
          <button id="sendResponseBatalPlp"
                  data-jenis="plp-resbatal"
                  class="btn btn-sm btn-warning btn-block elevation-2 plp">
            <i class="fas fa-paper-plane"></i> Get Response Batal
          </button>
        </div>
        @endif
      </div>
      <div class="row mt-4">
        <div class="col-12">
          <div class="table-responsive">
            <table id="tblPlp" class="table table-sm" style="width:100%;">
              <thead>
                <tr>
                  @forelse ($headerPlp as $hplp)
                    <th>
                      @if($hplp == 'id')
                      No
                      @else
                      {{ $hplp }}
                      @endif
                    </th>
                  @empty                    
                  @endforelse
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>  