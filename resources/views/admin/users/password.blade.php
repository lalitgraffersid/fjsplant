@extends('admin.layout.master')
@section('content')

<style type="text/css">
   .clearfix{
      margin-bottom: auto;
   }
</style>

<?php
  $sections = DB::table('sections')->where('section_slug','!=','modules')->orderBy('section_order','ASC')->get();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"> 
   <!-- Content Header (Page header) -->
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">User</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                  <li class="breadcrumb-item active">User</li>
               </ol>
            </div><!-- /.col -->
         </div><!-- /.row -->
      </div><!-- /.container-fluid -->
   </div>
<!-- /.content-header -->

   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
       
            <div class="col-lg-12"> 
               @if (count($errors) > 0)       
                  <div class="alert alert-danger alert-dismissable">
                     <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                     @foreach ($errors->all() as $error)
                        <span>{{ $error }}</span><br/>
                     @endforeach    
                  </div>         
               @endif
              
              @if(Session::has('message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{Session::get('message')}}</p>
              @endif
        
               <div class="card card-primary">
                  <div class="card-header">
                     <h3 class="card-title">Reset Password</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form id="quickForm" role="form" action="{{ route('users.password.save') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                 {{csrf_field()}}
                     <div class="card-body">
                        <input type="hidden" name="id" value="{{$user->id}}">

                        <div class="form-group">
                           <label for="password">New Password</label>
                           <input type="password" name="password" class="form-control" id="password" placeholder="Enter  Password" autocomplete="false" />
                        </div>

                        <div class="form-group">
                           <label for="confirm_password">Confirm Password</label>
                           <input type="text" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm Password" autocomplete="off" />
                        </div>              
                
                     </div>

                     <div class="card-footer">
                        <div>
                           <button type="submit" class="btn btn-primary">Submit</button>
                           <a href="{{route('users.index')}}" class="btn btn-default btn-secondary">Back</a>
                        </div>
                     </div>
                  </form>
               </div> 
            </div>
            <div class="col-lg-2"></div>
         </div>
      </div><!-- /.container-fluid -->
   </section>
   <!-- /.content -->
</div>

@endsection

@section('script')
<script src="{{asset('assets/admin/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/jquery-validation/additional-methods.min.js')}}"></script>

<script>
$(function () {
   $('#quickForm').validate({
      rules: {
         password: {
            required: true,
            minlength: 4
         },
         confirm_password: {
            required: true,
            minlength: 4,
            equalTo : "#password"
         }
      },
      messages: {
         password: {
            required: "Please provide a password",
            minlength: "Password must be at least 4 characters long"
         },
         confirm_password: {
            required: "Please provide a password",
            minlength: "Password must be at least 4 characters long",
            equalTo: "Password do not match!",
         },
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
         error.addClass('invalid-feedback');
         element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
         $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
         $(element).removeClass('is-invalid');
      }
   });
});

</script>

@endsection