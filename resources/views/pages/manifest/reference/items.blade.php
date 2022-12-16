<div class="row">
  <div class="col-lg-4">
    @if($disabled != 'disabled')
    <button id="btnNewItem" class="btn btn-sm btn-primary btn-block elevation-2"
            data-toggle="modal"
            data-target="#modal-item">
      <i class="fas fa-plus"></i> Add Item
    </button>
    @endif
  </div>
</div>
<div class="table-responsive mt-2">
  <table id="tblHSCodes" class="table table-sm table-striped" style="width: 100%">
    <thead>
      <tr>
        @forelse ($headerDetail as $hd)
          <th>{{ $hd }}</th>
        @empty
          
        @endforelse
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>
