@extends('layouts.master')

@section('title') Profile @endsection
@section('page_name') <i class="fas fa-user"></i> Profile @endsection
@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <form action="{{url()->full()}}/{{$user->id}}" method="post" enctype="multipart/form-data">        
        @method('PUT')
        @include('forms.user')
      </form>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection

@section('footer')
  <script>
    jQuery(document).ready(function(){
      $(document).on('click', '.fa-eye', function(){
        $('#inputPassword').get(0).type = 'text';      
        $(this).addClass('d-none');
        $('.fa-eye-slash').removeClass('d-none');
      });
      $(document).on('click', '.fa-eye-slash', function(){
        $('#inputPassword').get(0).type = 'password';      
        $(this).addClass('d-none');
        $('.fa-eye').removeClass('d-none');
      });      
    });
  </script>
@endsection