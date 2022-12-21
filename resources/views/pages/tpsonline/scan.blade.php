@extends('layouts.master')
@section('title') Scan {{ Str::title($type) }} @endsection
@section('page_name') Scan {{ Str::title($type) }} @endsection

@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    @if (count($errors) > 0)
      <div class="row">
        <div class="col-12">
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
        </div>
      </div>
    @endif
    <div class="row">
      <div class="col-lg-4">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">Scan {{ Str::title($type) }}</h3>
          </div>
          <div class="card-body">
            <form action="{{ route('tps-online.scan-'.$type.'.store') }}" 
                  method="post"
                  class="needs-validation"
                  novalidate
                  autocomplete="off">
              @csrf
              <div class="form-group">
                <label for="NO_HOUSE_BLAWB">HAWB/CN Number</label>
                <input type="text" 
                       name="NO_HOUSE_BLAWB" 
                       id="NO_HOUSE_BLAWB" 
                       class="form-control"
                       value="{{ $item->NO_HOUSE_BLAWB ?? '' }}"
                       required>
              </div>
              <button type="submit" class="btn btn-sm btn-success btn-block elevation-2">
                <i class="fas fa-eyedropper"></i> Scan
              </button>
            </form>
          </div>
        </div>     
      </div>
      <!-- /.col -->
      @if($item->id)
        <div class="col-lg-6">
          <div class="card card-success card-outline">
            <div class="card-header">
              <h3 class="card-title">Response</h3>
            </div>
            <div class="card-body">
              <div class="card-responsive">
                <table class="table table-sm" style="width:100%;">
                  <tr>
                    <td>House Number</td>
                    <td>:</td>
                    <td>{{ $item->NO_HOUSE_BLAWB ?? "-" }}</td>
                  </tr>
                  <tr>
                    <td>Master Number</td>
                    <td>:</td>
                    <td>{{ $item->NO_MASTER_BLAWB ?? "-" }}</td>
                  </tr>
                  <tr>
                    <td>Consignee</td>
                    <td>:</td>
                    <td>{{ $item->NM_PENGIRIM ?? "-" }}</td>
                  </tr>
                  <tr>
                    <td>No of Packages</td>
                    <td>:</td>
                    <td>{{ $item->JML_BRG ?? "-" }}</td>
                  </tr>
                  <tr>
                    <td>Bruto</td>
                    <td>:</td>
                    <td>{{ $item->BRUTO ?? "-" }}</td>
                  </tr>
                </table>
              </div>              
            </div>
          </div>
        </div>
      @endif      
    </div>
    @if($item->id
        && app()->isLocal())
      <div class="row">
        <div class="col-lg-10">
          <div class="card card-success card-outline">
            <div class="card-header">
              <h3 class="card-title">SFTP FILES</h3>
            </div>
            <div class="card-body">
              <div class="card-responsive">                
                <table class="table table-sm" style="width:100%;">
                  @php
                    if($type == 'in'){
                      $file = $item->CW_Ref_GateIn;
                    } else {
                      $file = $item->CW_Ref_GateOut;
                    }
                  @endphp
                  <tr>
                    <td>
                      <a href="{{ route('download.tps-online.scan-'.$type, ['file' => $file]) }}" target="_blank">{{ $file }}</a>
                    </td>
                  </tr>
                </table>
              </div>              
            </div>
          </div>
        </div>
      </div>      
    @endif
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('footer')
  <script>
    jQuery(document).ready(function(){
      $('#NO_HOUSE_BLAWB').val('').focus();
    });
  </script>
@endsection