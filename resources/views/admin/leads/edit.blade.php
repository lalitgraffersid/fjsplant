@extends('admin.layout.master')
@section('content')

<style type="text/css">
   .select12:invalid + .select2 .select2-selection{
       border-color: #dc3545!important;
   }
   .select12:valid + .select2 .select2-selection{
       border-color: #ced4da!important;
   }
   *:focus{
     outline:0px;
   }
</style>
 
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Lead</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                  <li class="breadcrumb-item active">Lead Edit</li>
               </ol>
            </div>
         </div>
      </div>
   </div>

   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-12">
               <div class="card card-primary">
                  <div class="card-header">
                     <h3 class="card-title">Edit <small>Lead</small></h3>
                  </div>
                  @if (count($errors) > 0)       
                     <div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        @foreach ($errors->all() as $error)
                           <span>{{ $error }}</span><br/>
                        @endforeach    
                     </div>         
                  @endif
                  <form id="quickForm" action="{{route('leads.update')}}" method="POST" enctype="multipart/form-data">
                     {{csrf_field()}}

                     <input type="hidden" name="id" value="{{$result->id}}">

                     <div class="card-body">
                        <div class="form-group">
                           <label for="title">Title</label>
                           <input type="text" name="title" id="title" class="form-control" placeholder="Enter Lead Title" value="{{$result->title}}">
                        </div>

                        <div class="form-group">
                           <label for="customer_id">Customer</label>
                           <select name="customer_id" class="customer_id select12 form-control" id="customer_id" data-placeholder="Select a Customer" style="width: 100%;" required >
                              <option value="">Select Customer</option>
                              @foreach($customers as $value)
                                 <option value="{{$value->id}}" <?php if($result->customer_id == $value->id){echo "selected";} ?>>{{$value->name}}</option>
                              @endforeach
                           </select>
                        </div>

                        <div class="form-group">
                           <label for="message">Message</label>
                           <textarea name="message" class="form-control" id="message" rows="3">{{$result->message}}</textarea>
                        </div>

                        <div class="form-group">
                           <label for="user_id">User</label>
                           <select name="user_id" class="user_id select12 form-control" id="user_id" data-placeholder="Select a User" style="width: 100%;" required >
                              <option value="">Select User</option>
                              @foreach($users as $value)
                                 <option value="{{$value->id}}" <?php if($result->user_id == $value->id){echo "selected";} ?>>{{$value->name}}</option>
                              @endforeach
                           </select>
                        </div>

                        <div class="form-group">
                           <label for="status">Status</label>
                           <select name="status" class="status form-control" id="status" data-placeholder="Select a User" style="width: 100%;" required >
                              <option value="">Select Status</option>
                              <option value="New" <?php if($result->status == 'New'){echo "selected";} ?>>New</option>
                              <option value="In Progress" <?php if($result->status == 'In Progress'){echo "selected";} ?>>In Progress</option>
                              <option value="On Hold" <?php if($result->status == 'On Hold'){echo "selected";} ?>>On Hold</option>
                              <option value="Lost" <?php if($result->status == 'Lost'){echo "selected";} ?>>Lost</option>
                              <option value="Closed" <?php if($result->status == 'Closed'){echo "selected";} ?>>Closed</option>
                           </select>
                        </div>

                     </div>
                     <div class="card-footer">
                        <div>
                           <button type="submit" class="btn btn-primary">Submit</button>
                           <a href="{{route('leads.index')}}" class="btn btn-default btn-secondary">Back</a>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
            <div class="col-md-6"></div>
         </div>
      </div>
   </section>
</div>
@endsection

@section('script')
<script src="{{asset('assets/admin/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/jquery-validation/additional-methods.min.js')}}"></script>

<script>

$('.select12').select2({
   theme: 'bootstrap4'
});

$(function () {
   $('#quickForm').validate({
      rules: {
         title: {
            required: true
         },
         user_id: {
            required: true
         },
         status: {
            required: true
         },
      },
      messages: {
         title: {
            required: "Please enter Lead Title",
         },
         user_id: {
            required: "Please select User",
         },
         status: {
            required: "Please select status",
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
  


