<!-- Main Houses -->
<div class="col-12">
  <div class="card card-success card-outline">
    <div class="card-body">
      <div class="table-responsive">
        <table id="tblHouses" class="table table-sm table-striped" style="width: 100%;">
          <thead>
            <tr>
              @forelse ($headerHouse as $hs)
              <th>{{ $hs }}</th>
              @empty
                
              @endforelse
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- Edit Houses -->
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

      <div class="card-body">

        <form id="formHouse" 
              method="post" 
              autocomplete="off">

          @csrf
          @method('PUT')

          @include('pages.manifest.reference.house')

          <div class="row">
            <div class="col-12">
              @if($disabled != 'disabled')
                <button type="submit" 
                        class="btn btn-sm btn-primary btn-block elevation-2">
                  <i class="fas fa-save"></i> Save
                </button>
              @endif
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- HS Codes -->
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
        @include('pages.manifest.reference.items')
      </div>
    </div>
  </div>
</div>
<!-- Responses -->
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
        @include('pages.manifest.reference.response')
      </div>
    </div>
  </div>
</div>
<!-- Calculate -->
<div class="col-12">
  <div id="collapseCalculate" class="collapse">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">Calculate <span id="detailCalculate"></span></h3>
        <div class="card-tools">
          <button id="hideCalculate" type="button" class="btn btn-tool">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>      
      <div class="card-body">
        @include('pages.manifest.reference.calculate')
      </div>
    </div>
  </div>
</div>