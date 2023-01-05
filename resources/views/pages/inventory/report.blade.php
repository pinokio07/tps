@extends('layouts.master')
@section('title') {{Str::title(Request::segment(1))}} @endsection
@section('page_name') Reports @endsection

@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Reports</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <form id="formReport" 
                  action="{{ url()->current() }}" 
                  method="get"
                  target="_blank">
              <div class="row">                
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="jenis">Jenis</label>
                    <select name="jenis" 
                            id="jenis" 
                            class="custom-select"
                            required>
                      <option selected disabled value="">Select...</option>
                      <option value="barang-keluar">Barang Keluar</option>
                      <option value="barang-masuk">Barang Masuk</option>
                      <option value="tidak-dikuasai">Barang Tidak Dikuasai</option>
                      <option value="monev">Monev</option>
                      <option value="rekap-plp">Rekapitulasi PLP</option>
                      <option value="status-plp">Status PLP</option>
                      <option value="timbun">Timbun</option>
                    </select>
                  </div>                  
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="period">Periode</label>
                    <input type="text" 
                           name="period" 
                           id="period" 
                           class="form-control daterange"
                           required>
                  </div>                  
                </div>                
                <div class="col-lg-1">
                  <button type="submit" 
                          class="btn btn-primary btn-block elevation-2 mt-0 mt-md-4"
                          id="btnFilter">
                    <i class="fas fa-download"></i>
                  </button>
                </div>
              </div>
            </form>   
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
    
  </script>
@endsection
