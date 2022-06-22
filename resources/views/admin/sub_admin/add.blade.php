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
               <h1 class="m-0 text-dark">Sub Admin</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">SubAdmin Add</li>
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
                     <h3 class="card-title">Sub Admin Add</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form id="quickForm" role="form" action="{{ route('sub_admin.save') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                 {{csrf_field()}}
                     <div class="card-body">
                        
                        <div class="form-group">
                           <label for="sub_admin">Account Type</label>
                           <select name="account_type" id="account_type" class="form-control">
                              <option value="">Select Account Type</option>
                              <option value="Office">Office</option>
                              <option value="Service">Service</option>
                              <option value="Account">Account</option>
                           </select>
                        </div>

                        <div class="form-group">
                           <label for="sub_admin">User Name</label>
                           <input type="text" name="name" class="form-control" id="sub_admin" placeholder="Enter  Name" autocomplete="off" />
                        </div>
                        <div class="form-group">
                           <label for="user_email">Email</label>
                           <input type="email" name="email" class="form-control" id="user_email" placeholder="Enter Email" autocomplete="off" />
                        </div>
                        <div class="form-group">
                           <label for="user_password">Password</label>
                           <input type="password" name="password" class="form-control" id="user_password" placeholder="Enter Password" autocomplete="off" />
                        </div>
                  
                        <!-- <?php $i=0;?>
                        @foreach($sections as $section) 
                           <?php $roles=DB::table('roles')->where('section_id',$section->id)->orderBy('order','ASC')->get(); ?>
                           <h5>{{$section->section_title}}:</h5>
                           @if(!empty($roles))
                              @foreach($roles as $rol) 
                                 <?php $i++; ?>
                                 <div class="form-group clearfix" style="padding-left: 20px;">
                                    <label for="role_id{{$i}}">{{$rol->name}}: &nbsp;</label>
                                    <input type="hidden" name="role_id{{$i}}" value="{{$rol->id}}">
                                    
                                       <?php $rolid=$rol->action_id;
                                       $action_id=explode(',',$rolid);
                                       foreach($action_id as $actid){
                                          $actions=DB::table('actions')->where('id',$actid)->first(); ?>
                                          <div class="d-inline">
                                             <input type="checkbox" id="checkbox_{{$i}}" name="action_id{{$i}}[]" value="{{$actions->action_slug}}">
                                             <label style="font-weight: 400;" for="checkbox_{{$i}}">{{$actions->action_title}}</label>
                                          </div>
                                       <?php } ?>

                                 </div>  
                              @endforeach 
                           @endif
                        @endforeach    -->             
                
                     </div>
                     <input type="hidden" name="total_row" value="{{$i}}">

                     <div class="card-footer">
                        <div>
                           <button type="submit" class="btn btn-primary">Submit</button>
                           <a href="{{route('sub_admin.index')}}" class="btn btn-default btn-secondary">Back</a>
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
   // $.validator.setDefaults({
   //    submitHandler: function () {
   //       alert( "Form successful submitted!" );
   //    }
   // });
   $('#quickForm').validate({
      rules: {
         account_type: {
            required: true
         },
         name: {
            required: true
         },
         email: {
            required: true,
            email: true,
         },
         password: {
            required: true,
            minlength: 6
         },
      },
      messages: {
         account_type: {
            required: "Please select account type",
         },
         name: {
            required: "Please enter name",
         },
         email: {
            required: "Please enter a email address",
            email: "Please enter a vaild email address"
         },
         password: {
            required: "Please provide a password",
            minlength: "Your password must be at least 6 characters long"
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