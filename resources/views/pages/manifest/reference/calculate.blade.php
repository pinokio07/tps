<div class="row">
  <div class="col-lg-3">
    <div class="form-group form-group-sm">
      <label for="cal_arrival">Arrivals</label>      
      <input type="text" 
             name="cal_arrival" 
             id="cal_arrival" 
             class="form-control form-control-sm"
             readonly>
    </div>
  </div>
  <div class="col-lg-3">
    <div class="form-group form-group-sm">
      <label for="cal_out">Estimated Exit Date</label>
      <div class="input-group input-group-sm date withtime" 
           id="datetimepicker8" 
           data-target-input="nearest">      
      <input type="text" 
              id="cal_out"
              name="cal_out"
              class="form-control datetimepicker-input tgltime"
              placeholder="Estimated Exit Date"
              data-target="#datetimepicker8">
      <div class="input-group-append" 
            data-target="#datetimepicker8" 
            data-toggle="datetimepicker">
        <div class="input-group-text">
          <i class="fa fa-calendar"></i>
        </div>
      </div>      
    </div>    
    </div>
  </div> 
  <div class="col-lg-3">
    <div class="form-group form-group-sm">
      <label for="cal_days">Estimated Days</label>
      <input type="text" 
             class="form-control form-control-sm" 
             id="cal_days"
             name="cal_days"
             form="formCalculate"
             readonly
             required>
    </div>
  </div> 
</div>
<div class="row">
  <div class="col-lg-3">
    <div class="form-group form-group-sm">
      <label for="cal_tariff">Tariff Schema</label>
      <select name="cal_tariff"
              id="cal_tariff"
              class="select2bs4clear"
              style="width: 100%"
              form="formCalculate"
              required>
        <option value=""></option>
        @forelse ($tariff as $t)
          <option value="{{ $t->id }}"
            @selected($t->id == $item->tariff_id)>{{ $t->name }}</option>
        @empty                  
        @endforelse
      </select>
    </div>
  </div>
  <div class="col-lg-3">
    <div class="form-group form-group-sm">
      <label for="cal_chargable">Chargable Weight</label>
      <input type="text" 
             name="cal_chargable" 
             id="cal_chargable" 
             class="form-control"
             readonly>
    </div>
  </div>
  <div class="col-lg-3">
    <div class="form-group form-group-sm">
      <label for="cal_gross">Gross Weight</label>
      <input type="text" 
             name="cal_gross" 
             id="cal_gross" 
             class="form-control"
             readonly>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-12">
    <form id="formCalculate" method="post">
      @csrf
    </form>
    <button type="submit" 
            id="btnCalculate"
            form="formCalculate" 
            class="btn btn-block btn-warning btn-sm elevation-2">
      <i class="fas fa-calculator"></i> Calculate
    </button>
  </div>
  <div class="col-lg-6 mt-2">
    <button class="btn btn-sm btn-info btn-block elevation-2">
      View Estimated
    </button>
  </div>
  <div class="col-lg-6 mt-2">
    <button class="btn btn-sm btn-success btn-block elevation-2">
      View Actual
    </button>
  </div>
</div>
<div class="row  mt-5">
  <div class="col-12">
    <div class="table-responsive">
      <form id="formStoreCalculate" method="POST">
        <table class="table table-sm">
          <thead>
            <tr>
              <th>Items</th>
              <th>Days</th>
              <th>Vol/Weight</th>
              <th class="text-right">Rate</th>            
              <th class="text-right">Total</th>
            </tr>
          </thead>          
          <tbody id="tblIsiCalculate"></tbody>          
        </table>
      </form>
    </div>
  </div>
  <div class="col-lg-6">
    <button type="button"
            class="btn btn-xm btn-primary btn-block elevation-2">
      Save as Estimated
    </button>
  </div>
  <div class="col-lg-6">
    <button type="button"
            class="btn btn-xm bg-lime btn-block elevation-2">
      Save as Actual
    </button>
  </div>
</div>