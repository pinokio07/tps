@extends('layouts.master')

@section('title') User @endsection
@section('page_name') <i class="fas fa-user"></i> User Data @endsection
@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      @if($user->id != '')
        <form action="/administrator/users/{{$user->id}}" method="post" enctype="multipart/form-data">        
          @method('PUT')
      @else
        <form action="/administrator/users" method="post" enctype="multipart/form-data">   
      @endif      
        @include('forms.user')
      </form>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection

@section('footer')
  <script>
    jQuery(document).ready(function(){
      $('.fa-eye').click(function(){
        $('#inputPassword').get(0).type = 'text';      
        $(this).addClass('d-none');
        $('.fa-eye-slash').removeClass('d-none');
      });
      $('.fa-eye-slash').click(function(){
        $('#inputPassword').get(0).type = 'password';      
        $(this).addClass('d-none');
        $('.fa-eye').removeClass('d-none');
      });
    })
  </script>
@endsection